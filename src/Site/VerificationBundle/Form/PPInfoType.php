<?php

namespace Site\VerificationBundle\Form;

use Site\VerificationBundle\Entity\Verifier;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PPInfoType extends AbstractType
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
       $form = $this->createForm(new PPInfoType(), $verifier);
   }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('PPEmail', 'text');
        $builder->add('PPButton', 'textarea');
    }

    public function getName()
    {
        return 'EditProfile';
    }
}

