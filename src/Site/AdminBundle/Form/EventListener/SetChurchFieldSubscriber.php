<?php 

namespace Site\AdminBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;


class SetChurchFieldSubscriber implements EventSubscriberInterface
{
	private $factory;
 
    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::POST_SET_DATA => 'postSetData');
    }

    public function postSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. You're only concerned with when
        // setData is called with an actual Entity object in it (whether new
        // or fetched with Doctrine). This if statement lets you skip right
        // over the null condition.
        if (null === $data) {
            return;
        }

        // check if the product object is "new"
        if ($data->getId()) {
        	   // var_dump($data->getState());
        	   // exit;
//$form->add($this->factory->createNamed('denomination', 'entity', null, array(
//          'class' => 'SiteChurchBundle:ChurchDenomination',
//          'query_builder' => function(EntityRepository $er) {
//                    return $er->createQueryBuilder('d')->orderBy('d.name', 'ASC');
//                },
//          'property' => 'name',
//          'property_path' => false,
//        )));
	        $form->add($this->factory->createNamed('state', 'entity', null, array(
    	        'class' => 'SiteMiscBundle:State',
        	    'property' => 'name',
//           	'preferred_choices' => array(1)
            	'data' => $data->getState(),
        	)));
        } else {
	        $form->add($this->factory->createNamed('state', 'entity', null, array(
  	        'class' => 'SiteMiscBundle:State',
        	    'property' => 'name',
        	)));
//        	$form->add($this->factory->createNamed('state', 'text'));
    	}
    }
}

?>