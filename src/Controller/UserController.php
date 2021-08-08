<?php
//src/Controller/UserController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /*
    * @Route("/register", name="new_user")
    * 
    */
    public function new(Request $request): Response
    {
        //creates a User object
        $user = new User();
        $user->setEmail('test1@abc.com');
        $user->setPassword('abc123');
        
        $form = $this->createForm(UserType::class, $user);

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}



