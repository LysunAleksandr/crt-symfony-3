<?php

namespace App\Controller\Admin;

use App\Entity\StaticPage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ArticleCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Blog');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'homepage');
        yield MenuItem::linkToCrud('Article', 'fas fa-list', Article::class);
        yield MenuItem::linkToCrud('Comments', 'fas fa-comments', Comment::class);
        if( $this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Users', 'fas fa-list', User::class);
            yield MenuItem::linkToCrud('StaticPage', 'fas fa-list', StaticPage::class);
        }
    }


}
