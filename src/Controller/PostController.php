<?php

//src/controller/PostController.php
namespace App\Controller;

use App\Entity\Post;
use App\Form\Type\PostType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    
    /**
     * @Route("/posts", name="post_list")
     */

    public function index(): Response
    {
        $msg = "New SymfonyPost";

        return $this->render('posts.html.twig',['msg' => $msg ]);
    }
    /**
     * @Route("/new",name="new_post")
     * Method({"GET","POST"})
     */
    public function new(Request $request):Response
    { 
        //create new Post();
        $post = new Post();
        $post->setName('Pierwszy Post');
        $post->setContent('some example content here for First Post');
        $post->setAuthor('Tomek Pierwszy');
        $post->setCreated(new \DateTime('tomorrow'));

        $form = $this->createForm(PostType::class, $post);

        // rendering form with createView()

        return $this->render('new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}