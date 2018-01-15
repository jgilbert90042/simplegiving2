<?php

namespace Site\FundingBundle\Controller;

use Site\FundingBundle\Form\DonateType;
use Site\FundingBundle\Entity\Funding;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FundingController extends Controller
{
    /**
     * @Route("/funding/donate/{targetId}", name="_funds_donate")
     * @Template()
     */
    public function donateAction(Request $request, $targetId)
    {

        $sender = $this->get('security.context')->getToken()->getUser();
        $target = $this->getDoctrine()
                ->getRepository('SiteUserBundle:User')
                ->find($targetId);

        $form = $this->get('form.factory')->create(new DonateType());

        if ('POST' == $request->getMethod()) {
             $form->bindRequest($request);

             if ($form->isValid()) {
                 return new Response('<h2>Form Validated</h2>');
             }
        } 

        return $this->render('SiteFundingBundle:Funding:donateFunds.html.twig',
          array(
            'target' => $target,
            'form' => $form->createView(),
          )
        );

    }

    /**
     * @Route("/funding/list/{targetId}", name="_funds_list")
     * @Template()
     */
    public function listAction($targetId)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $target = $this->getDoctrine()
                ->getRepository('SiteUserBundle:User')
                ->find($targetId);

        $not_allowed = 0;

        if ($user->getRole() == 'ROLE_BENEFICIARY') {
          if ($target->getId() != $user->getId()) {
            $not_allowed = 1;
          }
        } else if ($user->getRole() == 'ROLE_DONOR') {
          if ($target->getId() != $user->getId()) {
            $not_allowed = 1;
          }
        } else if ($user->getRole() == 'ROLE_VERIFICATION') {
          if ($target->getId() != $user->getId()) {

            if ($target->getRole() == 'ROLE_BENEFICIARY') {
              $beneficiary = $this->getDoctrine()
                ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                ->findOneByUser($target);

              if ($beneficiary->getVerifier()->getUser()->getId() != $user->getId()) {
                $not_allowed = 1;
              }
            } else {
              $not_allowed = 1;
            }
          }
        }
    
        if ($not_allowed) {
          $this->get('session')->setFlash('notice',
            'You do not have permission to review donations for that target.');
            return new RedirectResponse(
              $this->generateUrl('_secure'));
        }

        if ($target->getRole() == 'ROLE_BENEFICIARY') {

            $beneficiary = $this->getDoctrine()
                ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                ->findOneByUser($target);

            $donations = $this->getDoctrine()
                ->getRepository('SiteFundingBundle:Funding')
                ->findByBeneficiary($beneficiary);

        } else if ($target->getRole() == 'ROLE_VERIFICATION') {

            $verifier = $this->getDoctrine()
                ->getRepository('SiteVerificationBundle:Verifier')
                ->findOneByUser($target);

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
            $donations = $allDonations;

        } else if ($target->getRole() == 'ROLE_DONOR') {

            $donor = $this->getDoctrine()
                ->getRepository('SiteDonorBundle:Donor')
                ->findOneByUser($target);

            $donations = $this->getDoctrine()
                ->getRepository('SiteFundingBundle:Funding')
                ->findByDonor($donor);

        } else if ($target->getRole() == 'ROLE_ADMIN') {
            $donations = $this->getDoctrine()
                ->getRepository('SiteFundingBundle:Funding')
                ->findAll();
        }
        
        $request = $this->getRequest();
        $session = $request->getSession();
        $returnUrl = $session->get('referrer');

        return $this->render(
        'SiteFundingBundle:Funding:listDonations.html.twig',
        array('donations' => $donations,
        'unreadMessages' => $unread,
        'returnUrl' => $returnUrl,
         'user' => $user));

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
        
