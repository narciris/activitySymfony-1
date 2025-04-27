<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/project')]
class ProjectController extends AbstractController
{
    #[Route('/home', name: 'project_home')]
    public function index(): Response
    {
        return $this->render('project/home.html.twig');
    }

}