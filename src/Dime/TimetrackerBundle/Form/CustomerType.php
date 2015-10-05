<?php

namespace Dime\TimetrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Dime\TimetrackerBundle\Entity\User;

class CustomerType extends AbstractType
{
    /**
     * @var ObjectManager
     */
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
                'data_class' => 'Dime\TimetrackerBundle\Entity\Customer',
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
            ->add($builder->create('tags', 'text')->addModelTransformer($transformer))
        ;
    }

    public function getName()
    {
        return 'dime_timetrackerbundle_customertype';
    }
}
