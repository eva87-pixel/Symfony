<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class RegisterController
 *
 * Ce contrôleur gère l'inscription (enregistrement) des nouveaux utilisateurs.
 *
 * @package App\Controller
 */
class RegisterController extends AbstractController
{
    /**
     * Enregistre un nouvel utilisateur.
     *
     * Cette méthode crée un formulaire d'inscription basé sur l'API FormBuilder,
     * récupère les données soumises, hache le mot de passe, affecte les rôles sélectionnés
     * et persiste le nouvel utilisateur dans la base de données.
     * En cas de succès, l'utilisateur est redirigé vers la page de connexion.
     *
     * @param Request $request La requête HTTP.
     * @param UserPasswordHasherInterface $passwordHasher Le service de hachage des mots de passe.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités pour persister l'utilisateur.
     *
     * @return \Symfony\Component\HttpFoundation\Response La réponse HTTP générée.
     */
    #[Route('/register', name: 'register')]
    #[IsGranted('ROLE_ADMIN')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): \Symfony\Component\HttpFoundation\Response {
        // Création du formulaire d'inscription
        $form = $this->createFormBuilder()
                ->add('username')
                ->add('password', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'required' => true,
                        'first_options' => ['label' => 'Mot de passe'],
                        'second_options' => ['label' => 'Confirmation Mot de passe'],
                ])
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'ROLE_USER' => 'ROLE_USER',
                        'ROLE_ADMIN' => 'ROLE_ADMIN',
                        'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                    ],
                    'multiple' => true
                ])
                ->add('register', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-success',
                    ]
                ])
                ->getForm();

        // Traitement de la requête pour le formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide, on crée et persiste l'utilisateur
        if ($request->isMethod('post') && $form->isValid()) {
            $data = $form->getData();
            $user = new User();
            $user->setUsername($data['username']);
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $data['password']
                )
            );
            $user->setRoles($data['roles']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('app_login'));
        }

        // Rendu du formulaire d'inscription
        return $this->render('register/index.html.twig', [
            'my_form' => $form->createView()
        ]);
    }
}