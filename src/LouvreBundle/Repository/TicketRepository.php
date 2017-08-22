<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 16/08/2017
 * Time: 10:41
 */

namespace LouvreBundle\Repository;


use Doctrine\ORM\EntityRepository;

class TicketRepository extends EntityRepository
{
    public function getTicketByRateAndDuration($rate, $duration){
        return $this->createQueryBuilder('t')
            ->where('t.rate = :rate')
            ->andWhere('t.duration = :duration')
            ->setParameter('rate', $rate)
            ->setParameter('duration', $duration)
            ->getQuery()
            ->getResult();
    }

}