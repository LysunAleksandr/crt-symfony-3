<?php

namespace App\Controller\Admin;

use App\Entity\StaticPage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class StaticPageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StaticPage::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
