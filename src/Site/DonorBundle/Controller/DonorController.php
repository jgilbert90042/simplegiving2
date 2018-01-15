<?php

namespace Site\DonorBundle\Controller;

use Site\DonorBundle\Form\ProfileType;
use Site\SearchBundle\Entity\SearchResult;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DonorController extends Controller
{
    /**
     * @Route("/donor/", name="_donor_index")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);

        $donor = $this->getDonor($user);
        if (is_null($donor->getFirstName())) {
          return new RedirectResponse($this->generateUrl('_d_edit'));
        }
        return $this->render('SiteDonorBundle:Donor:index.html.twig',
          array('unreadMessages' => $unread));
    }

    /**
     * @Route("/donor/search_results/", name="_d_show_searchresults")
     * @Template()
     */
    public function showSearchResultsAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $donor = $this->getDonor($user);

        $searchResults = $this->getDoctrine()
                ->getRepository('SiteSearchBundle:SearchResult')
                ->findBy(array('donor' => $donor));

        if (count($searchResults) < $donor->getSearchCap())
        {
            $to_add = $donor->getSearchCap() - count($searchResults);

            $sr_ids = array();
            foreach ($searchResults as $sr) {
                array_push($sr_ids, $sr->getBeneficiary()->getId());
            }

            $conn = $this->get('database_connection'); 

            $query = 'SELECT id FROM Beneficiary where ';
            if (count($sr_ids)) {
              $query .= 'id NOT IN (' . implode(', ', $sr_ids) .') and ';
            }
            $query .= 'searchable = 1 order by rand() limit ' . $to_add;
            $results = $conn->fetchAll($query);

            $em = $this->getDoctrine()->getEntityManager();

            $blocked = $donor->getUser()->getBlocked();

            $blocked_array = array();
            foreach ($blocked as $user) {
              $blocked_array[] = $user->getId();
            }

            $count = 0;
            
            foreach ($results as $result) {
              $beneficiary = $this->getDoctrine()
                ->getRepository('SiteBeneficiaryBundle:Beneficiary')
                ->findOneById($result['id']);
              if (!is_object($beneficiary)) {
                continue;
              }
              if (in_array($beneficiary->getUser()->getId(),$blocked_array)) {
                continue;
              }

              if ($beneficiary->getVerifier()->getActivated() == 0)
              {
                continue;
              }  

              $searchResult = new SearchResult();
              $searchResult->setDonor($donor);
              $searchResult->setBeneficiary($beneficiary);
              $searchResult->setAdded(new \DateTime(date("Y-m-d H:i:s")));
              $em->persist($searchResult);
              $em->flush();
            
              $count++;
            }

            if (count($results)) {
                if ($count > 0) {
                  $this->get('session')->setFlash('notice', $count 
                    . ' search results were added.');
                }
            }

            $searchResults = $this->getDoctrine()
              ->getRepository('SiteSearchBundle:SearchResult')
              ->findBy(array('donor' => $donor));

        }
        
        return $this->render(
            'SiteDonorBundle:Donor:viewSearchResults.html.twig',
            array('searchResults' => $searchResults,
              'unreadMessages' => $unread));

    }

    /**
    * @Route("/donor/viewDonations", name="_d_list_donations")
    * @Template()
    */
    public function listDonationsAction()
    {

        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $donor = $this->getDonor($user);

        $donations = $this->getDoctrine()
          ->getRepository('SiteFundingBundle:Funding')
          ->findByDonor($donor);

        return $this->render('SiteDonorBundle:Donor:viewDonations.html.twig',
            array('donations' => $donations,
              'unreadMessages' => $unread));

    }

    /**
     * @Route("/donor/editProfile/", name="_d_edit")
     * @Template()
     */
    public function editProfileAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $unread = $this->getUnreadMessages($user);
        $donor = $this->getDonor($user);

        $form = $this->get('form.factory')->create(new ProfileType());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {
           
                $donor->setFirstName(
                  $form->get('first_name')->getData());
                $donor->setLastName(
                  $form->get('last_name')->getData());
                $donor->setPhone(
                  $form->get('phone')->getData());
                $donor->setFax(
                  $form->get('fax')->getData());
                $donor->setChurch(
                  $form->get('church')->getData());

                 $em = $this->getDoctrine()->getEntityManager();
                 $em->persist($donor);
                 $em->flush();

                return new RedirectResponse(
                  $this->generateUrl('_donor_index'));

            }
        }

        $form->setData($donor);

        return $this->
            render('SiteDonorBundle:Donor:' .
                'editProfile.html.twig',
                array('form' => $form->createView(),
                  'unreadMessages' => $unread));


    }



    public function getDonor($user)
    {
        return $this->getDoctrine()
                ->getRepository('SiteDonorBundle:Donor')
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
