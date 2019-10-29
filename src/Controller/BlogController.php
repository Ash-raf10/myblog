<?php


namespace App\Controller;

use App\Entity\Author;
use App\Entity\Post;

//currently not using for pic uploading
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class BlogController extends AbstractController
{


    //method to show all the blog post
    public function show_all()
    {
        //get the repository of Post entity
        $repository = $this->getDoctrine()->getRepository(Post::class);

        $all_post = $repository->findAll();

        $user = $this->getUser();

        return $this->render("Blog\all_post.html.twig", ['all' => $all_post,'user' => $user]);
    }

    //showing individual article using id
    public function show($title)
    {
        //finding the individual article using docttine find one by

        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findOneBy(['title'=>$title]);

        $user = $this->getUser();

        //If no post is found containing that id
        if (!$post) {
            return new Response('no post found for ' . $title . " ");

        }

        //getting the author name through one to many mapping (post->author)

        $auth_name = $post->getAuthor()->getName();


        return $this->render("Blog\single_post.html.twig", ['post' => $post, 'auth_name' => $auth_name,'user' =>$user]);
    }

    //deleting a certain article
    public function delete($id)
    {

        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        //after deleting redirecting to home page
        return $this->redirectToRoute("home");

    }

    //method that render the page for posting article form
    public function new()
    {
        $user = $this->getUser();
        return $this->render('Blog\new_post.html.twig',['user'=>$user]);
    }

    //for handling the submitting article form
    public function create(Request $request)
    {
        //getting the author repo for mapping author to article
        $author_repo = $this->getDoctrine()->getRepository(Author::class);

        $repository = $this->getDoctrine()->getRepository(Post::class);

        $all_post = $repository->findAll();

        //creating a post entity
        $post = new Post();
        //getting the values from the form submission
        $title = $request->get("title");
        $authors = $request->get("authors");
        $article = $request->get("article");
        $image = $request->files->get("imageFile");


        //if image is submitted then it is uploaded to the uploaded directory and generate a filename
        if ($image) {

            $upload_directory = $this->getParameter('upload_directory');
            $filename = md5(uniqid()) . '.' . $image->guessExtension();

            $image->move(
                $upload_directory, $filename
            );
        } //if no image is submitted then filename will be saved in database as null
        else {
            $filename = null;
        }

        //matching the author name that is submitted to the author_repo
        $author_full = $author_repo->findOneBy(['name' => $authors]);

        //if there exist no auther as such
        if (!$author_full) {
            return $this->redirectToRoute('sign_up');
        } else {
            //getting the auth id of the matched author
            $auth_id = $author_full->getId();
            $auth = $this->getDoctrine()
                ->getRepository(Author::class)
                ->find($auth_id);


            if (sizeof($all_post)>=15){

                return $this->redirectToRoute('post_error');

            }


            $entityManager = $this->getDoctrine()->getManager();


            $post->setTitle($title);
            $post->setArticle($article);
            $post->setAuthor($auth);
            $post->setImage($filename);

            $entityManager->persist($post);

            $entityManager->flush();


            return $this->render('Blog\submit.html.twig', ['post' => $post]);

        }

    }
}