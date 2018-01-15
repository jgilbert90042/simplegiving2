<?php

namespace Site\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class SecuredController extends Controller
{

    /**
     * @Route("/secure/", name="_secure")
     */
    public function indexAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
        	return new RedirectResponse(
                	$this->generateUrl('_admin_index'));
        } elseif ($this->get('security.context')
			->isGranted('ROLE_BENEFICIARY')) {
        	return new RedirectResponse(
                	$this->generateUrl('_beneficiary_index'));
        } elseif ($this->get('security.context')
			->isGranted('ROLE_VERIFICATION')) {
        	return new RedirectResponse(
                	$this->generateUrl('_verification_index'));
        } elseif ($this->get('security.context')
			->isGranted('ROLE_DONOR')) {
        	return new RedirectResponse(
                	$this->generateUrl('_donor_index'));
        } 
        return new Response('<h2>Logged in</h2>');
    }

    /**
     * @Route("/secure/TandC", name="_secure_tandc")
     */
    public function tandcAction()
    {

        return $this->render('SiteCoreBundle:Secured:TandC.html.twig');

    }

    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        $user = $this->get('security.context')->getToken()->getUser();

        if (is_object($user)) {
            $unread = $this->getUnreadMessages($user);
        }
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        if (is_object($user)) {
            return $this->render('SiteCoreBundle:Secured:login.html.twig', array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                'unreadMessages' => $unread
            ));
        } else {
            return $this->render('SiteCoreBundle:Secured:login.html.twig', array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            ));
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

?>
