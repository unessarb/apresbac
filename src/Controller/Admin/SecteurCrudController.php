<?php

namespace App\Controller\Admin;

use App\Entity\Secteur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\Validator\Constraints\File;

class SecteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Secteur::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'description']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name', 'Désignation'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name', 'Désignation'),
            TextEditorField::new('description', 'Description'),
            ImageField::new('image', 'Image')
                ->setBasePath('uploads/secteurs/')
                ->setUploadDir('public/uploads/secteurs')
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setHelp('Seulement .png et .jpg'),
        ];
    }
}
