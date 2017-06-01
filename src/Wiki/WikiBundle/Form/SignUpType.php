<?php

namespace Wiki\WikiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Handle the comments info, Add the type class
        //

        $builder->add('email');
        $builder->add('pseudo');
        $builder->add('username');

        $builder->add('password'); // To get the password and the verification

        $builder->add('status'); // Set if online or not
        $builder->add('role'); // To define by default

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Wiki\WikiBundle\Entity\User',
            'csrf_protection' => false,
        ]);
    }
}