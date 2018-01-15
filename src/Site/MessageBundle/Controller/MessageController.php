<?php

namespace Site\MessageBundle\Controller;

use Site\MessageBundle\Form\MessageType;
use Site\MessageBundle\Entity\Message;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MessageController extends Controller
{
    /**
     * @Route("/message/list", name="_message_list")
     * @Template()
     */
    public function listAction()
    {

      $user = $this->get('security.context')->getToken()->getUser();
      $unread = $this->getUnreadMessages($user);

      $em = $this->getDoctrine()->getEntityManager();
      $message_list_query = $em->createQuery(
        'SELECT m from SiteMessageBundle:Message m ' .
        'WHERE m.user=:userId and m.status not in (3,4)')
        ->setParameter('userId', $user->getId());

      $ml_results = $message_list_query->getResult();
      $messages = array();
      foreach ($ml_results as $message) {
        $sender = $message->getSender();
        $messages[$message->getId()]['message'] = $message;
        if (is_object($sender)) {
          $messages[$message->getId()]['entity'] = $this->getUserEntity($sender);
        }
      }

      return $this->render('SiteMessageBundle:Message:listMessages.html.twig',
      array('messages' => $messages, 'unreadMessages' => $unread));
    }

    /**
     * @Route("/message/list_deleted", name="_message_list_deleted")
     * @Template()
     */
    public function listDeletedAction()
    {

      $user = $this->get('security.context')->getToken()->getUser();
      $unread = $this->getUnreadMessages($user);

      $em = $this->getDoctrine()->getEntityManager();
      $message_list_query = $em->createQuery(
        'SELECT m from SiteMessageBundle:Message m ' .
        'WHERE m.user=:userId and m.status in (3)')
        ->setParameter('userId', $user->getId());

      $ml_results = $message_list_query->getResult();
      $messages = array();
      foreach ($ml_results as $message) {
        $sender = $message->getSender();
        $messages[$message->getId()]['message'] = $message;
        if (is_object($sender)) {
          $messages[$message->getId()]['entity'] = $this->getUserEntity($sender);
        }
      }
      return $this->render('SiteMessageBundle:Message:listMessages.html.twig',
      array('messages' => $messages, 'unreadMessages' => $unread));
    }
    /**
     * @Route("/message/read/{messageId}", name="_message_read")
     * @Template()
     */
    public function readAction($messageId)
    {

      $user = $this->get('security.context')->getToken()->getUser();
      $unread = $this->getUnreadMessages($user);

      $message = $this->getDoctrine()
                ->getRepository('SiteMessageBundle:Message')
                ->find($messageId);
      
      $status = $this->getDoctrine()
                  ->getRepository('SiteMessageBundle:MessageStatus')
                  ->findOneBy(array('name' => 'Read'));

      if ($message->getUser() != $user) {
        $this->get('session')->setFlash('notice', 
            'You do not have access to read this message!');
                 return new RedirectResponse($this->generateUrl('_secure'));
      }

      $message->setStatus($status);
      $em = $this->getDoctrine()->getEntityManager();
      $em->flush();

      $m_array['m'] = $message;
        $sender = $message->getSender();
        if (is_object($sender)) {
          $m_array['entity'] = $this->getUserEntity($sender);
        }


      return $this->render('SiteMessageBundle:Message:readMessage.html.twig',
        array('message' => $m_array,
        'unreadMessages' => $unread));

    }

    /**
     * @Route("/message/delete/{messageId}", name="_message_delete")
     * @Template()
     */
    public function deleteAction($messageId)
    {

      $user = $this->get('security.context')->getToken()->getUser();
      $unread = $this->getUnreadMessages($user);

      $message = $this->getDoctrine()
                ->getRepository('SiteMessageBundle:Message')
                ->find($messageId);
      
      $status = $this->getDoctrine()
                  ->getRepository('SiteMessageBundle:MessageStatus')
                  ->findOneBy(array('name' => 'Deleted'));

      if ($message->getUser() != $user) {
        $this->get('session')->setFlash('notice', 
            'You do not have access to delete this message!');
                 return new RedirectResponse($this->generateUrl('_secure'));
      }

      $message->setStatus($status);
      $em = $this->getDoctrine()->getEntityManager();
      $em->flush();

      return new RedirectResponse($this->generateUrl('_message_list')); 
    }

    /**
     * @Route("/message/restore/{messageId}", name="_message_restore")
     * @Template()
     */
    public function restoreAction($messageId)
    {

      $user = $this->get('security.context')->getToken()->getUser();
      $unread = $this->getUnreadMessages($user);

      $message = $this->getDoctrine()
                ->getRepository('SiteMessageBundle:Message')
                ->find($messageId);
      
      $status = $this->getDoctrine()
                  ->getRepository('SiteMessageBundle:MessageStatus')
                  ->findOneBy(array('name' => 'Read'));

      if ($message->getUser() != $user) {
        $this->get('session')->setFlash('notice', 
            'You do not have access to restore this message!');
                 return new RedirectResponse($this->generateUrl('_secure'));
      }

      $message->setStatus($status);
      $em = $this->getDoctrine()->getEntityManager();
      $em->flush();

      return new RedirectResponse($this->generateUrl('_message_list')); 
    }

    /**
     * @Route("/message/send/{targetId}", name="_message_send")
     * @Template()
     */
    public function sendAction(Request $request, $targetId)
    {

        $sender = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($sender);

        $target = $this->getDoctrine()
                ->getRepository('SiteUserBundle:User')
                ->find($targetId);

        if (!is_object($target))
        {
          $this->get('session')->setFlash('notice', 
            'You can not send a message to that user!');
            return new RedirectResponse($this->generateUrl('_secure'));          

        }

        if ($target->getId() == $sender->getId()) {
          $this->get('session')->setFlash('notice', 
            'You can not send a message to that user!');
            return new RedirectResponse($this->generateUrl('_secure'));          
        }
        // Need to add functionality to check if user can should be allowed
        // to send message to target.

        $isBlocked = 0;
        $blocked = $sender->getBlocked();

        $blocked_array = array();
        foreach ($blocked as $user) {
          $blocked_array[] = $user->getId();
        }
        if (in_array($target->getId(),$blocked_array)) {
          $isBlocked = 1;
        }

        if ($isBlocked or $sender->getRole() == 'ROLE_BENEFICIARY') {
          if ($target->getRole() == 'ROLE_BENEFICIARY') {
            $isBlocked = 1;

          } else if ($target->getRole() == 'ROLE_DONOR') {
            $isBlocked = 1;

            $em = $this->getDoctrine()->getEntityManager();
            $message_list_query = $em->createQuery(
              'SELECT m from SiteMessageBundle:Message m ' .
              'WHERE m.user=:userId and m.status not in (4)')
              ->setParameter('userId', $sender->getId());

            $ml_results = $message_list_query->getResult();

            foreach ($ml_results as $message) {

              if ($message->getSender()->getId() == $targetId) {
                $isBlocked = 0;
                break;
              }
            }
          } 
        } else if ($isBlocked or $sender->getRole() == 'ROLE_DONOR') {
          if ($target->getRole() == 'ROLE_BENEFICIARY') {
            $isBlocked = 1;

            $donor = $this->getDoctrine()
                  ->getRepository('SiteDonorBundle:Donor')
                  ->findByUser($sender);

            $searchResults = $this->getDoctrine()
                  ->getRepository('SiteSearchBundle:SearchResult')
                  ->findByDonor($donor);

            foreach ($searchResults as $searchResult) {
              if ($searchResult->getBeneficiary()->getUser()->getId() == $targetId) {
                $isBlocked = 0;
                break;
              }
            }
            $em = $this->getDoctrine()->getEntityManager();
            $message_list_query = $em->createQuery(
              'SELECT m from SiteMessageBundle:Message m ' .
              'WHERE m.user=:userId and m.status not in (4)')
              ->setParameter('userId', $sender->getId());

            $ml_results = $message_list_query->getResult();

            foreach ($ml_results as $message) {

              if ($message->getSender()->getId() == $targetId) {
                $isBlocked = 0;
                break;
              }
            }
          }
        }

        if ($isBlocked) {
          $this->get('session')->setFlash('notice', 
            'You can not send a message to that user!');
            return new RedirectResponse($this->generateUrl('_secure'));          
        }

        $form = $this->get('form.factory')->create(new MessageType());

        if ('POST' == $request->getMethod()) {

             $form->bindRequest($request);

             if ($form->isValid()) {
 
                 $status = $this->getDoctrine()
                     ->getRepository('SiteMessageBundle:MessageStatus')
                     ->findOneBy(array('name' => 'Unread'));

                 $message = new Message();
                 $message->setUser($target);
                 $message->setSender($sender);
                 $message->setStatus($status);
                 $message->setSubject($form->get('subject')->getData());
                 $message->setBody($form->get('body')->getData());
                 $message->setSent(new \DateTime(date("Y-m-d H:i:s")));

                 $em = $this->getDoctrine()->getEntityManager();
                 $em->persist($message);
                 $em->flush();

                 $this->get('session')->setFlash('notice', 
                     'Message sent!');
                 return new RedirectResponse($this->generateUrl('_secure'));

             }
        }

        $target_entity = $this->getUserEntity($target);

        return $this->render('SiteMessageBundle:Message:sendMessage.html.twig',
          array(
            'target' => $target,
            'entity' => $target_entity,
            'form' => $form->createView(),
            'unreadMessages' => $unread
          )
        );

    }

    /**
     * @Route("/public/send/{targetId}", name="_message_public_send")
     * @Template()
     */
    public function publicSendAction(Request $request, $targetId)
    {

        $target = $this->getDoctrine()
                     ->getRepository('SiteUserBundle:User')
                     ->findOneById($targetId);

        $user = $this->get('security.context')->getToken()->getUser();

        if (!(is_object($target) 
          and $target->getRole() == 'ROLE_VERIFICATION')) {
          $this->get('session')->setFlash('notice', 
            'You can not send a message to that user.');
          return new RedirectResponse($this->generateUrl('_index'));
        }

        $form = $this->get('form.factory')->create(new MessageType());

        if ('POST' == $request->getMethod()) {

             $form->bindRequest($request);

             if ($form->isValid()) {
 
                 $status = $this->getDoctrine()
                     ->getRepository('SiteMessageBundle:MessageStatus')
                     ->findOneBy(array('name' => 'Unread'));

                 $message = new Message();
                 $message->setUser($target);
                 $message->setStatus($status);
                 $message->setSubject($form->get('subject')->getData());
                 $message->setBody($form->get('body')->getData());
                 $message->setSent(new \DateTime(date("Y-m-d H:i:s")));

                if (is_object($user)) {
                    $message->setSender($user);
                }

                 $em = $this->getDoctrine()->getEntityManager();
                 $em->persist($message);
                 $em->flush();

                 $this->get('session')->setFlash('notice', 
                     'Message sent!');
                 return new RedirectResponse($this->generateUrl('_index'));

             }
        }
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteMessageBundle:Message:sendPublicMessage.html.twig',
                array(
                    'target' => $target,
                    'form' => $form->createView(),
                    'unreadMessages' => $unread
                ));
        } else {
            return $this->render('SiteMessageBundle:Message:sendPublicMessage.html.twig',
              array(
                'target' => $target,
                'form' => $form->createView(),
              )
            );
        }
    }

    /**
     * @Route("/public/send_admin", name="_message_public_send_admin")
     * @Template()
     */
    public function publicSendAdminAction(Request $request)
    {

        $target = $this->getDoctrine()
                     ->getRepository('SiteUserBundle:User')
                     ->findOneById(5);

        $user = $this->get('security.context')->getToken()->getUser();

        // Need to add functionality to check if user can should be allowed
        // to send message to target.

        $form = $this->get('form.factory')->create(new MessageType());

        if ('POST' == $request->getMethod()) {

             $form->bindRequest($request);

             if ($form->isValid()) {
 
                 $status = $this->getDoctrine()
                     ->getRepository('SiteMessageBundle:MessageStatus')
                     ->findOneBy(array('name' => 'Unread'));

                 $message = new Message();
                 $message->setUser($target);
                 $message->setStatus($status);
                 $message->setSubject($form->get('subject')->getData());
                 $message->setBody($form->get('body')->getData());
                 $message->setSent(new \DateTime(date("Y-m-d H:i:s")));

                if (is_object($user)) {
                    $message->setSender($user);
                }

                 $em = $this->getDoctrine()->getEntityManager();
                 $em->persist($message);
                 $em->flush();

                 $this->get('session')->setFlash('notice', 
                     'Message sent to Site Admin!');
                 return new RedirectResponse($this->generateUrl('_index'));

             }
        }
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteMessageBundle:Message:sendAdminMessage.html.twig',
                array(
                    'target' => $target,
                    'form' => $form->createView(),
                    'unreadMessages' => $unread
                ));
        } else {
            return $this->render('SiteMessageBundle:Message:sendAdminMessage.html.twig',
              array(
                'target' => $target,
                'form' => $form->createView(),
              )
            );
        }
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

    private function getUserEntity($user) {

      if ($user->getRole() == 'ROLE_BENEFICIARY') {
        return $this->getDoctrine()
                    ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                    ->findOneByUser($user);
      } else if ($user->getRole() == 'ROLE_DONOR') {
        return $this->getDoctrine()
                    ->getRepository('SiteDonorBundle:Donor')
                    ->findOneByUser($user);
      } else if ($user->getRole() == 'ROLE_VERIFICATION') {
        return $this->getDoctrine()
                    ->getRepository('SiteVerificationBundle:Verifier')
                    ->findOneByUser($user);
      } else  if ($user->getRole() == 'ROLE_ADMIN') {
        return $this->getDoctrine()
                    ->getRepository('SiteAdminBundle:Admin')
                    ->findOneByUser($user);
      } 
    }
}
