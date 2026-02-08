<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre de l\'article'
            ])
            ->add('content', null, [
                'label' => 'Contenu',
                'attr' => ['rows' => 10]
            ])

            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de couverture (Optionnel)',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}