<?php

namespace App\Controller\Admin;

use App\Entity\Secteur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
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

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ->add(Crud::PAGE_INDEX, Action::DETAIL);
            ->setPermissions([
                Action::BATCH_DELETE => 'ROLE_SUPER_ADMIN',
                Action::DELETE => 'ROLE_SUPER_ADMIN',
                Action::EDIT => 'ROLE_ADMIN',
                Action::DETAIL => 'ROLE_ADMIN',
                Action::NEW => 'ROLE_ADMIN',
                Action::INDEX => 'ROLE_ADMIN',
            ]);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des secteurs')
            ->setEntityLabelInSingular('Secteur')
            ->setEntityLabelInPlural('Secteurs')
            ->setSearchFields(['name', 'description']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name', 'Désignation'))
            ->add(TextFilter::new('description', 'Description'));
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            return [
                IdField::new('id')->onlyOnIndex(),
                ImageField::new('image', 'Image')
                    ->setBasePath('uploads/secteurs/')
                    ->setUploadDir('public/uploads/secteurs')
                    ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                    ->setHelp('Seulement .png et .jpg'),
                TextField::new('name', 'Désignation'),
                TextEditorField::new('description', 'Description'),
                BooleanField::new('isActive', 'Etat')->setDisabled(!$this->isGranted("ROLE_ADMIN")),

            ];
        }
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name', 'Désignation'),
            TextEditorField::new('description', 'Description'),
            ImageField::new('image', 'Image')
                ->setBasePath('uploads/secteurs/')
                ->setUploadDir('public/uploads/secteurs')
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setHelp('Seulement .png et .jpg')
                ->setRequired($pageName !== Crud::PAGE_EDIT),
            BooleanField::new('isActive', 'Etat')->setPermission("ROLE_ADMIN"),

        ];
    }
}
