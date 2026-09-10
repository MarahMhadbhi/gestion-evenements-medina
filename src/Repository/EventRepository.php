<?php
// src/Repository/EventRepository.php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    // Exemple de méthode personnalisée pour trouver les événements à venir
    public function findUpcomingEvents(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.dateDebut >= :today')
            ->setParameter('today', new \DateTime('today'))
            ->orderBy('e.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Exemple de méthode pour trouver les événements par lieu
    public function findByLocation(string $location): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.lieu LIKE :location')
            ->setParameter('location', '%' . $location . '%')
            ->getQuery()
            ->getResult();
    }

    // Ajouter ici d'autres méthodes de requête personnalisées...
}