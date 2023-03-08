<?php

namespace App\Controller\Admin;

use App\Entity\DocumentEtablissement;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class DocumentEtablissementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DocumentEtablissement::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setPageTitle('index', 'Gestion des docuements')
            ->setEntityLabelInSingular('Document')
            ->setEntityLabelInPlural('Documents')
            ->setSearchFields(['designation', 'etablissement.name', "link"]);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('designation', 'Désignation'))
            ->add(TextFilter::new('link', 'Lien'))
            ->add('etablissement');
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            yield IdField::new('id');
            yield TextField::new('designation', 'Désignation');
            yield TextField::new('etablissement');
            yield TextField::new('link', 'Lien');
            yield DateTimeField::new('createdAt', 'Crée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss");
            yield DateTimeField::new('updatedAt', 'Modifiée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss");
        } else {
            yield TextField::new('designation', 'Désignation')->setColumns(12);
            yield AssociationField::new('etablissement')
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    $qb->andWhere('entity.isActive = :enabled')
                        ->setParameter('enabled', true);
                })
                ->setColumns(6);
            yield TextField::new('link', 'Lien')->setColumns(6);
        }
    }
}
