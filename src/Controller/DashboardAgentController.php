<?php
// src/Controller/DashboardAgentController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted(User::ROLE_AGENT)]
class DashboardAgentController extends AbstractController
{
     /**
     * Affiche le tableau de bord d'administration
     * 
     * @Route("/agent", name="app_agent_dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('agent/dashboard.html.twig');
    } 
    
      /**
     * Redirection vers la liste des événements
     * 
     * @Route("/agent/events", name="app_agent_event_index")
     */
    public function eventRedirect(): Response
    {
        return $this->redirectToRoute('app_event_index');
    }

   /**
     * Redirection vers la liste des inscriptions
     * 
     * @Route("/agent/inscriptions", name="app_agent_inscription_index")
     */
    public function inscriptionRedirect(): Response
    {
        return $this->redirectToRoute('app_inscription_index');
    } 

    

}