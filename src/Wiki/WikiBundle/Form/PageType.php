<?php

namespace Wiki\WikiBundle\Form;

use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('revisionId', IntegerType::class);
        //$builder->add('createdAt', DateTimeType::class);
        //$builder->add('updatedAt', DateTimeType::class);
        $builder->add('title', TextType::class);
        $builder->add('slug');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Wiki\WikiBundle\Entity\Page',
            'csrf_protection' => false,
        ]);
    }
}