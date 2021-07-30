<?php

//src/controller/PostController.php
namespace App\Controller;

use App\Entity\Post;
use App\Form\Type\PostType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends AbstractController
{
    
    /**
     * @Route("/posts", name="post_list")
     */

    public function index(): Response
    {
        // $msg = "New SymfonyPost";
        // fetch all Posts from Post Table and pass data to Twig
        $posts = $this->getDoctrine()
                    ->getRepository(Post::class)
                    ->findAll();
        // var_dump($posts);
        return $this->render('posts.html.twig',['posts' => $posts]);
    }
    /**
     * @Route("/sucess", name="post_success")
     * Method({"GET"})
     */
    public function success()
    {
        return $this->render('/success.html.twig');
    }
    /**
     public function success()
     * @Route("/new",name="new_post")
     * Method({"GET","POST"})
     */
    public function new(ValidatorInterface $validator , Request $request):Response
    { 
        // connect to Doctrine 
        $entityManager = $this->getDoctrine()->getManager();
        //create new Post();
        $post = new Post();
        $post->setName('Pierwszy Post');
        $post->setContent('some example content here for First Post');
        $post->setAuthor('Tomek Pierwszy');
        $post->setCreated(new \DateTime('tomorrow'));

        // show errors from ValidatorInterface
        $errors = $validator->validate($post);
        if(count($errors) > 0){
            return new Response((string) $errors, 400);
        }
        $form = $this->createForm(PostType::class, $post);
        // handle submitted form
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post = $form->getData();

            //tell Doctrine that you want to eventually save the Post
            $entityManager->persist($post);
            //actually execute the query(INSERT INTO post table)
            $entityManager->flush();
            //var_dump($post);
            return $this->redirectToRoute('post_success');
        }

        // rendering form with createView()

        return $this->render('new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}