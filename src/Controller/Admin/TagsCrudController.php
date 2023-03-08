<?php

namespace App\Controller\Admin;

use App\Entity\Tags;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TagsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tags::class;
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
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setPageTitle('index', 'Gestion des tags')
            ->setEntityLabelInSingular('Tag')
            ->setEntityLabelInPlural('Tags')
            ->setSearchFields(['name']);
    }


    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            return [
                IdField::new('id')->onlyOnIndex(),
                TextField::new('name', 'Tag'),
                DateTimeField::new('createdAt', 'Crée le')->onlyOnIndex(),
                DateTimeField::new('updatedAt', 'Modifiée le')->onlyOnIndex(),
                BooleanField::new('isActive', 'Etat')->setDisabled(!$this->isGranted("ROLE_ADMIN"))
            ];
        }
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name', 'Tag'),
            DateTimeField::new('createdAt', 'Crée le')->onlyOnIndex(),
            DateTimeField::new('updatedAt', 'Modifiée le')->onlyOnIndex(),
            BooleanField::new('isActive', 'Etat')->setPermission("ROLE_ADMIN"),
        ];
    }
}
