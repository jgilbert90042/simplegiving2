<?php

namespace Site\DonorBundle\Controller;

use Site\UserBundle\Entity\User;
use Site\DonorBundle\Entity\Donor;
use Site\DonorBundle\Form\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RegisterController extends Controller
{

    /**
     * @Route("/donor/register/", name="_donor_register")
     */
    public function indexAction()
    {
        return new RedirectResponse($this->generateUrl('_donor_register_create'));
    }

    /**
     * @Route("/donor/register/create", name="_donor_register_create")
     */
    public function createAction(Request $request)
    {

        $form = $this->get('form.factory')->create(new UserType());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {

                $username = $form->get('username')->getData();
//                $role = $form->get('role')->getData();
                $role = 'ROLE_DONOR';
                $ip = $_SERVER['REMOTE_ADDR'];

                $email = $form->get('email')->getData();

                $pass_string=$form->get('password')->getData();
                $factory = $this->get('security.encoder_factory');

                $user = new User();
                $donor = new Donor();

                $user->setUsername($username);

                $encoder = $factory->getEncoder($user);
                $password = 
                   $encoder->encodePassword($pass_string, $user->getSalt());
                $user->setPassword($password);

                $user->setEmail($email);
                $user->setRole($role);
                $user->setCreatedIp($ip);
                $user->setCreated(new \DateTime(date("Y-m-d H:i:s")));


                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($user);

                $donor->setUser($user);
                $em->persist($donor);
                $em->flush();
                $this->get('session')->setFlash('notice', $username 
                  . ' has been registered!');
                return new RedirectResponse($this->generateUrl('_index'));
            }
        }

        return $this->render('SiteDonorBundle:Register:newDonor.html.twig', array(
	    'form' => $form->createView()
          )
        );

    }
}

?>
