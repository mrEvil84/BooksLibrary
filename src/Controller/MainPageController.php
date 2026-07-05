<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainPageController extends AbstractController
{
    #[Route('/main-page', name: 'app_main_page', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('main_page/index.html.twig');
    }
}
