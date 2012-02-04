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
            ->add('duration')
            ->add('startedAt', 'datetime', array('required' => false, 'widget' => 'single_text', 'with_seconds' => true))
            ->add('stoppedAt', 'datetime', array('required' => false, 'widget' => 'single_text', 'with_seconds' => true))
            ->add('rate')
            ->add('rateReference')  // TODO: add constraints
            ->add('service')
            ->add('customer')
            ->add('project')
        ;
    }

    public function getName()
    {
        return 'dime_timetrackerbundle_activitytype';
    }
}
