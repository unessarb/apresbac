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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

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
            ->setSearchFields(['title', 'content', 'etablissement.name']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('title', 'Désignation'))
            ->add(DateTimeFilter::new('createdAt', 'Crée le'))
            ->add(DateTimeFilter::new('updatedAt', 'Modifiée le'))
            ->add(DateTimeFilter::new('publishedAt', 'Publiée le'))
            ->add('etablissement')
            ->add(BooleanFilter::new('isActive', 'Etat'))
            ->add(BooleanFilter::new('isPublic', 'Grand public'));
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            yield IdField::new('id');
            yield ImageField::new('image', 'Image')->setBasePath('uploads/news/');
            yield TextField::new('title', 'Désignation');
            yield TextEditorField::new('content', 'Contenu');
            yield TextField::new('etablissement');
            yield DateTimeField::new('createdAt', 'Crée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss");
            yield DateTimeField::new('updatedAt', 'Modifiée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss");
            yield DateTimeField::new('publishedAt', 'Publiée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss");
            yield BooleanField::new('isActive', 'Etat')->setDisabled(!$this->isGranted("ROLE_SUPER_ADMIN"));
            yield BooleanField::new('isPublic', 'Grand public')->setDisabled(!$this->isGranted("ROLE_ADMIN"));
        } else {
            yield TextField::new('title', 'Désignation')->setColumns(6);
            yield AssociationField::new('etablissement')
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    $qb->andWhere('entity.isActive = :enabled')
                        ->setParameter('enabled', true);
                })
                ->setColumns(6);
            yield TextEditorField::new('content', 'Contenu')->setColumns(12);
            yield ImageField::new('image', 'Image')
                ->setBasePath('uploads/news/')
                ->setUploadDir('public/uploads/news')
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setColumns(6)
                ->setRequired($pageName !== Crud::PAGE_EDIT);
            yield DateTimeField::new('publishedAt', 'Publiée le')->setColumns(12);
            yield BooleanField::new('isActive', 'Etat')->setColumns(12)->setPermission("ROLE_SUPER_ADMIN");
            yield BooleanField::new('isPublic', 'Grand public')->setColumns(12)->setPermission("ROLE_ADMIN");
        }
    }
}
