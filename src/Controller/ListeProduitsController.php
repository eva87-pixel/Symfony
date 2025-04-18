<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\Distributeur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Contrôleur pour la gestion et l'affichage des produits et distributeurs.
 *
 * Ce contrôleur fournit plusieurs actions :
 * - apiTest() : Retourne la liste des noms de tous les produits au format JSON.
 * - eager() : Récupère tous les produits et affiche la vue 'eager.html.twig'.
 * - listedistributeur() : Récupère tous les distributeurs et affiche la vue 'distributeurs.html.twig'.
 * - liste() : Récupère tous les produits ainsi que le dernier produit inséré et transmet ces données à la vue 'index.html.twig'.
 *
 * @package App\Controller
 */
class ListeProduitsController extends AbstractController
{
    /**
     * Retourne une réponse JSON contenant la liste des noms de tous les produits.
     *
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités pour accéder aux produits.
     *
     * @return JsonResponse La réponse JSON contenant les noms des produits.
     */
    #[Route("/apitest", name:"apitest")]
    public function apiTest(EntityManagerInterface $entityManager)
    {
        $produitsRepository = $entityManager->getRepository(Produit::class);
        $listeProduits = $produitsRepository->findAll();
        $resultat = [];
        foreach ($listeProduits as $produit) {
            array_push($resultat, $produit->getNom());
        }
        $reponse = new JsonResponse($resultat);
        return $reponse;
    }

    /**
     * Récupère tous les produits et affiche la vue 'eager.html.twig'.
     *
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     *
     * @return Response La réponse qui affiche la vue eager.
     */
    #[Route("/eager", name: "eager")]
    public function eager(EntityManagerInterface $entityManager)
    {
        $produitsRepository = $entityManager->getRepository(Produit::class);
        $listeProduits = $produitsRepository->findAll();
        return $this->render('liste_produits/eager.html.twig', [
            'listeproduits' => $listeProduits,
        ]);
    }

    /**
     * Récupère tous les distributeurs et affiche la vue 'distributeurs.html.twig'.
     *
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     *
     * @return Response La réponse qui affiche la liste des distributeurs.
     */
    #[Route("/distrib", name: "distributeurs")]
    public function listedistributeur(EntityManagerInterface $entityManager)
    {
        $repositoryDistributeurs = $entityManager->getRepository(Distributeur::class);
        $distributeurs = $repositoryDistributeurs->findAll();
        return $this->render('liste_produits/distributeurs.html.twig', [
            'distributeurs' => $distributeurs,
        ]);
    }

    /**
     * Récupère la liste de tous les produits ainsi que le dernier produit inséré,
     * puis transmet ces données à la vue 'index.html.twig'.
     *
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     *
     * @return Response La réponse qui affiche la liste des produits.
     */
    #[Route('/liste', name: 'liste')]
    public function liste(EntityManagerInterface $entityManager)
    {
        // Récupération du repository de Produit
        $produitsrepository = $entityManager->getRepository(Produit::class);

        // Récupération de tous les produits
        $listeproduits = $produitsrepository->findAll();
        $lastProduit = $produitsrepository->getLastProduit();

        // Transmission des produits au template Twig
        return $this->render('liste_produits/index.html.twig', [
            'listeproduits' => $listeproduits,
            'lastproduit'   => $lastProduit,
        ]);
    }
}