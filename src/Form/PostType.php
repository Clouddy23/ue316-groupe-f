<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Title',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter the title here...'],
                'label_attr' => ['class' => 'form-label fw-bold']
            ])
            ->add('content', null, [
                'label' => 'Content',
                'attr' => ['class' => 'form-control', 'rows' => 10, 'placeholder' => 'Write your content here....'],
                'label_attr' => ['class' => 'form-label fw-bold mt-3']
            ])
            ->add('publishedAt', null, [
                'label' => 'Publication date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label fw-bold mt-3']
            ])
            // Retiré pour que l'auteur soit automatiquement l'utilisateur connecté
            // ->add('author', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
