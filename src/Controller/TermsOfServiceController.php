<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TermsOfServiceController extends AbstractController
{
    #[Route('/terms-of-service', name: 'app_terms_of_service')]
    public function index(): Response
    {
        return $this->render('terms_of_service/index.html.twig', [
            'controller_name' => 'TermsOfServiceController',
        ]);
    }
}
