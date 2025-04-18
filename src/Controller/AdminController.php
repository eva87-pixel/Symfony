<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Contrôleur d'administration permettant la gestion des produits.
 *
 * @package App\Controller
 */
#[Route("/admin")]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    /**
     * Supprime un produit selon son ID, puis redirige vers la liste.
     *
     * @param Request $request              La requête HTTP
     * @param mixed $id                     L'ID du produit à supprimer
     * @param EntityManagerInterface $entityManager  Le gestionnaire d'entités
     *
     * @return Response                     Redirection vers la route 'liste'
     */
    #[Route("/admin/delete/{id}", name:"delete")]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, $id, EntityManagerInterface $entityManager)
    {
        $produitRepository = $entityManager->getRepository(Produit::class);
        $produit = $produitRepository->find($id);

        $entityManager->remove($produit);
        $entityManager->flush();

        $session = $request->getSession();
        $session->getFlashBag()->add('message', 'le produit a été supprimé');
        $session->set('statut', 'success');

        return $this->redirect($this->generateUrl('liste'));
    }

    /**
     * Insère un nouveau produit, gère l'upload de l'image et la persistance.
     *
     * @param Request $request              La requête HTTP
     * @param EntityManagerInterface $entityManager  Le gestionnaire d'entités
     *
     * @return Response                     Affiche le formulaire ou redirige
     */
    #[Route("/insert", name: "insert")]
    #[IsGranted('ROLE_ADMIN')]
    public function insert(Request $request, EntityManagerInterface $entityManager)
    {
        $produit = new Produit;
        $formProduit = $this->createForm(ProduitType::class, $produit);

        $formProduit->add('creer', SubmitType::class, [
            'label' => 'Insertion d\'un produit',
            'validation_groups' => ['registration', 'all']
        ]);

        $formProduit->handleRequest($request);

        if ($request->isMethod('post') && $formProduit->isValid()) {
            $file = $formProduit['lienImage']->getData();

            if (!is_string($file)) {
                $filename = $file->getClientOriginalName();
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
                $produit->setLienImage($filename);
            } else {
                $session = $request->getSession();
                $session->getFlashBag()->add('message', 'Vous devez choisir une image pour le produit');
                $session->set('statut', 'danger');

                return $this->redirect($this->generateUrl('insert'));
            }

            $entityManager->persist($produit);
            $entityManager->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'un nouveau produit a été ajouté');
            $session->set('statut', 'success');

            return $this->redirect($this->generateUrl('liste'));
        }

        return $this->render('Admin/create.html.twig', [
            'my_form' => $formProduit->createView()
        ]);
    }

    /**
     * Met à jour un produit existant (image, données, etc.) selon son ID.
     *
     * @param Request $request              La requête HTTP
     * @param int $id                       L'ID du produit à mettre à jour
     * @param EntityManagerInterface $entityManager  Le gestionnaire d'entités
     *
     * @return Response                     Affiche le formulaire ou redirige
     */
    #[Route("/admin/update/{id}", name: "update")]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $produitRepository = $entityManager->getRepository(Produit::class);
        $produit = $produitRepository->find($id);

        $img = $produit->getLienImage();
        $formProduit = $this->createForm(ProduitType::class, $produit);

        $formProduit->add('creer', SubmitType::class, [
            'label' => 'Mise à jour d\'un produit',
            'validation_groups' => ['all']
        ]);
        $formProduit->handleRequest($request);

        // Vérification : Si le produit est null, afficher un message d'erreur
        if (!$produit) {
            $this->addFlash('danger', 'Produit introuvable !');
            return $this->redirectToRoute('liste');
        }

        if ($request->isMethod('post') && $formProduit->isValid()) {
            $file = $formProduit['lienImage']->getData();

            if (!is_string($file)) {
                $filename = $file->getClientOriginalName();
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
                $produit->setLienImage($filename);
            } else {
                $produit->setLienImage($img);
            }

            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Le produit a été mis à jour avec succès');
            return $this->redirectToRoute('liste');
        }

        return $this->render('Admin/create.html.twig', [
            'my_form' => $formProduit->createView()
        ]);
    }

    /**
     * Teste la validation d'un produit sans formulaire (NotBlank, Length).
     *
     * @param EntityManagerInterface $entityManager  Le gestionnaire d'entités
     *
     * @return Response                     Retourne "ok" ou liste les erreurs
     */
    #[Route('/testvalid', name:'testvalid')]
    public function testAction(EntityManagerInterface $entityManager)
    {
        $produit = new Produit;

        $produit->setNom('');
        $produit->setPrix(20);
        $produit->setQuantite(10);
        $produit->setLienImage("monimage.jpg");
        $produit->setRupture(false);

        // On récupère le service validator
        $validator = Validation::createValidator();
        $listErrors = $validator->validate($produit, [
            new Length(['min' => 2]),
            new NotBlank(),
        ]);

        // Si $listErrors n'est pas vide, on affiche les erreurs
        if (count($listErrors) > 0) {
            return new Response((string) $listErrors);
        } else {
            $entityManager->persist($produit);
            $entityManager->flush();
            return new Response("ok");
        }
    }
}