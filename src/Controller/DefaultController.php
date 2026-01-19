<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 18/10/2019
 * Time: 13:43
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index(){
        return $this->render('Default/index.html.twig');
    }

}
