<?php

namespace Site\AdminBundle\Controller;

use Site\UserBundle\Entity\User;
use Site\VerificationBundle\Entity\Verifier;
use Site\ChurchBundle\Entity\ChurchDenomination;
use Site\ChurchBundle\Entity\Church;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Site\AdminBundle\Form\AddVerifierType;
use Site\AdminBundle\Form\AddChurchType;
use Site\AdminBundle\Form\AddChurchDenominationType;

class AdminController extends Controller
{
    /**
     * @Route("/admin/", name="_admin_index")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('referrer', $request->getRequestUri());

        return $this->render('SiteAdminBundle:Admin:index.html.twig',
            array('unreadMessages' => $unread,
                'user' => $user));
    }

    /**
     * @Route("/admin/newVerifier", name="_a_new_vs")
     * @Template()
     */
    public function newVerifierAction(Request $request)
    {

        //$admin = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $form = $this->get('form.factory')->create(new AddVerifierType());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {

                $user = new User;
                $verifier = new Verifier;

                $username = $form->get('username')->getData();
                $email = $form->get('email')->getData();
                $church = $form->get('church')->getData();

                $pass_string=$form->get('password')->getData();
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password =
                   $encoder->encodePassword($pass_string, $user->getSalt());

                $role = 'ROLE_VERIFICATION';
                $ip = $_SERVER['REMOTE_ADDR'];

                $user->setUsername($username);
                $user->setPassword($password);
                $user->setEmail($email);
                $user->setRole($role);
                $user->setCreatedIp($ip);
                $user->setCreated(new \DateTime(date("Y-m-d H:i:s")));

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($user);

                $verifier->setUser($user);
                $verifier->setChurch($church);
                $verifier->setActivated(0);
                $em->persist($verifier);

                $church->addVerifier($verifier);

                $em->flush();
                $this->get('session')->setFlash('notice', $username
                  . ' has been registered!');
                return new RedirectResponse(
                  $this->generateUrl('_admin_index'));
            }

        }

        return $this->
            render('SiteAdminBundle:Admin:' .
                'newVerifier.html.twig',
                array('form' => $form->createView(),
                    'unreadMessages' => $unread));
    }

    /**
     * @Route("/admin/newChurch", name="_a_new_church")
     * @Template()
     */
    public function newChurchAction(Request $request)
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $form = $this->get('form.factory')->create(new AddChurchType());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {

                $church = new Church;
                $state = $form->get('state')->getData();

	            $church->setName($form->get('name')->getData());
		        $church->setDenomination($form->get('denomination')->getData());
                $church->setAddress($form->get('address')->getData());
                $church->setCity($form->get('city')->getData());
                $church->setState($state->getAbbr());
                $church->setZip($form->get('zip')->getData());
                $church->setPhone($form->get('phone')->getData());
                $church->setFax($form->get('fax')->getData());
                $church->setEmail($form->get('email')->getData());
                $church->setWebsite($form->get('website')->getData());
                $church->setVisible(1);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($church);

                $state->addChurch($church);

                $em->flush();
                $this->get('session')->setFlash('notice', $church->getName()
                  . ' has been registered!');
                return new RedirectResponse(
                  $this->generateUrl('_admin_index'));
            }

        }

        return $this->
            render('SiteAdminBundle:Admin:' .
                'newChurch.html.twig',
                array('form' => $form->createView(),
                    'unreadMessages' => $unread));
    }

    /**
     * @Route("/admin/church/edit/{churchId}", name="_a_edit_church")
     * @Template()
     */
    public function editChurchAction(Request $request, $churchId)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $church = $this->getDoctrine()
                ->getRepository('SiteChurchBundle:Church')
                ->find($churchId);
       $form = $this->get('form.factory')->create(new AddChurchType(), $church);
//        $form = $this->createForm(new AddChurchType(), $default);

        $state = $this->getDoctrine()
                ->getRepository('SiteMiscBundle:State')
                ->findOneByAbbr($church->getState());

        if ('POST' == $request->getMethod()) {

            $old_state = $state;
            $form->bindRequest($request);

            if ($form->isValid()) {

                $church->setName($form->get('name')->getData());
                $church->setDenomination($form->get('denomination')->getData());
                $church->setAddress($form->get('address')->getData());
                $church->setCity($form->get('city')->getData());
                $church->setZip($form->get('zip')->getData());
                $church->setPhone($form->get('phone')->getData());
                $church->setFax($form->get('fax')->getData());
                $church->setEmail($form->get('email')->getData());
                $church->setWebsite($form->get('website')->getData());

                $state = $form->get('state')->getData();
//                echo 'old state = ' . $old_state->getAbbr() . "\n";
//                echo 'New state = ' . $state->getAbbr() . "\n";
//                exit;
                $em = $this->getDoctrine()->getEntityManager();
                if ($state->getAbbr() != $old_state->getAbbr()) {
                    $old_state->removeChurch($church);
                    $em->flush();

                    $state->addChurch($church);

                }
//                    $church->setState($state->getAbbr());

                $em->flush();
                $this->get('session')->setFlash('notice', $church->getName()
                  . ' has been edited!');
                return new RedirectResponse(
                  $this->generateUrl('_a_church_list'));
            }
        }

        $church->setState($state);

        $form->setData($church);

        return $this->
            render('SiteAdminBundle:Admin:' .
                'editChurch.html.twig',
                array('form' => $form->createView(),
                    'church' => $church,
                    'unreadMessages' => $unread));
    }

    /**
     * @Route("/admin/church/list", name="_a_church_list")
     * @Template()
     */
    public function churchListAction()
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $churches = $this->getDoctrine()
                    ->getRepository('SiteChurchBundle:Church')
                    ->findAll();
        
        return $this->
            render('SiteAdminBundle:Admin:' .
                'listChurches.html.twig',
                array('churches' => $churches,
                    'unreadMessages' => $unread));

    }

    /**
     * @Route("/admin/church/toggleVisible/{churchId}", name="_a_church_visible")
     * @Template()
     */
    public function churchToggleVisibleAction($churchId)
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $church = $this->getDoctrine()
                    ->getRepository('SiteChurchBundle:Church')
                    ->findOneById($churchId);
        
        if ($church->getVisible() == 0) {
            $church->setVisible(1);
        } else {
            $church->setVisible(0);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->flush();

        return new RedirectResponse($this->generateUrl('_a_church_list'));

    }

    /**
     * @Route("/admin/vs/list", name="_a_vs_list")
     * @Template()
     */
    public function vsListAction()
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $verifiers = $this->getDoctrine()
                    ->getRepository('SiteVerificationBundle:Verifier')
                    ->findAll();

        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('referrer', $request->getRequestUri());
        
        return $this->
            render('SiteAdminBundle:Admin:' .
                'listVerifiers.html.twig',
                array('verifiers' => $verifiers,
                    'unreadMessages' => $unread));
    }

    /**
     * @Route("/admin/vs/toggleActive/{verifierId}", name="_a_vs_activate")
     * @Template()
     */
    public function vsToggleActiveAction(Request $request, $verifierId)
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $verifier = $this->getDoctrine()
                    ->getRepository('SiteVerificationBundle:Verifier')
                    ->findOneById($verifierId);
        
        if ($verifier->getActivated() == 0) {
            $verifier->setActivated(1);
        } else {
            $this->setUnactivated($verifier);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->flush();

        $session = $request->getSession();
        return new RedirectResponse($session->get('referrer'));

    }

    /**
     * @Route("/admin/vs/view/{verifierId}", name="_a_vs_view")
     * @Template()
     */
    public function vsViewAction($verifierId)
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $verifier = $this->getDoctrine()
                    ->getRepository('SiteVerificationBundle:Verifier')
                    ->findOneById($verifierId);

        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('referrer', $request->getRequestUri());

        return $this->
            render('SiteAdminBundle:Admin:' .
                'viewVerifier.html.twig',
                array('verifier' => $verifier,
                    'unreadMessages' => $unread));
        
    }

    /**
     * @Route("/admin/vBeneficiary/list/{verifierId}", name="_a_vs_b_list")
     * @Template()
     */
    public function vbListAction($verifierId)
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $verifier = $this->getDoctrine()
                    ->getRepository('SiteVerificationBundle:Verifier')
                    ->findOneById($verifierId);

        $beneficiaries = $this->getDoctrine()
                    ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                    ->findByVerifier($verifier);

        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('referrer', $request->getRequestUri());
        
        return $this->
            render('SiteAdminBundle:Admin:' .
                'listBeneficiaries.html.twig',
                array('beneficiaries' => $beneficiaries,
                    'unreadMessages' => $unread));
    }

    /**
     * @Route("/admin/beneficiary/list", name="_a_b_list")
     * @Template()
     */
    public function bListAction()
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $beneficiaries = $this->getDoctrine()
                    ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                    ->findAll();

        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('referrer', $request->getRequestUri());
        
        return $this->
            render('SiteAdminBundle:Admin:' .
                'listBeneficiaries.html.twig',
                array('beneficiaries' => $beneficiaries,
                    'unreadMessages' => $unread));
    }

    /**
     * @Route("/admin/beneficiary/view/{beneficiaryId}", name="_a_b_view")
     * @Template()
     */
    public function bviewAction($beneficiaryId)
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $beneficiary = $this->getDoctrine()
                    ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                    ->findOneById($beneficiaryId);

        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('referrer', $request->getRequestUri());
        
        return $this->
            render('SiteAdminBundle:Admin:' .
                'viewBeneficiary.html.twig',
                array('beneficiary' => $beneficiary,
                    'unreadMessages' => $unread));
    }

    /**
     * @Route("/admin/b/toggleSearchable/{beneficiaryId}", name="_a_b_searchable")
     * @Template()
     */
    public function bToggleSearchableAction(Request $request, $beneficiaryId)
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $beneficiary = $this->getDoctrine()
                    ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                    ->findOneById($beneficiaryId);
        
        if ($beneficiary->getSearchable() == 0) {
            $beneficiary->setSearchable(1);
        } else {
            $this->setUnsearchable($beneficiary);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->flush();

        $session = $request->getSession();
 //       echo $session->get('referrer');
//        exit;
        return new RedirectResponse($session->get('referrer'));

    }

    /**
     * @Route("/admin/donor/list", name="_a_d_list")
     * @Template()
     */
    public function dListAction()
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $donors = $this->getDoctrine()
                    ->getRepository('SiteDonorBundle:Donor')
                    ->findAll();

        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('referrer', $request->getRequestUri());
        
        return $this->
            render('SiteAdminBundle:Admin:' .
                'listDonors.html.twig',
                array('donors' => $donors,
                    'unreadMessages' => $unread));
    }    

    /**
     * @Route("/admin/newDenomination", name="_a_new_denomination")
     * @Template()
     */
    public function newDenominationAction(Request $request)
    {

        //$user = $this->get('security.context')->getToken()->getUser();
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $form = $this->get('form.factory')->
          create(new AddChurchDenominationType());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {

                $denomination = new ChurchDenomination;
                $denomination->setName($form->get('name')->getData());
                
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($denomination);

                $em->flush();
                $this->get('session')->setFlash('notice', 
                  $denomination->getName() . ' has been added!');
                return new RedirectResponse(
                  $this->generateUrl('_a_new_church'));
            }

        }

        return $this->
            render('SiteAdminBundle:Admin:' .
                'newChurchDenomination.html.twig',
                array('form' => $form->createView(),
                    'unreadMessages' => $unread));
    }

    private function getUnreadMessages($user) {
        
        $unread = $this->getDoctrine()
                    ->getRepository('SiteMessageBundle:MessageStatus')
                    ->findOneBy(array('name' => 'Unread'));
        $messages = $this->getDoctrine()
                    ->getRepository('SiteMessageBundle:Message')
                    ->findBy(array('user' => $user, 'status' => $unread));
        return count($messages);
    }

    private function setUnactivated($verifier) {

        $beneficiaries = $this->getDoctrine()
            ->getRepository('SiteBeneficiaryBundle:Beneficiary')
            ->findByVerifier($verifier);
        foreach ($beneficiaries as $beneficiary) {
            $searchResults = $this->getDoctrine()
                ->getRepository('SiteSearchBundle:SearchResult')
                ->findBy(array('beneficiary' => $beneficiary));
        
            foreach ($searchResults as $searchResult) {
                $em->remove($searchResult);
            }
        }
        $verifier->setActivated(0);
        return true;
    }

    private function setUnsearchable($beneficiary) {
        
        $searchResults = $this->getDoctrine()
            ->getRepository('SiteSearchBundle:SearchResult')
            ->findBy(array('beneficiary' => $beneficiary));
        
        foreach ($searchResults as $searchResult) {
            $em->remove($searchResult);
        }

        $beneficiary->setSearchable(0);

        return true;
    }
}
