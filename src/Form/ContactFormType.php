<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Your Name',
                'attr' => ['class' => 'form-control', 'placeholder' => 'John Doe'],
                'label_attr' => ['class' => 'form-label fw-bold']
            ])
            ->add('email', null, [
                'label' => 'Your Email',
                'attr' => ['class' => 'form-control', 'placeholder' => 'john@example.com'],
                'label_attr' => ['class' => 'form-label fw-bold mt-3']
            ])
            ->add('subject', null, [
                'label' => 'Subject',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Subject of your message'],
                'label_attr' => ['class' => 'form-label fw-bold mt-3']
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => ['class' => 'form-control', 'rows' => 6, 'placeholder' => 'How can we help you?'],
                'label_attr' => ['class' => 'form-label fw-bold mt-3']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
