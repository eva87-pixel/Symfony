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

use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/admin")]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{

    #[Route("/admin/delete/{id}", name:"delete")]
    #[IsGranted('ROLE_ADMIN')]
    function delete(Request $request, $id,EntityManagerInterface $entityManager)
        {

            $produitRepository=$entityManager->getRepository(Produit::class);
            $produit=$produitRepository->find($id);
            $entityManager->remove($produit);
            $entityManager->flush();
            $session=$request->getSession();
            $session->getFlashBag()->add('message','le produit a été supprimé');
            $session->set('statut','success');
            return $this->redirect($this->generateUrl('liste'));
     }

    #[Route("/insert", name: "insert")]
    #[IsGranted('ROLE_ADMIN')]
    public function insert(Request $request, EntityManagerInterface $entityManager)
               {
               $produit = new Produit;
               $formProduit = $this->createForm(ProduitType::class, $produit);

               $formProduit->add('creer', SubmitType::class, array(
                   'label' => 'Insertion d\'un produit'));

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
                       $session->getFlashBag()->add('message',
                           'Vous devez choisir une image pour le produit');
                       $session->set('statut', 'danger');

                       return $this->redirect($this->generateUrl('insert'));

                   }
                   $entityManager->persist($produit);

                   $entityManager->flush();

                   $session=$request->getSession();
                   $session->getFlashBag()->add('message','un nouveau produit a été ajouté');
                   $session->set('statut','success');
                   return $this->redirect($this->generateUrl('liste'));

                   return $this->redirect($this->generateUrl('liste'));
               }
               return $this->render('Admin/create.html.twig',
                   array('my_form' => $formProduit->createView()));

           }

#[Route("/admin/update/{id}", name: "update")]
#[IsGranted('ROLE_ADMIN')]
public function update(Request $request, int $id, EntityManagerInterface $entityManager): Response
{
    $produitRepository = $entityManager->getRepository(Produit::class);
    $produit = $produitRepository->find($id);

    // Vérification : Si le produit est null, afficher un message d'erreur
    if (!$produit) {
        $this->addFlash('danger', 'Produit introuvable !');
        return $this->redirectToRoute('liste'); // Rediriger vers la liste des produits
    }

    $img = $produit->getLienImage(); //  Plus d'erreur ici car on est sûr que $produit n'est pas null

    // Création du formulaire avec l'entité Produit
    $formProduit = $this->createForm(ProduitType::class, $produit);
    $formProduit->handleRequest($request);

    if ($request->isMethod('post') && $formProduit->isValid()) {

        // Gestion de l'image uploadée
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

        // Mise à jour en base de données
        $entityManager->persist($produit);
        $entityManager->flush();

        $this->addFlash('success', 'Le produit a été mis à jour avec succès');
        return $this->redirectToRoute('liste');
    }

    return $this->render('Admin/create.html.twig', [
        'my_form' => $formProduit->createView()
    ]);
}


}