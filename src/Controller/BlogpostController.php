<?php

namespace App\Controller;

use App\Entity\Blogpost;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\BlogpostRepository;
use App\Repository\CommentaireRepository;
use App\Service\CommentaireService;
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
    public function realisation(Blogpost           $blogpost, Request $request, CommentaireRepository $commentaireRepository,
                                CommentaireService $commentaireService): Response
    {
        $commentaires = $commentaireRepository->findCommentaires($blogpost);
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire = $form->getData();
            $commentaireService->persistCommentaire($commentaire, $blogpost, null);

            return $this->redirectToRoute('actualites_details', ['slug' => $blogpost->getSlug()]);
        }

        return $this->render('blogpost/details.html.twig', [
            'blogpost' => $blogpost,
            'form' => $form->createView(),
            'commentaires' => $commentaires,
        ]);
    }
}
