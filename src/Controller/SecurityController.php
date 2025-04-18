<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 *
 * Ce contrôleur gère les actions de connexion et de déconnexion.
 *
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion et gère les erreurs d'authentification.
     *
     * Si une erreur d'authentification se produit, elle est transmise à la vue.
     * Le dernier nom d'utilisateur saisi est également fourni pour pré-remplir le champ.
     *
     * @param AuthenticationUtils $authenticationUtils Service pour récupérer les erreurs et le dernier nom d'utilisateur saisi
     *
     * @return Response Retourne la réponse qui affiche le template de login
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // Récupère la dernière erreur d'authentification, s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * Déconnexion de l'utilisateur.
     *
     * Cette méthode n'est jamais appelée directement, car la déconnexion est gérée
     * par le système de sécurité via la configuration du firewall.
     *
     * @return void
     *
     * @throws \LogicException Cette méthode est interceptée par le firewall pour la déconnexion.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}