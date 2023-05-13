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
            ->setPageTitle('index', 'Gestion des établissements')
            ->setEntityLabelInSingular('Etablissement')
            ->setEntityLabelInPlural('Etablissements')
            ->setSearchFields(['name', 'sigle', 'secteur.name', 'villes.name']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name', 'Désignation'))
            ->add(TextFilter::new('sigle', 'Sigle'))
            ->add(DateTimeFilter::new('createdAt', 'Crée le'))
            ->add(DateTimeFilter::new('updatedAt', 'Modifiée le'))
            ->add('secteurs')
            ->add('villes')
            ->add(BooleanFilter::new('isActive', 'Actif'))
            ->add(BooleanFilter::new('isPublic', 'Publique'));
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            yield IdField::new('id');
            yield ImageField::new('logo', 'Logo')->setBasePath('uploads/etablissements/');
            yield TextField::new('name', 'Désignation');
            yield TextField::new('sigle', 'Sigle');
            yield AssociationField::new('secteurs')->renderAsNativeWidget();
            yield AssociationField::new('villes');
            yield DateTimeField::new('createdAt', 'Crée le');
            yield DateTimeField::new('updatedAt', 'Modifiée le');
            yield BooleanField::new('isActive', 'Actif')->setDisabled(!$this->isGranted("ROLE_SUPER_ADMIN"));
            yield BooleanField::new('isEtranger', 'Si étranger')->setDisabled(!$this->isGranted("ROLE_ADMIN"));
            yield BooleanField::new('isPublic', 'Publique')->setDisabled(!$this->isGranted("ROLE_ADMIN"));
        } else {
            $typesBac = ['SM', 'PC', 'SVT', 'SAgro', 'SEco', 'SGC', 'STM', 'STE', 'LSH', 'Chariâa', 'Toutes options'];
            yield FormField::addTab('Détails')->setIcon('info');
            yield TextField::new('name', 'Désignation')->setColumns(6);
            yield TextField::new('sigle', 'Sigle')->setColumns(6);
            yield ChoiceField::new('typeBac', 'Type bac')
                ->setChoices(array_combine($typesBac, $typesBac))
                ->allowMultipleChoices()
                ->setColumns(6);
            yield IntegerField::new('dureeFormation', 'Durée formation (ans)')->setColumns(6);
            yield TextField::new('diplome', 'Diplome')->setColumns(6);
            yield TextField::new('modeAdmis', 'Mode admis')->setColumns(6);
            yield UrlField::new('video', 'Lien vidéo (youtube)')->setColumns(6);
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

            yield BooleanField::new('isActive', 'Actif')->setColumns(2)->setPermission("ROLE_SUPER_ADMIN");
            yield BooleanField::new('isEtranger', 'Si étranger')->setColumns(2)->setPermission("ROLE_ADMIN");
            yield BooleanField::new('isPublic', 'Publique')->setColumns(2)->setPermission("ROLE_ADMIN");

            yield FormField::addTab('Seuils')->setIcon('check-square');
            yield NumberField::new('seuilSM', 'Seuil SM')->setColumns(6)->setNumDecimals(2);
            yield NumberField::new('seuilSP', 'Seuil SPC')->setColumns(6)->setNumDecimals(2);
            yield NumberField::new('seuilSVT', 'Seuil SVT')->setColumns(6)->setNumDecimals(2);
            yield NumberField::new('seuilSAgro', 'Seuil SAgro')->setColumns(6)->setNumDecimals(2);
            yield NumberField::new('seuilEco', 'Seuil SEco')->setColumns(6)->setNumDecimals(2);
            yield NumberField::new('seuilSGC', 'Seuil SGC')->setColumns(6)->setNumDecimals(2);
            yield NumberField::new('seuilSTM', 'Seuil STM')->setColumns(6)->setNumDecimals(2);
            yield NumberField::new('seuilSTE', 'Seuil STE')->setColumns(6)->setNumDecimals(2);
            yield NumberField::new('seuilLSH', 'Seuil LSH(Lettre)')->setColumns(6)->setNumDecimals(2);
            yield NumberField::new('seuilChariaa', 'Seuil Chariâa')->setColumns(6)->setNumDecimals(2);

            yield FormField::addTab('Contact information')->setIcon('phone');
            yield TextField::new('adresse', 'Adresse')->setColumns(6);
            yield UrlField::new('fb', 'Facebook')->setColumns(6);
            yield TelephoneField::new('phone', 'Téléphone')->setColumns(6);
            yield TelephoneField::new('phoneWP', 'Whatsapp')->setColumns(6);


            // yield FormField::addTab('Docs')->setIcon('info');
            // yield AssociationField::new('documentEtablissements', 'Docs')->renderAsEmbeddedForm();
        }
    }
}
