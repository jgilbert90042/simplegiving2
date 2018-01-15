<?php

namespace Site\VerificationBundle\Form;

use Site\UserBundle\Entity\User;
use Site\BeneficiaryBundle\Entity\Beneficiary;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddBeneficiary1Type extends AbstractType
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
//        $builder->add('verifier', 'entity', array(
//          'class' => 'SiteVerificationBundle:Verifier',
//          'property' => 'user.username',
//          'property_path' => false
//        ));
    }

    public function getName ()
    {
        return 'user';
    }
}

