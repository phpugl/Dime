<?php

namespace Dime\TimetrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Dime\TimetrackerBundle\Entity\ServiceRepository;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('duration', 'integer')
            ->add('rate')
            ->add('rate_reference')  // TODO: add constraints
            ->add('started_at')
            ->add('stopped_at')
            ->add('service', 'entity', array( 
                'class'         => 'Dime\\TimetrackerBundle\\Entity\\Service', 
                'multiple'      => false, 
                'expanded'      => false, 
                'required'      => false, 
            ))
            ->add('customer', 'entity', array( 
                'class'         => 'Dime\\TimetrackerBundle\\Entity\\Customer', 
                'multiple'      => false, 
                'expanded'      => false, 
                'required'      => false, 
            ))
            ->add('project', 'entity', array( 
                'class'         => 'Dime\\TimetrackerBundle\\Entity\\Project', 
                'multiple'      => false, 
                'expanded'      => false, 
                'required'      => false, 
            ))
        ;
    }

    public function getName()
    {
        return 'dime_timetrackerbundle_activitytype';
    }
}
