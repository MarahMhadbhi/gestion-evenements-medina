<?php
// src/Controller/DashboardAdminController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardAdminController extends AbstractController
{
    /**
     * Affiche le tableau de bord d'administration
     * 
     * @Route("/admin", name="app_admin_dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * Redirection vers la liste des événements
     * 
     * @Route("/admin/events", name="app_admin_event_index")
     */
    public function eventRedirect(): Response
    {
        return $this->redirectToRoute('app_event_index');
    }

    /**
     * Redirection vers la liste des utilisateurs
     * 
     * @Route("/admin/users", name="app_admin_user_index")
     */
    public function userRedirect(): Response
    {
        return $this->redirectToRoute('app_user_index');
    }

    /**
     * Redirection vers la liste des inscriptions
     * 
     * @Route("/admin/inscriptions", name="app_admin_inscription_index")
     */
    public function inscriptionRedirect(): Response
    {
        return $this->redirectToRoute('app_inscription_index');
    }

   
}
