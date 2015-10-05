<?php

namespace Dime\TimetrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Dime\TimetrackerBundle\Entity\Setting',
                'csrf_protection' => false
            )
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('namespace')
            ->add('name')
            ->add('value');
    }

    public function getName()
    {
        return 'dime_timetrackerbundle_settingtype';
    }
}
