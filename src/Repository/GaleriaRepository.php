<?php

namespace App\Repository;

use App\Entity\Galeria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Galeria|null find($id, $lockMode = null, $lockVersion = null)
 * @method Galeria|null findOneBy(array $criteria, array $orderBy = null)
 * @method Galeria[]    findAll()
 * @method Galeria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GaleriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Galeria::class);
    }

    public function BuscarTodasLasFotos(){
        return $this->getEntityManager()
            ->createQuery('select galeria.id, galeria.title, galeria.description, 
            galeria.image,usuario.nombre  from App:Galeria galeria 
            JOIN galeria.user usuario order by galeria.title asc');//query para buscar en la galeria y ordenarlos por 
    }

    // /**
    //  * @return Galeria[] Returns an array of Galeria objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Galeria
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
