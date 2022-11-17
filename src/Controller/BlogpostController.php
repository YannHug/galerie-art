<?php

namespace App\Controller;

use App\Entity\Blogpost;
use App\Repository\BlogpostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogpostController extends AbstractController
{
    /**
     * @Route("/actualites", name="actualites")
     */
    public function actualites(BlogpostRepository $blogpostRepository, PaginatorInterface $paginator, Request $request):
    Response
    {
        $data = $blogpostRepository->findBy([], ['createdAt' => 'DESC']);
        $actualites = $paginator->paginate($data, $request->query->getInt('page', 1), 6);

        return $this->render('blogpost/actualites.html.twig', [
            'actualites' => $actualites,
        ]);
    }

    /**
     * @Route("/actualites/{slug}", name="actualites_details")
     */
    public function realisation(Blogpost $blogpost): Response
    {
        return $this->render('blogpost/details.html.twig', [
            'blogpost' => $blogpost,
        ]);
    }
}
