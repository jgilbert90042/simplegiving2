<?php

namespace Site\VerificationBundle\Controller;

use Site\UserBundle\Entity\User;
use Site\BeneficiaryBundle\Entity\Beneficiary;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Site\VerificationBundle\Form\AddBeneficiary1Type;
use Site\VerificationBundle\Form\EditBenPersonalType;
use Site\VerificationBundle\Form\EditBenNeedType;

class BeneficiaryController extends Controller
{

    /**
     * @Route("/verification/newBeneficiary", name="_vs_new_beneficiary")
     * @Template()
     */
    public function newBeneficiaryAction(Request $request)
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);

        $form = $this->get('form.factory')->create(new AddBeneficiary1Type());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {

                $username = $form->get('username')->getData();
                $email = $form->get('email')->getData();

                $user = new User();
                $pass_string=$form->get('password')->getData();
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password =
                   $encoder->encodePassword($pass_string, $user->getSalt());

                $role = 'ROLE_BENEFICIARY';
                $ip = $_SERVER['REMOTE_ADDR'];
                $maritalStatus = $this->getDoctrine()
                ->getRepository('SiteMiscBundle:MaritalStatus')
                ->findOneBy(array('id' => 1));

                $beneficiary = new Beneficiary();

                $user->setUsername($username);
                $user->setPassword($password);
                $user->setEmail($email);
                $user->setRole($role);
                $user->setCreatedIp($ip);
                $user->setCreated(new \DateTime(date("Y-m-d H:i:s")));

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($user);

                $beneficiary->setUser($user);
                $beneficiary->setVerifier($verifier);
                $em->persist($beneficiary);
                $em->flush();
                $this->get('session')->setFlash('notice', $username
                  . ' has been registered!');
                return new RedirectResponse(
                  $this->generateUrl('_vs_list_beneficiaries'));
            }

        }

        return $this->
            render('SiteVerificationBundle:Beneficiary:' .
                'newBeneficiary.html.twig', 
                array('form' => $form->createView(),
                    'unreadMessages' => $unread));
    }

    /**
     * @Route("/verification/editBenPersonal/{beneficiaryId}", name="_vs_edit_pers_ben")
     * @Template()
     */
    public function editBenPersonalAction(Request $request, $beneficiaryId)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);

        $beneficiary = $this->getDoctrine()
                ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                ->find($beneficiaryId);

        if ($beneficiary->getVerifier()->getId() != $verifier->getId()) {
              $this->get('session')->setFlash('notice',
                'You do not have edit permission for that beneficiary.');
            return new RedirectResponse(
              $this->generateUrl('_verification_index'));
        }

        $form = $this->get('form.factory')->create(new EditBenPersonalType());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {

                 $beneficiary->setFirstName(
                   $form->get('first_name')->getData());
                 $beneficiary->setLastName(
                   $form->get('last_name')->getData());
                 $beneficiary->setSsn(
                   $form->get('ssn')->getData());
                 $beneficiary->setAddress(
                   $form->get('address')->getData());
                 $beneficiary->setCity(
                   $form->get('city')->getData());
                 $beneficiary->setState(
                   $form->get('state')->getData()->getAbbr());
                 $beneficiary->setPhone(
                   $form->get('phone')->getData());
                 $beneficiary->setFax(
                   $form->get('fax')->getData());
                 $beneficiary->setMaritalStatus(
                   $form->get('marital_status')->getData());
                 $beneficiary->setDependants(
                   $form->get('dependants')->getData());
                $beneficiary->setUpdated(new \DateTime(date("Y-m-d H:i:s")));

                 $em = $this->getDoctrine()->getEntityManager();
                 $em->persist($beneficiary);
                 $em->flush();

                return new RedirectResponse(
                  $this->generateUrl('_verification_index'));
            }
        }
        $state = $this->getDoctrine()
          ->getRepository('SiteMiscBundle:State')
          ->findOneByAbbr($beneficiary->getState());
        
        $beneficiary->setState($state);
        $form->setData($beneficiary);

        return $this->
            render('SiteVerificationBundle:Beneficiary:' .
                'editBenPersonal.html.twig', array(
		          'form' => $form->createView(),
                  'beneficiary' => $beneficiary,
                  'unreadMessages' => $unread
            ));
    }

    /**
     * @Route("/verification/editBenNeed/{beneficiaryId}", name="_vs_edit_need_ben")
     * @Template()
     */
    public function editBenNeedAction(Request $request, $beneficiaryId)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);

        $beneficiary = $this->getDoctrine()
                ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                ->find($beneficiaryId);

        if ($beneficiary->getVerifier()->getId() != $verifier->getId()) {
              $this->get('session')->setFlash('notice',
                'You do not have edit permission for that beneficiary.');
            return new RedirectResponse(
              $this->generateUrl('_verification_index'));
        }

        $form = $this->get('form.factory')->create(new EditBenNeedType());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {

                 $urgency = $form->get('needUrgency')->getData()->getId();

                 $need = $form->get('need')->getData();

                 $beneficiary->setNeedOrig($need);
                 $beneficiary->setNeed($need);
                 $beneficiary->setNeedUrgency($urgency);
                 $beneficiary->setNeedDesc(
                   $form->get('needDesc')->getData());
                 $beneficiary->setUpdated(new \DateTime(date("Y-m-d H:i:s")));
                 $em = $this->getDoctrine()->getEntityManager();
                 $em->persist($beneficiary);
                 $em->flush();

                return new RedirectResponse(
                  $this->generateUrl('_verification_index'));
            }
        }
        $form->setData($beneficiary);

        return $this->
            render('SiteVerificationBundle:Beneficiary:' .
                'editBenNeed.html.twig', array(
		  'form' => $form->createView(),
                  'beneficiary' => $beneficiary,
                  'unreadMessages' => $unread
            ));
    }

    /**
     * @Route("/verification/toggleSearchable/{beneficiaryId}", name="_vs_toggle_search_ben")
     * @Template()
     */
    public function toggleSearchableAction($beneficiaryId)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);

        $beneficiary = $this->getDoctrine()
                ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                ->find($beneficiaryId);

        if ($beneficiary->getVerifier()->getId() != $verifier->getId()) {
              $this->get('session')->setFlash('notice',
                'You do not have edit permission for that beneficiary.');
            return new RedirectResponse(
              $this->generateUrl('_verification_index'));
        }
 
        $first_name = $beneficiary->getFirstName();
        $need = $beneficiary->getNeed(); 
        $searchable = $beneficiary->getSearchable();

        if (!($searchable or (isset($first_name) and ($need != 0)) )) {
            $this->get('session')->setFlash('notice',
                "Please complete the beneficiary profile before setting to searchable.");
            return new RedirectResponse($this->generateUrl('_vs_list_beneficiaries'));
        }

        $em = $this->getDoctrine()->getEntityManager();

        if ($beneficiary->getSearchable() == 0) {
            $beneficiary->setSearchable(1);
            $this->get('session')->setFlash('notice',
              "Beneficiary profile set to searchable.");
        } else {
            $beneficiary->setSearchable(0);
    
            $searchResults = $this->getDoctrine()
                ->getRepository('SiteSearchBundle:SearchResult')
                ->findBy(array('beneficiary' => $beneficiary));

            foreach ($searchResults as $searchResult) {
                $em->remove($searchResult);
            }
    
            $this->get('session')->setFlash('notice',
              "Beneficiary profile set to unsearchable.");
        }
        $em->flush();

        return new RedirectResponse($this->generateUrl('_vs_list_beneficiaries'));
    }

    private function getVerifier($user) {

        $verifier = $this->getDoctrine()
		->getRepository('SiteVerificationBundle:Verifier')
		->findOneBy(array('user' => $user->getId()));

        return $verifier;
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
}
