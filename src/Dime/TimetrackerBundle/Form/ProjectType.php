<?php

namespace Dime\TimetrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('customer', 'entity', array( 
                'class'         => 'Dime\\TimetrackerBundle\\Entity\\Customer', 
                'multiple'      => false, 
                'expanded'      => false, 
                'required'      => false, 
            ))
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
        return 'dime_timetrackerbundle_projecttype';
    }
}
