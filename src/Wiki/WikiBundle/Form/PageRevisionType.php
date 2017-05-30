<?php

namespace Wiki\WikiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageRevisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('status');
        $builder->add('content', TextType::class);
        $builder->add('updatedBy', IntegerType::class);
        $builder->add('createdAt', DateTimeType::class);
        $builder->add('updatedAt', DateTimeType::class);
        $builder->add('revisionVersion', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Wiki\WikiBundle\Entity\PageRevision',
            'csrf_protection' => false,
        ]);
    }
}