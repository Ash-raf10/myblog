<?php

namespace App\Controller;

use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;



class AuthorController extends AbstractController
{

    //welcome page after signing up as an author
    public function home_auth()
    {
        return $this->render('Author\author_home.html.twig');
    }

    //sign up form for author
    public function sign_up(Request $request)
    {
        $new = new Author();

        $repository = $this->getDoctrine()->getRepository(Author::class);
        $all_author = $repository->findAll();


        //symfony form builder
        $form = $this->createFormBuilder($new)
            ->add('Name', TextType::class,['label'=> 'Name: '])
            ->add('Phone',NumberType::class,['label' => 'Contact: '])
            ->add('Save', SubmitType::class, ['label' => 'Go'])
            ->getForm();

        $form->handleRequest($request);



        //after submitting the form ,,validating and updating form input in  database
        if ($form->isSubmitted() && $form->isValid()) {


            $new = $form->getData();

            $name = $new->getName();

            if (sizeof($all_author)>=10){
                return $this->redirectToRoute("auth_error");
            }

            foreach ($all_author as $a){
                if ($a->getName()==$name){
                    return $this->redirectToRoute('post_new');
                }
            }




            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($new);
            $entityManager->flush();

            //redirecting to the welcome page for author
            return $this->redirectToRoute('post_new');
        }

        //rendering the form
        return $this->render('Author\sign_auth.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    //getting the list of all authors
    public function all_authors()
    {
        $repository = $this->getDoctrine()->getRepository(Author::class);
        $all_author = $repository->findAll();

        $user = $this->getUser();

        return $this->render("Author\all_author.html.twig", ['user'=>$user,'author' => $all_author]);
    }


    //finding individual author

    public function author($name)
    {

        $author = $this->getDoctrine()->getRepository(Author::class)->findOneBy(['name'=>$name]);

        $user = $this->getUser();


        //if author is not found
        if (!$author) {
            return $this->render("Error\NotFound.html.twig");
        }

        //else
        return $this->render("Author\single_author.html.twig", ['author' => $author,'user'=>$user]);

    }

    //finding an  author's all article
    public function author_post($id)
    {

        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);


        $post = $author->getPosts();
        $user = $this->getUser();

        //if no post is found of that author
        if ($post->isEmpty()) {
            return $this->render("Error\NotFound.html.twig");
        }

        return $this->render("Author\author_post.html.twig", ['post' => $post, 'author' => $author,'user'=>$user]);
    }

    public function writers_post($id){
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);


        $post = $author->getPosts();

        $user = $this->getUser();


        //if no post is found of that author
        if ($post->isEmpty()) {
            return $this->render("Error\NotFound.html.twig");
        }

        return $this->render("Author\writers_post.html.twig", ['post' => $post, 'author' => $author,'user'=>$user]);
    }

}

