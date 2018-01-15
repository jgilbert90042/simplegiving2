<?php

namespace Site\BeneficiaryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BeneficiaryController extends Controller
{

    /**
     * @Route("/beneficiary/", name="_beneficiary_index")
     * @Template()
     */
    public function indexAction()
    {

        $user = $this->get('security.context')->getToken()->getUser();

        $beneficiary = $this->getDoctrine()
          ->getRepository('SiteBeneficiaryBundle:Beneficiary')
          ->findOneBy(array('user' => $user->getId()));

        $request = $this->getRequest();
        $session = $request->getSession();
        $session->set('referrer', $request->getRequestUri());

        $unread = $this->getUnreadMessages($user);
        
        return $this->render('SiteBeneficiaryBundle:Beneficiary:index.html.twig',
            array('beneficiary' => $beneficiary,
              'user' => $user,
              'unreadMessages' => $unread));
    }

    /**
     * @Route("/beneficiary/view/{beneficiaryId}", name="_b_view")
     * @Template()
     */
    public function viewAction($beneficiaryId)
    {
        
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $beneficiary = $this->getDoctrine()
          ->getRepository('SiteBeneficiaryBundle:Beneficiary')
          ->find($beneficiaryId);

        return $this->render('SiteBeneficiaryBundle:Beneficiary:view.html.twig',
          array('beneficiary' => $beneficiary,
                'unreadMessages' => $unread));

    }

    /**
     * @Route("/beneficiary/VSProfile/", name="_b_view_vs")
     * @Template()
     */
    public function viewVSProfileAction()
    {

        $user = $this->get('security.context')->getToken()->getUser();

        $beneficiary = $this->getDoctrine()
          ->getRepository('SiteBeneficiaryBundle:Beneficiary')
          ->findOneBy(array('user' => $user->getId()));

        $unread = $this->getUnreadMessages($user);

        return $this->render('SiteBeneficiaryBundle:Beneficiary:viewVSProfile.html.twig',
          array('beneficiary' => $beneficiary,
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

}
