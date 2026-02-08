<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 18/10/2019
 * Time: 13:43
 */

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    public function index(PostRepository $postRepository){
        $latestPosts = $postRepository->findBy([], ['publishedAt' => 'DESC'], 3);
        
        return $this->render('accueil/index.html.twig', [
            'posts' => $latestPosts,
        ]);
    }
}
