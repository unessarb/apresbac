<?php

namespace App\Controller\Admin;

use App\Entity\Partenaire;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class PartenaireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Partenaire::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermissions([
                Action::BATCH_DELETE => 'ROLE_SUPER_ADMIN',
                Action::DELETE => 'ROLE_SUPER_ADMIN',
                Action::EDIT => 'ROLE_ADMIN',
                Action::DETAIL => 'ROLE_ADMIN',
                Action::NEW => 'ROLE_ADMIN',
                Action::INDEX => 'ROLE_USER',
            ]);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setPageTitle('index', 'Gestion des paretenaires')
            ->setEntityLabelInSingular('Partenaire')
            ->setEntityLabelInPlural('Partenaires')
            ->setSearchFields(['name', 'link']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name', 'Raison sociale'))
            ->add(TextFilter::new('link', 'Lien'))
            ->add(DateTimeFilter::new('createdAt', 'Crée le'))
            ->add(DateTimeFilter::new('updatedAt', 'Modifiée le'));
    }


    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            yield IdField::new('id');
            yield ImageField::new('image', 'Image')->setBasePath('uploads/partenaires/');
            yield TextField::new('name', 'Raison sociale');
            yield TextField::new('link', 'Lien');
            yield DateTimeField::new('createdAt', 'Crée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss");
            yield DateTimeField::new('updatedAt', 'Modifiée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss");
        } else {
            yield TextField::new('name', 'Raison sociale')->setColumns(6);
            yield TextField::new('link', 'Lien')->setColumns(6);
            yield ImageField::new('image', 'Image')->setBasePath('uploads/partenaires/')->setUploadDir('public/uploads/partenaires')
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setColumns(6)
                ->setRequired($pageName !== Crud::PAGE_EDIT);
        }
    }
}
