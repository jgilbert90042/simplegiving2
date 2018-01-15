<?php

namespace Site\VerificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class VerificationFundingController extends Controller
{

    /**
     * @Route("/verification/viewDonations", name="_vs_list_donations")
     * @Template()
     */
    public function listDonationsAction()
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $verifier = $this->getVerifier($user);

        $beneficiaries = $this->getDoctrine()
          ->getRepository('SiteBeneficiaryBundle:Beneficiary')
          ->findByVerifier($verifier);

        $allDonations = array();
        foreach ($beneficiaries as $beneficiary)
        {
        	$donations = $this->getDoctrine()
        		->getRepository('SiteFundingBundle:Funding')
        		->findByBeneficiary($beneficiary);

            foreach ($donations as $donation)
            {
            	$allDonations[] = $donation;
            }
        		
        }

        return $this->render(
	    'SiteVerificationBundle:Funding:listDonations.html.twig',
	    array('donations' => $allDonations,
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