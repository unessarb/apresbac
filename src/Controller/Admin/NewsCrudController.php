<?php

namespace App\Controller\Admin;

use App\Entity\News;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class NewsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return News::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('title', 'Désignation'))
            ->add(DateTimeFilter::new('createdAt', 'Crée le'))
            ->add(DateTimeFilter::new('updatedAt', 'Modifiée le'))
            ->add('etablissement');
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            yield IdField::new('id');
            yield ImageField::new('image', 'Image')->setBasePath('uploads/news/');
            yield TextField::new('title', 'Désignation');
            yield TextEditorField::new('content', 'Contenu');
            yield TextField::new('etablissement');
            yield DateTimeField::new('createdAt', 'Crée le');
            yield DateTimeField::new('updatedAt', 'Modifiée le');
            yield DateTimeField::new('publishAt', 'Publiée le');
            yield BooleanField::new('isActive', 'Etat');
            yield BooleanField::new('isPublic', 'Grand public');
        } else {
            yield TextField::new('title', 'Désignation')->setColumns(6);
            yield AssociationField::new('etablissement')->setColumns(6);
            yield UrlField::new('link', 'Lien')->setColumns(6);
            yield ImageField::new('image', 'Image')
                ->setBasePath('uploads/news/')
                ->setUploadDir('public/uploads/news')
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setColumns(6);
            yield TextEditorField::new('content', 'Contenu')->setColumns(12);
            yield DateTimeField::new('publishedAt', 'Publiée le')->setColumns(12);
            yield BooleanField::new('isActive', 'Etat')->setColumns(12);
            yield BooleanField::new('isPublic', 'Grand public')->setColumns(12);
        }
    }
}
