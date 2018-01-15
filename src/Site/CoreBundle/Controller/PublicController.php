<?php

namespace Site\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Kitpages\CmsBundle\Model\Paginator;

class PublicController extends Controller
{
    /**
     * @Route("/", name="_index")
     * @Template()
     */
    public function indexAction()
    {
        
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteCoreBundle:Default:index.html.twig',
                array('unreadMessages' => $unread));
        } else {
            return $this->render('SiteCoreBundle:Default:index.html.twig');
        }
    }

    /**
     * @Route("/about", name="_about")
     * @Template()
     */
    public function aboutAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteCoreBundle:Default:about.html.twig',
                array('unreadMessages' => $unread));
        } else {
            return $this->render('SiteCoreBundle:Default:about.html.twig');
        }
    }

    /**
     * @Route("/difference", name="_difference")
     * @Template()
     */
    public function differenceAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteCoreBundle:Default:difference.html.twig',
                array('unreadMessages' => $unread));
        } else {
            return $this->render('SiteCoreBundle:Default:difference.html.twig');
        }
    }

    /**
     * @Route("/business", name="_business")
     * @Template()
     */
    public function businessAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteCoreBundle:Default:business.html.twig',
                array('unreadMessages' => $unread));
        } else {
            return $this->render('SiteCoreBundle:Default:business.html.twig');
        }
    }

    /**
     * @Route("/vision", name="_vision")
     * @Template()
     */
    public function visionAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteCoreBundle:Default:vision.html.twig',
                array('unreadMessages' => $unread));
        } else {
            return $this->render('SiteCoreBundle:Default:vision.html.twig');
        }
    }

    /**
     * @Route("/public/donor", name="_public_donor")
     * @Template()
     */
    public function publicDonorsAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteCoreBundle:Default:donors.html.twig',
                array('unreadMessages' => $unread));
        } else {
            return $this->render('SiteCoreBundle:Default:donors.html.twig');
        }
    }

    /**
     * @Route("/public/beneficiary", name="_public_beneficiary")
     * @Template()
     */
    public function publicBeneficiariesAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteCoreBundle:Default:beneficiaries.html.twig',
                array('unreadMessages' => $unread));
        } else {
            return $this->render('SiteCoreBundle:Default:beneficiaries.html.twig');
        }
    }

    /**
     * @Route("/public/verification", name="_public_vs")
     * @Template()
     */
    public function publicVerificationAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteCoreBundle:Default:verifiers.html.twig',
                array('unreadMessages' => $unread));
        } else {
            return $this->render('SiteCoreBundle:Default:verifiers.html.twig');
        }
    }

    /**
     * @Route("/public/church", name="_public_church")
     * @Template()
     */
    public function publicChurchAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteCoreBundle:Default:churches.html.twig',
                array('unreadMessages' => $unread));
        } else {
            return $this->render('SiteCoreBundle:Default:churches.html.twig');
        }
    }


    /**
     * @Route("/contact", name="_contact")
     * @Template()
     */
    public function contactAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
            return $this->render('SiteCoreBundle:Default:contact.html.twig',
                array('unreadMessages' => $unread));
        } else {
            return $this->render('SiteCoreBundle:Default:contact.html.twig');
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
}
