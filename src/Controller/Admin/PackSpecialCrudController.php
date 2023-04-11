<?php

namespace App\Controller\Admin;

use App\Entity\PackSpecial;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class PackSpecialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PackSpecial::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->setPermissions([
                Action::BATCH_DELETE => 'ROLE_SUPER_ADMIN',
                Action::DELETE => 'ROLE_SUPER_ADMIN',
                Action::DETAIL => 'ROLE_ADMIN',
                Action::INDEX => 'ROLE_USER',
            ]);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name', 'Nom et Prénom'))
            ->add(TextFilter::new('email', 'Email'))
            ->add(TextFilter::new('phone', 'Téléphone'))
            ->add(DateTimeFilter::new('createdAt', 'Crée le'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setPageTitle('index', 'Packs Special')
            ->setEntityLabelInSingular('Pack Special')
            ->setSearchFields(['name', 'email', 'phone']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name', 'Nom et prénom'),
            TextField::new('email', 'Email'),
            TextField::new('phone', 'Téléphone'),
            DateTimeField::new('createdAt', 'Crée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss")
        ];
    }
}
