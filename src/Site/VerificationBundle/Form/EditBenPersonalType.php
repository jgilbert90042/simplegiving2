<?php

namespace Site\VerificationBundle\Form;

use Site\BeneficiaryBundle\Entity\Beneficiary;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EditBenPersonalType extends AbstractType
{

    public function getDefaultOptions(array $options) 
    {
        return array (
            'data_class' => 'Site\BeneficiaryBundle\Entity\Beneficiary',
        );

    }

   public function newAction()
   {
       $beneficiary = new Beneficiary;
       $form = $this->createForm(new EditBenPersonalType(), $beneficiary);
   }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('first_name', 'text');
        $builder->add('last_name', 'text');
        $builder->add('ssn', 'text');
        $builder->add('address', 'text');
        $builder->add('city', 'text');
        $builder->add('state', 'entity', array(
          'class' => 'SiteMiscBundle:State',
          'property' => 'name',
 //         'property_path' => false,
          'empty_value' => 'Choose a state'
        ));
        $builder->add('phone', 'text');
        $builder->add('fax', 'text', array('required' => false));
        $builder->add('marital_status', 'entity', array(
          'class' => 'SiteMiscBundle:MaritalStatus',
          'property' => 'status',
 //         'property_path' => false,
          'empty_value' => 'Choose a status',
        ));
        $builder->add('dependants', 'text');
    }

    public function getName ()
    {
        return 'EditPersonal';
    }
}

