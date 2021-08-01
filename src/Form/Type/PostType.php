<?php

// src/Form/Type/PostType.php
namespace App\Form\Type;

use App\Entity\Post;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class PostType extends AbstractType 
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder 
                ->add('name', TextType::class)
                // ->add('content', TextType::class)
                ->add('content',TextareaType::class,['attr'=>['class' => 'tinytextarea']])
                ->add('author', TextType::class)
                ->add('created', DateType::class, ['attr'=> ['class' => 'setdate']])
                ->add('save', SubmitType::class, ['attr'=>['class' => 'btn btn-primary mt-3']])
                ;
        }
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Post::class,
            ]);
        }
    }