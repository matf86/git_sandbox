<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $books_list = $entityManager->getRepository('AppBundle:Book')
            ->findAllDistinct();

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $books_list, /* query NOT result */
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );

        return $this->render('book/index.html.twig', [
            'books' => $pagination
        ]);
    }

    /**
     * @Route("/books/{id}", name="show_book")
     */
    public function showAction($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $book = $entityManager->getRepository('AppBundle:Book')
            ->find($id);

        return $this->render('book/show.html.twig', [
            'book' => $book
        ]);
    }

    /**
     * @Route("/books/issue/{id}", name="show_book")
     */
    public function issueAction($id, Request $request)
    {
        //todo
        //issue book by user logic
    }
}
