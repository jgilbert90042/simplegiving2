<?php

namespace Site\BeneficiaryBundle\Controller;

use Site\UserBundle\Entity\User;
use Site\BeneficiaryBundle\Entity\Beneficiary;
use Site\BeneficiaryBundle\Entity\Verifier;
use Site\BeneficiaryBundle\Form\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RegisterController extends Controller
{

    /**
     * @Route("/beneficiary/register/", name="_beneficiary_register")
     */
    public function indexAction()
    {
        return new RedirectResponse($this->generateUrl('_beneficiary_register_create'));
    }

    /**
     * @Route("/beneficiary/register/create", name="_beneficiary_register_create")
     */
    public function createAction(Request $request)
    {

        $form = $this->get('form.factory')->create(new UserType());

        if ('POST' == $request->getMethod()) {

            $form->bindRequest($request);

            if ($form->isValid()) {

                $username = $form->get('username')->getData();
//                $role = $form->get('role')->getData();
                $role = 'ROLE_BENEFICIARY';

                $email = $form->get('email')->getData();

                $pass_string=$form->get('password')->getData();
                $factory = $this->get('security.encoder_factory');

                $user = new User();
                $beneficiary = new Beneficiary();

                $user->setUsername($username);

                $encoder = $factory->getEncoder($user);
                $password = 
                   $encoder->encodePassword($pass_string, $user->getSalt());
                $user->setPassword($password);

                $user->setEmail($email);
                $user->setRole($role);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($user);

                $beneficiary->setUser($user);

                $beneficiary->setVerifier($form->get('verifier')->getData());
                $em->persist($beneficiary);
                $em->flush();
                $this->get('session')->setFlash('notice', $username 
                  . ' has been registered!');
                return new RedirectResponse($this->generateUrl('_index'));
            }
        }

        return $this->render('SiteBeneficiaryBundle:Register:newBeneficiary.html.twig', array(
	    'form' => $form->createView()
          )
        );

    }
}

?>
