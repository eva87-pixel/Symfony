<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class CustomAuthenticator
 *
 * Cet authentificateur personnalisé gère l'authentification par formulaire.
 * Il récupère les données (username, password et token CSRF) depuis la requête et
 * construit un Passport pour authentifier l'utilisateur.
 * En cas d'authentification réussie, il redirige l'utilisateur vers la page cible ou
 * vers la route 'liste'. Si la connexion échoue, un message d'erreur est renvoyé sous forme JSON.
 *
 * @package App\Security
 */
class CustomAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    /**
     * La route de connexion.
     */
    public const LOGIN_ROUTE = 'app_login';

    /**
     * CustomAuthenticator constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator Permet de générer des URLs à partir des routes.
     */
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * Authentifie la requête en extrayant les données de connexion via le payload.
     *
     * Récupère le username, le password et le token CSRF depuis le payload de la requête,
     * et construit un Passport en utilisant un UserBadge, des PasswordCredentials,
     * un CsrfTokenBadge ainsi qu'un RememberMeBadge.
     *
     * @param Request $request La requête HTTP contenant les données d'authentification.
     *
     * @return Passport Le passport validant l'authentification.
     *
     * @throws \Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException
     *         Si le token API n'est pas fourni.
     */
    public function authenticate(Request $request): Passport
    {
        $username = $request->getPayload()->getString('username');

        // Enregistre le dernier username dans la session pour pré-remplir le champ lors d'une erreur
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    /**
     * Gère la réussite de l'authentification.
     *
     * Si un chemin cible a été sauvegardé dans la session, l'utilisateur sera redirigé vers celui-ci.
     * Sinon, il sera redirigé vers la route 'liste'.
     *
     * @param Request $request La requête HTTP.
     * @param TokenInterface $token Le token d'authentification.
     * @param string $firewallName Le nom du firewall.
     *
     * @return Response|null Une réponse de redirection ou null pour poursuivre le traitement.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Redirige vers la route 'liste' par défaut si aucune cible n'est définie.
        return new RedirectResponse($this->urlGenerator->generate('liste'));
    }

    /**
     * Retourne l'URL de connexion.
     *
     * Génère l'URL de la page de connexion à partir de la constante LOGIN_ROUTE.
     *
     * @param Request $request La requête HTTP.
     *
     * @return string L'URL de connexion.
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}