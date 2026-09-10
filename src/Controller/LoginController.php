<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Form\LoginType;

class LoginController extends AbstractController
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    #[Route(path: '/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Vérifie si l'utilisateur est déjà authentifié
        if ($this->getUser()) {
            return $this->redirectToRoleDashboard();
        }

        // Récupère les erreurs et le dernier email
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmail = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class);

        return $this->render('Login.html.twig', [
        'loginForm' => $form->createView(),
        'last_email' => $lastEmail,
        'error' => $error // ⚠️ N'oubliez pas d'ajouter l'erreur au template
    ]);

    }

    #[Route(path: '/logout', name: 'app_logout' ,  methods: ['GET', 'POST'])]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode sera interceptée par le système de déconnexion');
    }

    /**
     * Redirige l'utilisateur vers le dashboard approprié selon son rôle
     */
    private function redirectToRoleDashboard(): RedirectResponse
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $roles = $user->getRoles();
        

        if (in_array('ROLE_ADMIN', $roles)) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        if (in_array('ROLE_AGENT', $roles)) {
            return $this->redirectToRoute('app_agent_dashboard');
        }

         if (in_array('ROLE_RESPONSABLE', $roles)) {
            return $this->redirectToRoute('app_responsable_dashboard');
        }

        // Redirection par défaut si aucun rôle ne correspond
        return $this->redirectToRoute('app_home');
    }


    #[Route('/dashboard_redirect', name: 'app_dashboard_redirect')]
     public function dashboardRedirect(): RedirectResponse
     {
        return $this->redirectToRoleDashboard();
     }



     #[Route(path: '/forgot-password', name: 'app_forgot_password', methods: ['GET'])]
    public function forgotPassword(): Response
    {
        return $this->render('forgot_password.html.twig');
    }
     
}