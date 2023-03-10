<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use Doctrine\ORM\QueryBuilder;
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
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class EtablissementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etablissement::class;
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
                Action::INDEX => 'ROLE_USER',
            ]);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion des ??tablissements')
            ->setEntityLabelInSingular('Etablissement')
            ->setEntityLabelInPlural('Etablissements')
            ->setSearchFields(['name', 'sigle', 'secteur.name', 'villes.name']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name', 'D??signation'))
            ->add(TextFilter::new('sigle', 'Sigle'))
            ->add(DateTimeFilter::new('createdAt', 'Cr??e le'))
            ->add(DateTimeFilter::new('updatedAt', 'Modifi??e le'))
            ->add('secteurs')
            ->add('villes')
            ->add(BooleanFilter::new('isActive', 'Etat'))
            ->add(BooleanFilter::new('isPublic', 'Grand public'));
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            yield IdField::new('id');
            yield ImageField::new('logo', 'Logo')->setBasePath('uploads/etablissements/');
            yield TextField::new('name', 'D??signation');
            yield TextField::new('sigle', 'Sigle');
            yield AssociationField::new('secteurs')->renderAsNativeWidget();
            yield AssociationField::new('villes');
            yield DateTimeField::new('createdAt', 'Cr??e le');
            yield DateTimeField::new('updatedAt', 'Modifi??e le');
            yield BooleanField::new('isActive', 'Etat')->setDisabled(!$this->isGranted("ROLE_SUPER_ADMIN"));
            yield BooleanField::new('isPublic', 'Grand public')->setDisabled(!$this->isGranted("ROLE_ADMIN"));
        } else {
            $typesBac = ['SM', 'SVT', 'PC'];

            yield FormField::addTab('D??tails')->setIcon('info');
            yield TextField::new('name', 'D??signation')->setColumns(6);
            yield TextField::new('sigle', 'Sigle')->setColumns(6);
            yield ChoiceField::new('typeBac', 'Type bac')
                ->setChoices(array_combine($typesBac, $typesBac))
                ->allowMultipleChoices()
                ->setColumns(6);
            yield IntegerField::new('dureeFormation', 'Dur??e formation (mois)')->setColumns(6);
            yield TextField::new('diplome', 'Diplome')->setColumns(6);
            yield TextField::new('modeAdmis', 'Mode admis')->setColumns(6);
            yield UrlField::new('video', 'Lien vid??o (youtube)')->setColumns(6);
            yield UrlField::new('webSite', 'Site web')->setColumns(6);
            yield AssociationField::new('secteurs', 'Secteurs')
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    $qb->andWhere('entity.isActive = :enabled')
                        ->setParameter('enabled', true);
                })
                ->setColumns(6);
            yield AssociationField::new('villes', 'Villes')
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    $qb->andWhere('entity.isActive = :enabled')
                        ->setParameter('enabled', true);
                })
                ->setColumns(6);
            yield AssociationField::new('tags', 'Tags')
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    $qb->andWhere('entity.isActive = :enabled')
                        ->setParameter('enabled', true);
                })
                ->setColumns(6);

            yield ImageField::new('logo', 'Logo')
                ->setBasePath('uploads/etablissements/')
                ->setUploadDir('public/uploads/etablissements')
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setColumns(6)
                ->setRequired($pageName !== Crud::PAGE_EDIT);
            yield BooleanField::new('isActive', 'Etat')->setColumns(6)->setPermission("ROLE_SUPER_ADMIN");
            yield BooleanField::new('isPublic', 'Grand public')->setColumns(6)->setPermission("ROLE_ADMIN");

            yield FormField::addTab('Seuils')->setIcon('check-square');
            yield NumberField::new('seuilSM', 'Seuil SM')->setColumns(6);
            yield NumberField::new('seuilSP', 'Seuil SP')->setColumns(6);
            yield NumberField::new('seuilSVT', 'Seuil SVT')->setColumns(6);
            yield NumberField::new('seuilSAgro', 'Seuil SAgro')->setColumns(6);
            yield NumberField::new('seuilEco', 'Seuil Eco')->setColumns(6);
            yield NumberField::new('seuilSTM', 'Seuil STM')->setColumns(6);
            yield NumberField::new('seuilSTE', 'Seuil STE')->setColumns(6);

            yield FormField::addTab('Contact information')->setIcon('phone');
            yield TextField::new('adresse', 'Adresse')->setColumns(6);
            yield UrlField::new('fb', 'Facebook')->setColumns(6);
            yield TelephoneField::new('phone', 'T??l??phone')->setColumns(6);
            yield TelephoneField::new('phoneWP', 'Whatsapp')->setColumns(6);


            // yield FormField::addTab('Docs')->setIcon('info');
            // yield AssociationField::new('documentEtablissements', 'Docs')->renderAsEmbeddedForm();
        }
    }
}
