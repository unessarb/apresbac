<?php

namespace App\Controller\Admin;

use App\Entity\News;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class NewsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return News::class;
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
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setPageTitle('index', 'Gestion des actualités')
            ->setEntityLabelInSingular('Actualité')
            ->setEntityLabelInPlural('Actualités')
            ->setSearchFields(['title', 'publishedBy.name', 'updatedBy.name', 'etablissement.name']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('title', 'Désignation'))
            ->add('etablissement')
            ->add(EntityFilter::new('publishedBy', 'Publiée par'))
            ->add(EntityFilter::new('updatedBy', 'Modifée par'))
            ->add(DateTimeFilter::new('createdAt', 'Crée le'))
            ->add(DateTimeFilter::new('updatedAt', 'Modifiée le'))
            ->add(BooleanFilter::new('isActive', 'Etat'));
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            yield IdField::new('id');
            yield ImageField::new('image', 'Image')->setBasePath('uploads/news/');
            yield TextField::new('title', 'Désignation');
            yield TextField::new('etablissement');
            yield TextField::new('publishedBy', 'Publiée par');
            yield TextField::new('updatedBy', 'Modifiée par');
            yield DateTimeField::new('createdAt', 'Crée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss");
            yield DateTimeField::new('updatedAt', 'Modifiée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss");
            yield BooleanField::new('isActive', 'Etat')->setDisabled(!$this->isGranted("ROLE_SUPER_ADMIN"));
        } else {
            yield TextField::new('title', 'Désignation')->setColumns(6);
            yield AssociationField::new('etablissement')
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    $qb->andWhere('entity.isActive = :enabled')
                        ->setParameter('enabled', true);
                })
                ->setColumns(6);
            yield DateField::new('dateLimitInscription', 'Date limite inscription')->setColumns(6)->addWebpackEncoreEntries('admin');
            yield ImageField::new('image', 'Image')
                ->setBasePath('uploads/news/')
                ->setUploadDir('public/uploads/news')
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setColumns(6)
                ->setRequired(false);

            yield BooleanField::new('isActive', 'Etat')->setColumns(12)->setPermission("ROLE_SUPER_ADMIN");
        }
    }
}
