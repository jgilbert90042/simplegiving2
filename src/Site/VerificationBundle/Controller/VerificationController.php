<?php

namespace Site\VerificationBundle\Controller;

use Site\VerificationBundle\Form\ProfileType;
use Site\VerificationBundle\Form\PPInfoType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class VerificationController extends Controller
{
    /**
     * @Route("/verification/", name="_verification_index")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);
        if (is_null($verifier->getFirstName())) {
          return new RedirectResponse($this->generateUrl('_vs_edit'));
        }
        return $this->render('SiteVerificationBundle:Verification:index.html.twig',
          array('unreadMessages' => $unread));
    }

    /**
     * @Route("/verification/viewBeneficiaries", name="_vs_list_beneficiaries")
     * @Template()
     */
    public function listBeneficiariesAction()
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);

        $em = $this->getDoctrine()->getEntityManager();
        $beneficiaries = $this->getDoctrine()
                ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                ->findByVerifier($verifier);

        $b = array();

        foreach ($beneficiaries as $beneficiary) {

          $messages_list_query = $em->createQuery(
            'SELECT m from SiteMessageBundle:Message m ' .
            'WHERE (m.user=:userId or m.sender=:userId) ' . 
            'AND m.status NOT IN (4) AND m.reviewed = 0')
            ->setParameter('userId', $beneficiary->getUser()->getId());

          $ml_results = $messages_list_query->getResult();
          $messages = array();
          foreach ($ml_results as $mlr) {
            if ($mlr->getUser()->getRole() == 'ROLE_DONOR' or $mlr->getSender()->getRole() == 'ROLE_DONOR') {
              array_push($messages,$mlr);
            }
          }
          
          $b[$beneficiary->getId()]['beneficiary'] = $beneficiary; 
          $b[$beneficiary->getId()]['messageCount'] = count($messages);
        }

        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('referrer', $request->getRequestUri());

        return $this->render(
	    'SiteVerificationBundle:Verification:listBeneficiaries.html.twig',
	    array('beneficiaries' => $b,
        'unreadMessages' => $unread));
    }

    /**
     * @Route("/verification/editProfile", name="_vs_edit")
     * @Template()
     */
    public function editProfileAction(Request $request)
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);

        $form = $this->get('form.factory')->create(new ProfileType());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {
                 $verifier->setFirstName(
                   $form->get('first_name')->getData());
                 $verifier->setLastName(
                   $form->get('last_name')->getData());
                 $verifier->setPosition(
                   $form->get('position')->getData());
                 $verifier->setPhone(
                   $form->get('phone')->getData());
                 $verifier->setFax(
                   $form->get('fax')->getData());

                 $em = $this->getDoctrine()->getEntityManager();
                 $em->flush();

                return new RedirectResponse(
                  $this->generateUrl('_verification_index'));
            }
        }

        $form->setData($verifier);

        return $this->
            render('SiteVerificationBundle:Verification:' .
                'editProfile.html.twig',
                array('form' => $form->createView(),
                  'unreadMessages' => $unread));

    }

    /**
     * @Route("/verification/editPPInfo", name="_vs_ppedit")
     * @Template()
     */
    public function editPPInfoAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);

        $form = $this->get('form.factory')->create(new PPInfoType());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {
              
              $verifier->setPPEmail(
                $form->get('PPEmail')->getData());

              $button_text = $form->get('PPButton')->getData();

              $button_code = $verifier->getPPButton();
              $matches = array();

              if ($button_text) {
                if (preg_match('/name="hosted_button_id" value="([A-Z0-9]+)/',
                  $button_text,$matches)) {
                    $button_code = $matches[1];
                } else {
                  if (preg_match('/^([A-Z0-9]+)$/', $button_text, $matches)) {
                     $button_code = $matches[1];
                   }
                 }
               }

               $verifier->setPPButton($button_code);
               
               $em = $this->getDoctrine()->getEntityManager();
               $em->flush();

               return new RedirectResponse(
                 $this->generateUrl('_verification_index'));
            }
        }

        $form->setData($verifier);

        return $this->
            render('SiteVerificationBundle:Verification:' .
                'editPPInfo.html.twig',
                array('form' => $form->createView(),
                  'unreadMessages' => $unread));
    }


    public function getVerifier($user) {

      return $this->getDoctrine()
		    ->getRepository('SiteVerificationBundle:Verifier')
		    ->findOneBy(array('user' => $user->getId()));
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
