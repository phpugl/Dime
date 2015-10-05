<?php

namespace Dime\TimetrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Dime\TimetrackerBundle\Entity\User',
                'csrf_protection' => false
            )
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password')
            ->add('firstname')
            ->add('lastname')
            ->add('email')
        ;
    }

    public function getName()
    {
        return 'dime_timetrackerbundle_usertype';
    }
}
