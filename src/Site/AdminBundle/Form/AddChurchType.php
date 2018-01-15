<?php

namespace Site\AdminBundle\Form;

use Site\ChurchBundle\Entity\Church;
use Site\AdminBundle\Form\EventListener\SetChurchFieldSubscriber;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddChurchType extends AbstractType
{

//    public function getDefaultOptions(array $options) 
//    {
//        return array (
//            'data_class' => 'Site\ChurchBundle\Entity\Church',
//        );

//    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
      $resolver->setDefaults(array(
        'data_class' => 'Site\ChurchBundle\Entity\Church',
      ));
    }

   public function newAction()
   {
       $church = new Church;
       $form = $this->createForm(new AddChurchType(), $church);
   }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('denomination', 'entity', array(
          'class' => 'SiteChurchBundle:ChurchDenomination',
         'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
                },
          'property' => 'name',
          'empty_value' => 'Choose a Denomination'
        ));
        $builder->add('address', 'text');
        $builder->add('city', 'text');
        $builder->add('state', 'entity', array(
          'class' => 'SiteMiscBundle:State',
          'property' => 'name',
          'empty_value' => 'Choose a State',
        ));
        $builder->add('zip', 'text');
        $builder->add('phone', 'text');
        $builder->add('fax', 'text', array('required' => false) );
        $builder->add('email', 'text', array('required' => false) );
        $builder->add('website', 'text', array('required' => false) );
       
    }

    public function getName ()
    {
        return 'ChurchDenomination';
    }
}

