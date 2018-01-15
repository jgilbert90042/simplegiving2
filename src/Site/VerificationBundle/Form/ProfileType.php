<?php

namespace Site\VerificationBundle\Form;

use Site\VerificationBundle\Entity\Verifier;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{

    public function getDefaultOptions(array $options) 
    {
        return array (
            'data_class' => 'Site\VerificationBundle\Entity\Verifier',
        );

    }

   public function newAction()
   {
       $verifier = new Verifier;
       $form = $this->createForm(new ProfileType(), $verifier);
   }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('first_name', 'text');
        $builder->add('last_name', 'text');
        $builder->add('position', 'text');
        $builder->add('phone', 'text');
        $builder->add('fax', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'EditProfile';
    }
}

