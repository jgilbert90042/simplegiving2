<?php

namespace Site\DonorBundle\Form;

use Site\DonorBundle\Entity\Donor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{

    public function getDefaultOptions(array $options) 
    {
        return array (
            'data_class' => 'Site\DonorBundle\Entity\Donor',
        );

    }

   public function newAction()
   {
       $donor = new Donor;
       $form = $this->createForm(new ProfileType(), $donor);
   }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('first_name', 'text');
        $builder->add('last_name', 'text');
        $builder->add('phone', 'text');
        $builder->add('fax', 'text', array('required' => false));
        $builder->add('church', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'EditProfile';
    }
}

