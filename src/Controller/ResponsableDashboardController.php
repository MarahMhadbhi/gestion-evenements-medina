<?php
// src/Controller/ResponsableDashboardController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted(User::ROLE_RESPONSABLE)]
class ResponsableDashboardController extends AbstractController
{
     /**
     * Affiche le tableau de bord de responsable
     * 
     * @Route("/responsable", name="app_responsable_dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('responsable/dashboard.html.twig');
    } 

   
    
      /**
     * Redirection vers la liste des événements
     * 
     * @Route("/responsable/events", name="app_responsable_event_index")
     */
      public function eventRedirect(): Response
    {
        return $this->redirectToRoute('app_respo_event_index');
    }

   /**
     * Redirection vers la liste des inscriptions
     * 
     * @Route("/responsable/inscriptions", name="app_respo_inscription_index")
     */
     public function inscriptionRedirect(): Response
    {
        return $this->redirectToRoute('responsable_inscription_index');
    } 


}
