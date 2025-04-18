<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AccessDeniedHandler
 *
 * Ce gestionnaire d'accès refusé intercepte les tentatives d'accès non autorisées aux pages protégées.
 * Il affiche un message flash indiquant l'absence de droits suffisants et redirige l'utilisateur vers la page de connexion.
 *
 * @package App\Security
 */
class AccessDeniedHandler extends AbstractController implements AccessDeniedHandlerInterface
{
    /**
     * Gère l'accès refusé.
     *
     * Cette méthode est appelée lorsque l'utilisateur tente d'accéder à une page pour laquelle il n'a pas les droits nécessaires.
     * Elle ajoute un message d'erreur dans le flash bag et redirige l'utilisateur vers la route 'app_login'.
     *
     * @param Request $request La requête HTTP.
     * @param AccessDeniedException $accessDeniedException L'exception d'accès refusé.
     *
     * @return Response La réponse HTTP qui redirige vers la page de connexion.
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        $session = $request->getSession();
        $session->getFlashBag()->add('message', 'Vous n\'avez pas les droits suffisants pour accéder à cette page');
        $session->set('statut', 'danger');

        return $this->redirectToRoute('app_login');
    }
}