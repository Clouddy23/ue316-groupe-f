<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Title'),

            SlugField::new('slug')->setTargetFieldName('title'),

            TextareaField::new('content', 'Content'),
            DateTimeField::new('publishedAt', 'Published'),
            AssociationField::new('author', 'Author')->onlyOnForms()->hideOnForm(),
            // Enlever hideOnForm si on veut pouvoir changer l'auteur d'un post
            TextField::new('author', 'Author')->hideOnForm(),
        ];
    }
}
