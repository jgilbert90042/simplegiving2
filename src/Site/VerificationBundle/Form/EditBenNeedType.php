<?php

namespace Site\VerificationBundle\Form;

use Site\BeneficiaryBundle\Entity\Beneficiary;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EditBenNeedType extends AbstractType
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
        $builder->add('need', 'money', array('currency' => 'USD', 'label' => 'Amount'));
        $builder->add('needUrgency', 'entity', array(
          'class' => 'SiteBeneficiaryBundle:Urgency',
          'property' => 'urgency',
          'property_path' => false,
          'label' => 'Urgency'
        ));
        $builder->add('needDesc', 'textarea', array('label' => 'Description'));
    }

    public function getName ()
    {
        return 'EditNeed';
    }
}

