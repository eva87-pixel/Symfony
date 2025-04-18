<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

/**
 * ErrorController
 *
 * Ce contrôleur intercepte les exceptions et affiche une page d'erreur personnalisée.
 */
class ErrorController extends AbstractController
{
    /**
     * Affiche une page d'erreur personnalisée.
     *
     * Cette méthode est appelée pour rendre la vue d'erreur, en extrayant le message
     * de l'exception via FlattenException et en le passant au template.
     *
     * @param FlattenException $exception L'exception levée
     *
     * @return Response La réponse qui affiche la page d'erreur
     */
    #[Route('/error', name: 'error')]
    public function show(FlattenException $exception): Response
    {
        $message = $exception->getMessage();

        return $this->render('Exception/index.html.twig', [
            'message' => $message,
        ]);
    }
}