<?php

namespace Site\AdminBundle\Form;

use Site\UserBundle\Entity\User;
use Site\VerificationBundle\Entity\Verifier;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddVerifierType extends AbstractType
{

    public function getDefaultOptions(array $options) 
    {
        return array (
            'data_class' => 'Site\UserBundle\Entity\User',
        );

    }

   public function newAction()
   {
       $user = new User;
       $form = $this->createForm(new UserType(), $user);
   }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text');
        $builder->add('password', null, array('property_path' => false));

        $builder->add('email', 'email');
        $builder->add('church', 'entity', array(
          'class' => 'SiteChurchBundle:Church',
          'property' => 'name',
          'property_path' => false,
          'mapped' => false
        ));
    }

    public function getName ()
    {
        return 'Verifier';
    }
}

