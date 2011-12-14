<?php

namespace Dime\TimetrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('customer')
            ->add('name')
            ->add('startedAt')
            ->add('stoppedAt')
            ->add('deadline')
            ->add('description')
            ->add('budgetPrice')
            ->add('fixedPrice')
            ->add('budgetTime')
            ->add('rate')
        ;
    }

    public function getName()
    {
        return 'dime_timetrackerbundle_servicetype';
    }
}
