<?php

namespace App\Controller;

use App\Entity\Distributeur;
use App\Form\DistributeurType;                      // Utilisation du form type correct
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;      // Annotation Route
use Symfony\Component\Security\Http\Attribute\IsGranted; // Import manquant

#[Route('/distributeur')]
#[IsGranted('ROLE_ADMIN')]                            // Seuls les ADMIN et SUPER_ADMIN ont accès
final class DistributeurController extends AbstractController
{
    #[Route('', name: 'app_distributeur_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $distributeurs = $em->getRepository(Distributeur::class)->findAll();

        return $this->render('distributeur/index.html.twig', [
            'distributeurs' => $distributeurs,
        ]);
    }

    #[Route('/new', name: 'app_distributeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $distributeur = new Distributeur();
        $form = $this->createForm(DistributeurType::class, $distributeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($distributeur);
            $em->flush();

            return $this->redirectToRoute('app_distributeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('distributeur/new.html.twig', [
            'distributeur' => $distributeur,
            'form'         => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_distributeur_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Distributeur $distributeur): Response
    {
        return $this->render('distributeur/show.html.twig', [
            'distributeur' => $distributeur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_distributeur_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Distributeur $distributeur, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DistributeurType::class, $distributeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_distributeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('distributeur/edit.html.twig', [
            'distributeur' => $distributeur,
            'form'         => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_distributeur_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Distributeur $distributeur, EntityManagerInterface $em): Response
    {
        // Vérification du CSRF token envoyé dans le formulaire de suppression
        if ($this->isCsrfTokenValid('delete' . $distributeur->getId(), $request->request->get('_token'))) {
            $em->remove($distributeur);
            $em->flush();
        }

        return $this->redirectToRoute('app_distributeur_index', [], Response::HTTP_SEE_OTHER);
    }
}