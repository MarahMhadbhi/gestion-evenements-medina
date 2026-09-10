<?php

namespace App\Repository;

use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inscription>
 *
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }

    public function save(Inscription $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Inscription $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Exemple de méthode de recherche personnalisée
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.type = :type')
            ->setParameter('type', $type)
            ->orderBy('i.dateInscription', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // Exemple de méthode pour les inscriptions récentes
    public function findRecentInscriptions(int $maxResults = 10): array
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.dateInscription', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByEvent(): array
{
    return $this->createQueryBuilder('i')
        ->select('e.titre as event, COUNT(i.id) as count')
        ->join('i.event', 'e')
        ->groupBy('e.id')
        ->orderBy('count', 'DESC')
        ->getQuery()
        ->getResult();
}

public function countByNationalite(): array
{
    return $this->createQueryBuilder('i')
        ->select('i.nationalite, COUNT(i.id) as count')
        ->groupBy('i.nationalite')
        ->orderBy('count', 'DESC')
        ->getQuery()
        ->getResult();
}

public function countBySecteurActivite(): array
{
    return $this->createQueryBuilder('i')
        ->select('i.secteur_Activite, COUNT(i.id) as count')
         ->groupBy('i.secteur_Activite')
        ->orderBy('count', 'DESC')
        ->getQuery()
        ->getResult();
}
}
