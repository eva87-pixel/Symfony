<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\Distributeur;

class ListeProduitsController extends AbstractController
{
    #[Route("/eager",name: "eager")]
      public function eager(EntityManagerInterface $entityManager)
        {
        $produitsRepository=$entityManager->getRepository(Produit::class);
        $listeProduits=$produitsRepository->findAll();
            return $this->render('liste_produits/eager.html.twig', [
                'listeproduits' => $listeProduits,

            ]);
        }

      #[Route("/distrib",name: "distributeurs")]
      public function listedistributeur(EntityManagerInterface $entityManager)
      {
        $repositoryDistributeurs=$entityManager->
    getRepository(Distributeur::class);
        $distributeurs = $repositoryDistributeurs->findAll();

      return $this->render('liste_produits/distributeurs.html.twig', array(
             'distributeurs' => $distributeurs));
    }

    #[Route('/liste', name: 'liste')]
    public function liste(EntityManagerInterface $entityManager)
    {
        // Récupération du repository de Produit
        $produitsrepository = $entityManager->getRepository(Produit::class);
        
        // Récupération de tous les produits
        $listeproduits = $produitsrepository->findAll();
        $lastProduit=$produitsrepository->getLastProduit();

        // Transmission des produits au template Twig
        return $this->render('liste_produits/index.html.twig', [
            'listeproduits' => $listeproduits,
            'lastproduit' => $lastProduit,

        ]);
    }
}