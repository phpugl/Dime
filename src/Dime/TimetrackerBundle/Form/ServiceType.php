<?php

namespace Dime\TimetrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('rate')
        ;
    }

    public function getName()
    {
        return 'dime_timetrackerbundle_servicetype';
    }
}
