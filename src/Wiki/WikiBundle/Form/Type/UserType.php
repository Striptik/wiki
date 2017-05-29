<?php

namespace Wiki\WikiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('status');
        $builder->add('email', EmailType::class);
        $builder->add('pseudonyme');
        $builder->add('password');
        $builder->add('role');
        $builder->add('createdAt', DateTimeType::class);
        $builder->add('lastConnectedAt', DateTimeType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Wiki\WikiBundle\Entity\User',
            'csrf_protection' => false
        ]);
    }
}