<?php

namespace App\Controller;

use App\Entity\Post;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Algolia\SearchBundle\IndexManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends AbstractController
{

    protected $indexManager;

    public function __construct(IndexManagerInterface $indexingManager)
    {
        $this->indexManager = $indexingManager;
    }

    public function searchedPost(Request $request)
    {
        //$repository = $this->getDoctrine()->getRepository(Post::class);

        //$all_post = $repository->findAll();


        $q = $request->get('query');





        $em = $this->getDoctrine()->getManagerForClass(Post::class);
        $posts = $this->indexManager->search($q,Post::class, $em);



         return $this->render('Search/post.html.twig',['posts'=>$posts]);







    }



}
