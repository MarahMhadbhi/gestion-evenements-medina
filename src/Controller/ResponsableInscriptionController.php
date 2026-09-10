<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Repository\EventRepository;
use App\Repository\InscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[IsGranted(User::ROLE_RESPONSABLE)]
#[Route('/responsable/inscription')]
class ResponsableInscriptionController extends AbstractController
{
    // Liste toutes les inscriptions
    #[Route('/', name: 'responsable_inscription_index', methods: ['GET'])]
    public function index(InscriptionRepository $inscriptionRepository): Response
    {
        return $this->render('inscription/indexResponsable.html.twig', [
            'inscriptions' => $inscriptionRepository->findAll(),
        ]);
    }

    // Crée une nouvelle inscription
    #[Route('/new', name: 'responsable_inscription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,  ValidatorInterface $validator, EventRepository $eventRepository): Response
    {
       $inscription = new Inscription();
        // Fetch events for both GET and POST scenarios
        $events = $eventRepository->findAll();
        
        if ($request->isMethod('POST')) {
            // Récupération des données du formulaire
            $data = $request->request->all();

            $eventId = $data['event'];
            $event = $eventRepository->find($eventId);
            
            if (!$event) {
                $this->addFlash('error', 'Événement invalide!');
                return $this->redirectToRoute('responsable_inscription_new');
            }

            // Hydratation de l'objet
            $inscription->setNomClient($data['nomClient']);
            $inscription->setPrenomClient($data['prenomClient']);
            $inscription->setEmail($data['email']);
            $inscription->setTelephone($data['telephone']);
            $inscription->setDateNaissance(new \DateTime($data['dateNaissance']));
            $inscription->setSecteurActivite($data['secteur_Activite']);
            $inscription->setType($data['type']);
            $inscription->setDateInscription(new \DateTime());
            $inscription->setEvent($event);
            $inscription->setNationalite($data['nationalite']);
            
            // Validation
            $errors = $validator->validate($inscription);
            
            if (count($errors) > 0) {
                $this->addFlash('error', (string) $errors);
                return $this->redirectToRoute('responsable_inscription_new');
            }
            
            // Persistance
            $entityManager->persist($inscription);
            $entityManager->flush();
            
            $this->addFlash('success', 'Inscription créée avec succès!');
            return $this->redirectToRoute('responsable_inscription_index');
        }

        // Pass events to the template for both initial load and form errors
        return $this->render('inscription/newResponsable.html.twig' , [
            'events' => $events,
        ]);
    }

    

    // Modifie une inscription existante
    #[Route('/{id}/edit', name: 'responsable_inscription_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, 
    Inscription $inscription, 
    EntityManagerInterface $entityManager, 
    ValidatorInterface $validator,  
    EventRepository $eventRepository): Response
    {
       // Récupérer tous les événements pour la liste déroulante
    $events = $eventRepository->findAll();
    
    if ($request->isMethod('POST')) {
        // Récupération des données
        $data = $request->request->all();
        
        // Mise à jour de l'objet
        $inscription->setNomClient($data['nomClient']);
        $inscription->setPrenomClient($data['prenomClient']);
        $inscription->setEmail($data['email']);
        $inscription->setTelephone($data['telephone']);
        $inscription->setDateNaissance(new \DateTime($data['dateNaissance']));
        $inscription->setSecteurActivite($data['secteur_Activite']);
        $inscription->setType($data['type']);
        $inscription->setNationalite($data['nationalite']);
        
        // Mise à jour de l'événement
        $eventId = $data['event'];
        $newEvent = $eventRepository->find($eventId);
        
        if (!$newEvent) {
            $this->addFlash('error', 'Événement sélectionné invalide!');
            return $this->redirectToRoute('responsable_inscription_edit', ['id' => $inscription->getId()]);
        }
        
        $inscription->setEvent($newEvent);
        
        // Validation
        $errors = $validator->validate($inscription);
        
        if (count($errors) > 0) {
            $this->addFlash('error', (string) $errors);
            return $this->redirectToRoute('responsable_inscription_edit', ['id' => $inscription->getId()]);
        }
        
        // Sauvegarde
        $entityManager->flush();
        
        $this->addFlash('success', 'Inscription mise à jour avec succès!');
        return $this->redirectToRoute('responsable_inscription_index');
    }

    return $this->render('inscription/editResponsable.html.twig', [
        'inscription' => $inscription,
        'events' => $events, // Passer les événements au template
    ]);
}

    // Supprime une inscription
    #[Route('/{id}', name: 'responsable_inscription_delete', methods: ['POST'])]
    public function delete(Request $request, Inscription $inscription, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscription->getId(), $request->request->get('_token'))) {
            $entityManager->remove($inscription);
            $entityManager->flush();
            
            $this->addFlash('success', 'Inscription supprimée avec succès');
        }

        return $this->redirectToRoute('responsable_inscription_index');
    }

    #[Route('/{id}/badge', name: 'responsable_inscription_badge', methods: ['GET'])]
    public function badge(Inscription $inscription): Response
    {
        // Récupérer l'événement associé
        $event = $inscription->getEvent();

        return $this->render('inscription/badgeresponsable.html.twig', [
            'event' => [
                'titre' => $event->getTitre(),
                'dateDebut' => $event->getDateDebut(),
                'dateFin' => $event->getDateFin(),
                'lieu' => $event->getLieu(),
                'logo' => $event->getLogo(),
                'logoSponsor'=> $event->getLogoSponsor(),
            ],
            'inscription' => [
                'nomClient' => $inscription->getNomClient(),
                'prenomClient' => $inscription->getPrenomClient(),
                'type' => $inscription->getType(),
            ]
        ]);
    }
}

    

