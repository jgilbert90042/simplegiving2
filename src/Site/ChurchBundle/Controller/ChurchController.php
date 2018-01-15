<?php

namespace Site\ChurchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;

class ChurchController extends Controller
{
    /**
     * @Route("/church/list", name="_church_list")
     * @Template()
     */
    public function listAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
        }

        $states = array();
        $repository = $this->getDoctrine()
            ->getRepository('SiteMiscBundle:State');

        $allStates = $repository->findAll();

        foreach ($allStates as $state) {
          $count = 0;
          $churches = $state->getChurches();
          foreach ($churches as $church) {
            if ($church->getVisible() && count($church->getVerifiers())) {
                $count++;
            }
          }
          if ($count) {
            array_push($states,$state);
          }
        }
        
        if (is_object($user)) {
            return $this->render('SiteChurchBundle:Church:listChurches.html.twig',
                array('states' => $states,
                    'unreadMessages' => $unread
                    ));
        } else {
            return $this->render('SiteChurchBundle:Church:listChurches.html.twig',
                array('states' => $states));
        }
    }

    /**
    * @Route("/church/view/{churchId}", name="_church_view")
    * @Template()
    */
    public function churchViewAction($churchId)
    {

        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
        }

        $repository = $this->getDoctrine()
            ->getRepository('SiteChurchBundle:Church');

        $church = $repository->findOneById($churchId);

        $verifiers = $church->getVerifiers();
   
        return $this->render('SiteChurchBundle:Church:churchView.html.twig',
            array('church' => $church,
                'verifier' => $verifiers[0],
                'unreadMessages' => $unread));
    }


    /**
     * @Route("/church/cities/", name="_church_cities")
     * @Template()
     */
    public function citiesAction()
    {
        $state = $_GET['stateAbbr']; 
        $cities =array();
        
        $repository = $this->getDoctrine()
            ->getRepository('SiteChurchBundle:Church');

        $churches = $repository->findByState($state);
   
        foreach ($churches as $church) {
            
            if ($church->getVisible() && count($church->getVerifiers()) > 0 ) {

                $city = $church->getCity();

                if (isset($cities[$city])) {
                    $cities[$city] += 1;
                } else {
                    $cities[$city] = 1;
                }
            }
        }

        ksort($cities);
        return new Response(json_encode($cities));

    }
    
    /**
    * @Route("/church/churchByCity/", name="_church_by_city")
    * @Template()
    */
    public function churchByCityAction()
    {

        $city = urldecode($_GET['city']);
        $churches = array();

        $repository = $this->getDoctrine()
            ->getRepository('SiteChurchBundle:Church');

        $church_results = $repository->findByCity($city);
   
        foreach ($church_results as $church) {
            $verifiers = $church->getVerifiers();

            if ($church->getVisible() && count($verifiers) > 0 ) {
                $churches[$church->getId()]['church'] = $church;
                $churches[$church->getId()]['verifier'] = $verifiers[0];

            }
        }

        return $this->render('SiteChurchBundle:Church:church_list.html.twig',
            array('churches' => $churches));
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
