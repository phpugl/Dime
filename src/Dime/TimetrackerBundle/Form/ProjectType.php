<?php

namespace Dime\TimetrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('customer')
            ->add('name')
            ->add('startedAt', 'datetime', array('widget' => 'single_text', 'required' => false))
            ->add('stoppedAt', 'datetime', array('widget' => 'single_text', 'required' => false))
            ->add('deadline', 'datetime', array('widget' => 'single_text', 'required' => false))
            ->add('description')
            ->add('budgetPrice')
            ->add('fixedPrice')
            ->add('budgetTime')
            ->add('rate')
        ;
    }

    public function getName()
    {
        return 'dime_timetrackerbundle_projecttype';
    }
}
