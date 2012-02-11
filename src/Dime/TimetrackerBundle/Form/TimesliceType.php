<?php

namespace Dime\TimetrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TimesliceType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('duration')
            ->add('startedAt', 'datetime', array('widget' => 'single_text', 'required' => false))
            ->add('stoppedAt', 'datetime', array('widget' => 'single_text', 'required' => false))
            ->add('activity')
        ;
    }

    public function getName()
    {
        return 'dime_timetrackerbundle_timeslicetype';
    }
}
