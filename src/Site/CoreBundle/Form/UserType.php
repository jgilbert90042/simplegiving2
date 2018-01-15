<?php

namespace Site\CoreBundle\Form;

use Site\UserBundle\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserType extends AbstractType
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

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('username', 'text');
        $builder->add('password', null, array('property_path' => false));
//        $builder->add('role', 'choice', array(
//	    'choices' => array(
//	        'ROLE_USER' => 'user',
//	        'ROLE_CMS_ADMIN' => 'cmsadmin', 
//	        'ROLE_ADMIN' => 'admin', 
//	        'ROLE_SUPER_ADMIN' => 'superadmin', 
//            )
//          )
//        );
        $builder->add('email', 'email');
    }

    public function getName ()
    {
        return 'user';
    }
}

