<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

/**
 * Class ApiKeyAuthenticator
 *
 * Cet authentificateur vérifie la présence d'un token d'API dans l'en-tête HTTP "X-AUTH-TOKEN"
 * et authentifie l'utilisateur correspondant en utilisant le UserBadge.
 * Il implémente les méthodes nécessaires pour gérer la réussite et l'échec de l'authentification.
 *
 * @package App\Security
 */
class ApiKeyAuthenticator extends AbstractAuthenticator
{
    /**
     * Constructeur.
     *
     * Utilise la promotion de propriété pour injecter l'EntityManager.
     *
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités permettant d'accéder aux utilisateurs.
     */
    public function __construct(public EntityManagerInterface $entityManager)
    {
    }

    /**
     * Détermine si cet authentificateur doit être utilisé pour la requête.
     *
     * Ici, l'authentificateur est activé si l'en-tête "X-AUTH-TOKEN" est présent dans la requête.
     *
     * @param Request $request La requête HTTP.
     *
     * @return bool|null Retourne true si l'en-tête est présent, sinon false (ou null pour passer à l'authentificateur suivant).
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    /**
     * Authentifie la requête en utilisant le token présent dans l'en-tête.
     *
     * Si le token est absent, une exception CustomUserMessageAuthenticationException est levée.
     * Sinon, un SelfValidatingPassport est retourné, basé sur le UserBadge qui recherche l'utilisateur correspondant.
     *
     * @param Request $request La requête HTTP.
     *
     * @return Passport Le passport validant l'authentification.
     *
     * @throws CustomUserMessageAuthenticationException Si le token est manquant.
     */
    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get('X-AUTH-TOKEN');

        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        return new SelfValidatingPassport(
            new UserBadge($apiToken, function ($apiToken) {
                return $this->entityManager->getRepository(User::class)
                    ->findOneBy(['apiToken' => $apiToken]);
            })
        );
    }

    /**
     * Gère la réussite de l'authentification.
     *
     * En cas de succès, cette méthode retourne null pour laisser le reste de la requête se poursuivre normalement.
     *
     * @param Request $request La requête HTTP.
     * @param TokenInterface $token Le token authentifié.
     * @param string $firewallName Le nom du firewall en cours.
     *
     * @return Response|null Retourne null pour continuer la requête.
     */
    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?Response {
        // En cas de succès, on laisse la requête se poursuivre
        return null;
    }

    /**
     * Gère l'échec de l'authentification.
     *
     * En cas d'échec, cette méthode construit et retourne une réponse JSON avec un message d'erreur,
     * en utilisant le code HTTP 401 Unauthorized.
     *
     * @param Request $request La requête HTTP.
     * @param AuthenticationException $exception L'exception générée lors de l'authentification.
     *
     * @return Response La réponse JSON indiquant l'erreur d'authentification.
     */
    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): ?Response {
        $data = [
            // On formate le message d'erreur en remplaçant les paramètres éventuels dans le message de l'exception
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}