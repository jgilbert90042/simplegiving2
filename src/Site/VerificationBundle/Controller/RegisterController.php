<?php

namespace Site\VerificationBundle\Controller;

use Site\UserBundle\Entity\User;
use Site\VerificationBundle\Entity\Verifier;
use Site\VerificationBundle\Form\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
#use Symfony\Component\Security\Core\SecurityContext;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RegisterController extends Controller
{

    /**
     * @Route("/verification/register/", name="_verification_register")
     */
    public function indexAction()
    {
        return new RedirectResponse($this->generateUrl('_verification_register_create'));
    }

    /**
     * @Route("/verification/register/create", name="_verification_register_create")
     */
    public function createAction(Request $request)
    {

        $form = $this->get('form.factory')->create(new UserType());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {

                $username = $form->get('username')->getData();
//                $role = $form->get('role')->getData();
                $role = 'ROLE_VERIFICATION';

                $email = $form->get('email')->getData();

                $pass_string=$form->get('password')->getData();
                $factory = $this->get('security.encoder_factory');

                $user = new User();
                $verifier = new Verifier();

                $user->setUsername($username);

                $encoder = $factory->getEncoder($user);
                $password = 
                   $encoder->encodePassword($pass_string, $user->getSalt());
                $user->setPassword($password);

                $user->setEmail($email);
                $user->setRole($role);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($user);

               $verifier->setUser($user);
                $verifier->setDonateLink('http://www.paypal.com/');
                $em->persist($verifier);
                $em->flush();
                $this->get('session')->setFlash('notice', $user->getUsername() 
                  . ' has been registered!');
                return new RedirectResponse($this->generateUrl('_index'));
            }
        }

//        $em = $this->getDoctrine()->getEntityManager();
//        $verifier_list_query = $em->createQuery(
//          'SELECT vs from SiteVerificationBundle:Verifier vs ');
//        $vl_results = $verifier_list_query->getResults();
//        $verifiers = array();
//        foreach ($vl_results as $vlr) {
//          array_push($verifiers,$vlr);
//        }

        return $this->render('SiteVerificationBundle:Register:newVerifier.html.twig', array(
	    'form' => $form->createView()
          )
        );

    }
}

?>
