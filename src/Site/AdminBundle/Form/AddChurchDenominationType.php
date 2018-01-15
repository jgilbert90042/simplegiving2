<?php

namespace Site\AdminBundle\Form;

use Site\ChurchBundle\Entity\ChurchDenomination;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddChurchDenominationType extends AbstractType
{

    public function getDefaultOptions(array $options) 
    {
        return array (
            'data_class' => 'Site\ChurchBundle\Entity\ChurchDenomination',
        );

    }

   public function newAction()
   {
       $denomination = new ChurchDenomination;
       $form = $this->createForm(new AddChurchDenominationType(), $denomination);
   }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
    }

    public function getName ()
    {
        return 'Denomination';
    }
}

