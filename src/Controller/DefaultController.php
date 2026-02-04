<?php
namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('Default/index.html.twig', [
            'posts' => $postRepository->findBy([], ['publishedAt' => 'DESC'], 3),
        ]);
    }

}
