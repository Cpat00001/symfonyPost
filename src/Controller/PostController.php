<?php

//src/controller/PostController.php
namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Form\Type\PostType;
use App\Form\Type\UpdatePostType;

// use Symfony\Component\Form\Extension\Core\Type\DateType;
// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    // show individual post
    /**
     * @Route("/post/{id}",name="show_post_details")
     * */
    public function showIndPost(int $id):Response
    {
        $postID = $id; 
        // find a Post based on ID passed as parameter
        $post = $this->getDoctrine()
                ->getRepository(Post::class)
                ->find($id);
        // manage 404 page Post not found
        if(!$post){
            // throw $this->createNotFoundException('The post does not exist');
            // return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
        }
        

        return $this->render('post_view.html.twig',['postID' => $postID, 'post' => $post]);
    }
    // show and edit indiviudual Post
    /**
     * @Route("/post/edit/{id}",name="edit_post")
     *
     */
    public function update(Request $request , int $id):Response
    {
        //connect with DB and fetch post with given ID
        $entityManager = $this->getDoctrine()->getManager();
        $postID = $entityManager->getRepository(Post::class)->find($id);

        //if post doesn't exist
        if (!$postID) {
            throw $this->createNotFoundException(
                'No post found for id '.$id
            );
        }

        // display form and pass data form Poss chosen by ID
        $formUpdated = $this->createForm(UpdatePostType::class, $postID);
           // rendering form with createView()
        
        $formUpdated->handleRequest($request);
        if($formUpdated->isSubmitted() && $formUpdated->isValid()){
            //get data from form
            $updatedPost = $formUpdated->getData();
            $entityManager->persist($updatedPost);
            //execute and save queries (INSERT INTO)
            $entityManager->flush();
            //redirect to  HomePage with list of posts(including Updated Post)
            return $this->redirectToRoute('post_list');
        }
        return $this->render('update.html.twig', [
            'form' => $formUpdated->createView(),
        ]);
    } 
    // delete post
    /**
     * @Route("/delete/{id}",name="delete_post")
     */
    public function delete(int $id):Response
    {
        $entityManager = $this->getDoctrine()->getManager() ;
        $post_toDelete = $entityManager->getRepository(Post::class)->find($id);

        $entityManager->remove($post_toDelete);
        //remove post from DB
        $entityManager->flush();
        // show message with confirmation, that Post was deleted
        $this->addFlash(
            'notice',
            'The selected post with ID: ' . $id . ' was deleted.Thank you'
        );
        //after deleteing redirect to "post_list" page with list of current posts
        return $this->redirectToRoute('post_list');

    }
}