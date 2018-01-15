<?php

namespace Site\MessageBundle\Form;

use Site\MessageBundle\Entity\Message;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MessageType extends AbstractType
{

    public function getDefaultOptions(array $options)
    {
        return array (
            'data_class' => 'Site\MessageBundle\Entity\Message',
        );

    }

   public function newAction()
   {
       $message = new Message;
       $form = $this->createForm(new MessageType(), $message);
   }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

       $builder->add('subject', 'text');
       $builder->add('body', 'textarea');

    }

    public function getName ()
    {
        return 'message';
    }

}
