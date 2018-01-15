<?php

namespace Site\FundingBundle\Form;

use Site\FundingBundle\Entity\Funding;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DonateType extends AbstractType
{

    public function getDefaultOptions(array $options)
    {
        return array (
            'data_class' => 'Site\FundingBundle\Entity\Funding',
        );

    }

   public function newAction()
   {
       $funding = new Funding;
       $form = $this->createForm(new FundingType(), $funding);
   }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder->add('amount', 'money', array('currency' => 'USD'));
//       $builder->add('donateMethod', null, array('mapped' => false));
//       $builder->add('method', 'choice', array(
//              'mapped' => false,
//		'choices' => array ('paypal' => 'Paypal', 'check' => 'Check'),
//       ));
    }

    public function getName ()
    {
        return 'funding';
    }

}

