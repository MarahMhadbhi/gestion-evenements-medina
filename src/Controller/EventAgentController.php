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

#[IsGranted(User::ROLE_AGENT)]
#[Route('/agent/event')]
class EventAgentController extends AbstractController
{
    #[Route('/', name: 'app_agent_event_index', methods: ['GET'])]
    public function indexagent(EventRepository $eventRepository): Response
    {
        return $this->render('event/agentIndex.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }
}