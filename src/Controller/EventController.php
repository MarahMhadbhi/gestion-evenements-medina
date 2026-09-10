<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_ADMIN')]
#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

     #[Route('/agent', name: 'app_event_agent_index', methods: ['GET'])]
    public function agentIndex(EventRepository $eventRepository): Response
    {
         return $this->render('event/agentIndex.html.twig', [
           'events' => $eventRepository->findAll(),
         ]);
    }
     
    
     #[Route('/responsable', name: 'app_event_responsable_index', methods: ['GET'])]
    public function responsableIndex(EventRepository $eventRepository): Response
    {
         return $this->render('event/responsableIndex.html.twig', [
           'events' => $eventRepository->findAll(),
         ]);
    }





    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du fichier logo
            $logoFile = $form->get('logoFile')->getData();
            if ($logoFile) {
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();

                 $sponsorLogosFiles = $request->files->get('logoFile');
                $uploadedSponsorLogos = [];


            
            foreach ($sponsorLogosFiles as $sponsorFile) {
                if ($sponsorFile) {
                    $newFilename = $this->uploadFile($sponsorFile, $slugger, $this->getParameter('sponsors_directory'));
                    $uploadedSponsorLogos[] = $newFilename;
                }
            }

              $existingSponsorLogos = $event->getLogoSponsor() ?? [];
            $event->setLogoSponsor(array_merge($existingSponsorLogos, $uploadedSponsorLogos));


                try {
                    $logoFile->move(
                        $this->getParameter('logos_directory'),
                        $newFilename
                    );
                     $event->setLogo($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement du logo');
                }

                
            }

            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', 'Événement créé avec succès');
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

   #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        $originalSponsorLogos = $event->getLogoSponsor() ? $event->getLogoSponsor() : [];
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du fichier logo
            $logoFile = $form->get('logoFile')->getData();
            if ($logoFile) {
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();
            
                try {
                    $logoFile->move(
                        $this->getParameter('logos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception
                }

                // Supprimer l'ancien logo si existe
                $oldLogo = $event->getLogo();
                if ($oldLogo) {
                    unlink($this->getParameter('logos_directory').'/'.$oldLogo);
                }

                $event->setLogo($newFilename);
            }

             $sponsorLogosFiles = $form->get('logoFile')->getData();
             $uploadedSponsorLogos = [];


             if ($sponsorLogosFiles) {
            foreach ($sponsorLogosFiles as $sponsorFile) {
                if ($sponsorFile) {
                    $newFilename = $this->uploadFile(
                        $sponsorFile, 
                        $slugger, 
                        $this->getParameter('sponsors_directory')
                    );
                    $uploadedSponsorLogos[] = $newFilename;
                }
            }

             $existingSponsorLogos = $event->getLogoSponsor() ?? [];
              $event->setLogoSponsor(array_merge($existingSponsorLogos, $uploadedSponsorLogos));
           }

            $sponsorLogosToRemove = $request->request->all('sponsorLogosToRemove');
            if ($sponsorLogosToRemove) {
            $currentSponsorLogos = $event->getLogoSponsor() ?? [];

              $newSponsorLogos = array_filter($currentSponsorLogos, function($logo) use ($sponsorLogosToRemove) {
                return !in_array($logo, $sponsorLogosToRemove);
            });

             $event->setLogoSponsor(array_values($newSponsorLogos)); // Réindexe le tableau

              foreach ($sponsorLogosToRemove as $filename) {
                $filePath = $this->getParameter('sponsors_directory').'/'.$filename;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }




            $entityManager->flush();
             $this->addFlash('success', 'Événement mis à jour avec succès');

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
            'existingSponsorLogos' => $originalSponsorLogos
        ]);
    }  
   

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_event_'.$event->getId(), $request->request->get('_token'))) {
            // Supprimer le fichier logo
            $logo = $event->getLogo();
            if ($logo) {
                unlink($this->getParameter('logos_directory').'/'.$logo);
            }

            $entityManager->remove($event);
            $entityManager->flush();

            // Ajoutez ce message flash si vous voulez afficher un feedback
            $this->addFlash('success', 'L\'événement a été supprimé avec succès.');
            } else {
                  $this->addFlash('error', 'Token CSRF invalide.');
                }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }


    private function uploadFile($file, SluggerInterface $slugger, string $directory): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($directory, $newFilename);
        } catch (FileException $e) {
            $this->addFlash('error', 'Erreur lors du téléchargement du fichier');
        }

        return $newFilename;
    }

}

