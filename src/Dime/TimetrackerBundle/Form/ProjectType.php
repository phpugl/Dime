<?php

namespace Dime\TimetrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Dime\TimetrackerBundle\Entity\User;

class ProjectType extends AbstractType
{
    protected $em;

    /**
     * @var User
     */
    protected $user;

    public function __construct($em, User $user)
    {
        $this->em = $em;
        $this->user = $user;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Dime\TimetrackerBundle\Entity\Project',
                'csrf_protection' => false
            )
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TagTransformer($this->em, $this->user);

        $builder
            ->add('name')
            ->add('alias')
            ->add('customer')
            ->add('startedAt', 'datetime', array('required' => false, 'widget' => 'single_text', 'with_seconds' => true))
            ->add('stoppedAt', 'datetime', array('required' => false, 'widget' => 'single_text', 'with_seconds' => true))
            ->add('deadline', 'datetime', array('required' => false, 'widget' => 'single_text', 'with_seconds' => true))
            ->add('description')
            ->add('budgetPrice')
            ->add('fixedPrice')
            ->add('budgetTime')
            ->add('rate')
            ->add($builder->create('tags', 'text')->addModelTransformer($transformer))
        ;
    }

    public function getName()
    {
        return 'dime_timetrackerbundle_projecttype';
    }
}
