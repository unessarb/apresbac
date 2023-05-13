<?php

namespace App\Controller\Admin;

use App\Entity\DocNews;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class DocNewsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DocNews::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setPageTitle('index', 'Gestion des docuements d\'actualités')
            ->setEntityLabelInSingular('Document d\'actualité')
            ->setEntityLabelInPlural('Documents d\'actualités')
            ->setSearchFields(['title', 'news.title', "link", "file"]);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('title', 'Titre'))
            ->add(EntityFilter::new('news', 'Actualité'))
            ->add(TextFilter::new('link', 'Lien'))
            ->add(TextFilter::new('file', 'Fichier'))
            ->add(DateTimeFilter::new('createdAt', 'Crée le'))
            ->add(DateTimeFilter::new('updatedAt', 'Modifiée le'));
    }


    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_INDEX) {
            yield IdField::new('id');
            yield ImageField::new('file', 'Fichier')->setBasePath('uploads/news_docs/');
            yield TextField::new('title', 'Titre');
            yield TextField::new('news', 'Actualité');
            yield TextField::new('link', 'Lien');
            yield DateTimeField::new('createdAt', 'Crée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss");
            yield DateTimeField::new('updatedAt', 'Modifiée le')->setFormat("yyyy-MM-dd 'à' HH:mm:ss");
        } else {
            yield TextField::new('title', 'Titre')->setColumns(6);
            yield AssociationField::new('news', 'Actualité')->setColumns(6);
            yield TextField::new('link', 'Lien')->setColumns(6);
            yield ImageField::new('file', 'Fichier')
                ->setBasePath('uploads/news_docs/')
                ->setUploadDir('public/uploads/news_docs')
                ->setUploadedFileNamePattern('[slug]-[contenthash].[extension]')
                ->setColumns(6)
                ->setRequired(false);
        }
    }
}
