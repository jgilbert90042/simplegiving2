<?php

namespace Site\VerificationBundle\Controller;

use Site\VerificationBundle\Form\ProfileType;
use Site\VerificationBundle\Form\PPInfoType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MessageReviewController extends Controller
{

    /**
     * @Route("/verification/reviewMessagesList/{userId}", name="_vs_review_messages_list")
     * @Template()
     */
    public function reviewMessagesListAction($userId)
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);

        $target = $this->getDoctrine()
                ->getRepository('SiteUserBundle:User')
                ->findOneById($userId);

        if ($target->getRole() == 'ROLE_BENEFICIARY')
        {        
            $target_entity = $this->getDoctrine()
              ->getRepository('SiteBeneficiaryBundle:Beneficiary')
              ->findOneByUser($userId);
        } else {
            $target_entity = $this->getDoctrine()
              ->getRepository('SiteDonorBundle:Donor')
              ->findOneByUser($userId);
        }


        if (($user->getRole() != 'ROLE_ADMIN') && 
             ($target->getRole() == 'ROLE_BENEFICIARY' && $target_entity->getVerifier()->getId() != $verifier->getId())) {
          $this->get('session')->setFlash('notice',
            'You do not have permission to review messages for that beneficiary.');
            return new RedirectResponse(
              $this->generateUrl('_verification_index'));
        }

      $em = $this->getDoctrine()->getEntityManager();
      $messages_list_query = $em->createQuery(
        'SELECT m from SiteMessageBundle:Message m ' .
        'WHERE m.user=:userId or m.sender=:userId')
        ->setParameter('userId', $target->getId());

      $ml_results = $messages_list_query->getResult();
      $messages = array();
      foreach ($ml_results as $mlr) {
        if ($mlr->getUser()->getRole() == 'ROLE_DONOR' or $mlr->getSender()->getRole() == 'ROLE_DONOR') {
          array_push($messages,$mlr);
        }
      }

      $request = $this->getRequest();
      $session = $request->getSession();
      $session->set('referrer', $request->getRequestUri());

      return $this->render('SiteVerificationBundle:Beneficiary:reviewMessagesList.html.twig',
      array('messages' => $messages, 
        'beneficiary' => $target_entity,
        'user' => $user,
        'unreadMessages' => $unread));
    }

    /**
     * @Route("/verification/reviewMessage/{messageId}", name="_vs_review_message")
     * @Template()
     */
    public function reviewMessageAction($messageId)
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);

        $message = $this->getDoctrine()
                ->getRepository('SiteMessageBundle:Message')
                ->find($messageId);

        if ($message->getUser()->getRole() == 'ROLE_BENEFICIARY') {
          $beneficiary = $this->getDoctrine()
            ->getRepository('SiteBeneficiaryBundle:Beneficiary')
            ->findOneByUser($message->getUser()->getId());
        } else {
          $beneficiary = $this->getDoctrine()
            ->getRepository('SiteBeneficiaryBundle:Beneficiary')
            ->findOneByUser($message->getSender()->getId());
        }

        if (($user->getRole() != 'ROLE_ADMIN') && ($beneficiary->getVerifier()->getId() != $verifier->getId())) {
          $this->get('session')->setFlash('notice',
            'You do not have permission to review messages for that beneficiary.');
            return new RedirectResponse(
              $this->generateUrl('_verification_index'));
        }

      $request = $this->getRequest();
      $session = $request->getSession();
      $returnUrl = $session->get('referrer');

      return $this->render('SiteVerificationBundle:Beneficiary:reviewMessage.html.twig',
      array('message' => $message, 
        'user' => $user,
        'unreadMessages' => $unread,
        'returnUrl' => $returnUrl));
    }

    /**
     * @Route("/verification/blockMessage/{messageId}", name="_vs_block_message")
     * @Template()
     */
    public function blockMessageAction($messageId)
    {

      $user = $this->get('security.context')->getToken()->getUser();
      $unread = $this->getUnreadMessages($user);
      $verifier = $this->getVerifier($user);

      $message = $this->getDoctrine()
        ->getRepository('SiteMessageBundle:Message')
        ->find($messageId);

      if ($message->getUser()->getRole() == 'ROLE_BENEFICIARY') {
      	$beneficiary = $this->getDoctrine()
          ->getRepository('SiteBeneficiaryBundle:Beneficiary')
          ->findOneByUser($message->getUser()->getId());
      } else {
      	$beneficiary = $this->getDoctrine()
          ->getRepository('SiteBeneficiaryBundle:Beneficiary')
          ->findOneByUser($message->getSender()->getId());
      }

      $status = $this->getDoctrine()
                  ->getRepository('SiteMessageBundle:MessageStatus')
                  ->findOneBy(array('name' => 'Blocked'));        

      if (($user->getRole() != 'ROLE_ADMIN') && ($beneficiary->getVerifier()->getId() != $verifier->getId())) {
        $this->get('session')->setFlash('notice',
          'You do not have permission to block messages for that beneficiary.');
        return new RedirectResponse(
          $this->generateUrl('_verification_index'));
      }

      $message->setStatus($status);
      $em = $this->getDoctrine()->getEntityManager();
      $em->flush();

      $request = $this->getRequest();
      $session = $request->getSession();
      $returnUrl = $session->get('referrer');

      return $this->render('SiteVerificationBundle:Beneficiary:reviewMessage.html.twig',
      array('message' => $message, 
        'user' => $user,
        'unreadMessages' => $unread,
        'returnUrl' => $returnUrl));
    }

    /**
     * @Route("/verification/blockAllMessages/{messageId}", name="_vs_block_all_messages")
     * @Template()
     */
    public function blockAllMessagesAction($messageId)
    {

      $user = $this->get('security.context')->getToken()->getUser();
      $unread = $this->getUnreadMessages($user);
      $verifier = $this->getVerifier($user);

      $message = $this->getDoctrine()
        ->getRepository('SiteMessageBundle:Message')
        ->find($messageId);

      if ($message->getUser()->getRole() == 'ROLE_BENEFICIARY') {
      	
      	$beneficiary_user = $message->getUser();
      	$donor_user = $message->getSender();

      } else {

      	$beneficiary_user = $message->getSender();
      	$donor_user = $message->getUser();

      }

      if ($beneficiary_user->getRole() == 'ROLE_BENEFICIARY') {
        $beneficiary = $this->getDoctrine()
		      ->getRepository('SiteBeneficiaryBundle:Beneficiary')
		      ->findOneBy(array('user' => $beneficiary_user));
      } 

      if ($donor_user->getRole() == 'ROLE_DONOR') {
        $donor = $this->getDoctrine()
		      ->getRepository('SiteDonorBundle:Donor')
		      ->findOneBy(array('user' => $donor_user));
      }

      if (!(isset($beneficiary) && isset($donor))) {
        $this->get('session')->setFlash('notice',
          'You are trying to block a conversation that is not between a donor and a beneficiary.');
        return new RedirectResponse(
          $this->generateUrl('_vs_review_messages_list', array('userId' => $beneficiary_user->getId())));
      }

      $status = $this->getDoctrine()
                  ->getRepository('SiteMessageBundle:MessageStatus')
                  ->findOneBy(array('name' => 'Blocked'));        

      if (($user->getRole() != 'ROLE_ADMIN') && ($beneficiary->getVerifier()->getId() != $verifier->getId())) {
        $this->get('session')->setFlash('notice',
          'You do not have permission to block messages for that beneficiary.');
        return new RedirectResponse(
          $this->generateUrl('_verification_index'));
      }

      $beneficiaryMessages = $this->getDoctrine()
        ->getRepository('SiteMessageBundle:Message')
        ->findBy(array('user' => $beneficiary_user, 'sender' => $donor_user));

      foreach ($beneficiaryMessages as $message) 
      {
          $message->setStatus($status);
          $message->setReviewed(1);
      }

      $donorMessages = $this->getDoctrine()
        ->getRepository('SiteMessageBundle:Message')
        ->findBy(array('sender' => $beneficiary_user, 'user' => $donor_user));

      foreach ($donorMessages as $message) 
      {
          $message->setStatus($status);
          $message->setReviewed(1);
      }

      $donor_blocked = $donor_user->getBlockedFromMe();
      $donor_blocked_array = array();
 
      foreach ($donor_blocked as $user) {
        $donor_blocked_array[] = $user->getId();
      }

      if (!in_array($beneficiary_user->getId(),$donor_blocked_array)) {
        $donor_user->addBlockedFromMe($beneficiary_user);
      }

      $beneficiary_blocked = $beneficiary_user->getBlockedFromMe();
      $beneficiary_blocked_array = array();
 
      foreach ($beneficiary_blocked as $user) {
        $beneficiary_blocked_array[] = $user->getId();
      }

      if (!in_array($donor_user->getId(),$beneficiary_blocked_array)) {
        $beneficiary_user->addBlockedFromMe($donor_user);
      }


      $em = $this->getDoctrine()->getEntityManager();
      $searchResult = $this->getDoctrine()
          ->getRepository('SiteSearchBundle:SearchResult')
          ->findOneBy(array('donor' => $donor, 'beneficiary' => $beneficiary));
      if (is_object($searchResult)) {
        $em->remove($searchResult);
      }

      $em->flush();

	  return new RedirectResponse(
              $this->generateUrl('_vs_review_messages_list', array('userId' => $beneficiary_user->getId())));
    }
    
    /**
     * @Route("/verification/ToggleReview/{messageId}", name="_vs_toggle_review")
     * @Template()
     */
    public function ToggleReviewAction($messageId)
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);

        $message = $this->getDoctrine()
                ->getRepository('SiteMessageBundle:Message')
                ->find($messageId);

        $beneficiary = $this->getDoctrine()
        ->getRepository('SiteBeneficiaryBundle:Beneficiary')
        ->findOneByUser($message->getUser()->getId());

        if ($beneficiary->getVerifier()->getId() != $verifier->getId()) {
          $this->get('session')->setFlash('notice',
            'You do not have permission to toggle that message as reviewed.');
            return new RedirectResponse(
              $this->generateUrl('_verification_index'));
        }

        if ($message->getReviewed() == 0) {
          $message->setReviewed(1);
        } else {
          $message->setReviewed(0);
        }
 
        $em = $this->getDoctrine()->getEntityManager();
        $em->flush();

        return new RedirectResponse(
          $this->generateUrl('_vs_review_messages_list', array('userId' => $beneficiary->getUser()->getId())));
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