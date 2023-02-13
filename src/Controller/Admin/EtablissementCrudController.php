<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class EtablissementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etablissement::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'sigle', 'secteur.name']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ->add(Crud::PAGE_INDEX, Action::DETAIL);
            ->setPermissions([
                Action::BATCH_DELETE => 'ROLE_SUPER_ADMIN',
                Action::DELETE => 'ROLE_SUPER_ADMIN',
                Action::EDIT => 'ROLE_SUPER_ADMIN',
                Action::DETAIL => 'ROLE_SUPER_ADMIN',
                Action::INDEX => 'ROLE_ADMIN',
            ]);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name', 'Désignation'))
            ->add(TextFilter::new('sigle', 'Sigle'))
            ->add(DateTimeFilter::new('createdAt', 'Crée le'))
            ->add(DateTimeFilter::new('updatedAt', 'Modifiée le'))
            ->add('secteur')
            ->add('ville');
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            yield IdField::new('id');
            yield ImageField::new('logo', 'Logo')->setBasePath('uploads/etablissements/');
            yield TextField::new('name', 'Désignation');
            yield TextField::new('sigle', 'Sigle');
            yield TextField::new('secteur');
            yield TextField::new('ville');
            yield DateTimeField::new('createdAt', 'Crée le');
            yield DateTimeField::new('updatedAt', 'Modifiée le');
            yield BooleanField::new('isActive', 'Etat');
            yield BooleanField::new('isPublic', 'Grand public');
        } else {
            $typesBac = ['SM', 'SVT', 'PC'];

            yield FormField::addTab('Détails')->setIcon('info');
            yield IdField::new('id')->onlyOnIndex();
            yield TextField::new('name', 'Désignation')->setColumns(6);
            yield TextField::new('sigle', 'Sigle')->setColumns(6);
            yield ChoiceField::new('typeBac', 'Type bac')->setChoices(array_combine($typesBac, $typesBac))->setColumns(6)->hideOnIndex();
            yield IntegerField::new('dureeFormation', 'Durée formation')->setColumns(6)->hideOnIndex();
            yield TextField::new('diplome', 'Diplome')->setColumns(6)->hideOnIndex();
            yield TextField::new('modeAdmis', 'Mode admis')->setColumns(6)->hideOnIndex();
            yield UrlField::new('video', 'Lien vidéo (youtube)')->setColumns(6)->hideOnIndex();
            yield UrlField::new('webSite', 'Site web')->setColumns(6)->hideOnIndex();
            yield AssociationField::new('secteur', 'Secteur')->setColumns(6);
            yield AssociationField::new('ville', 'Ville')->setColumns(6);
            yield TextField::new('tags', 'Tags')->setColumns(6)->hideOnIndex();
            yield ImageField::new('logo', 'Logo')
                ->setBasePath('uploads/etablissements/')
                ->setUploadDir('public/uploads/etablissements')
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setColumns(6);
            yield BooleanField::new('isActive', 'Etat')->setColumns(6);
            yield BooleanField::new('isPublic', 'Grand public')->setColumns(6);

            yield FormField::addTab('Seuils')->setIcon('check-square');
            yield IntegerField::new('seuilSM', 'Seuil SM')->setColumns(6)->hideOnIndex();
            yield IntegerField::new('seuilSP', 'Seuil SP')->setColumns(6)->hideOnIndex();
            yield IntegerField::new('seuilSVT', 'Seuil SVT')->setColumns(6)->hideOnIndex();
            yield IntegerField::new('seuilSAgro', 'Seuil SAgro')->setColumns(6)->hideOnIndex();
            yield IntegerField::new('seuilEco', 'Seuil Eco')->setColumns(6)->hideOnIndex();
            yield IntegerField::new('seuilSTM', 'Seuil STM')->setColumns(6)->hideOnIndex();
            yield IntegerField::new('seuilSTE', 'Seuil STE')->setColumns(6)->hideOnIndex();

            yield FormField::addTab('Contact information')->setIcon('phone');
            yield TextField::new('adresse', 'Adresse')->setColumns(12)->hideOnIndex();
            yield TextField::new('phone', 'Téléphone')->setColumns(6)->hideOnIndex();
            yield TelephoneField::new('phoneWP', 'Whatsapp')->setColumns(6)->hideOnIndex();
        }
    }
}
