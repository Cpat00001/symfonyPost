<?php

//src/controller/PostController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    
    /**
     * @Route("/posts")
     */

    public function index(): Response
    {
        $msg = "New SymfonyPost";

        return $this->render('posts.html.twig',['msg' => $msg ]);
    }
}