<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 16/08/2017
 * Time: 10:57
 */

namespace LouvreBundle\Repository;


use Doctrine\ORM\EntityRepository;
use LouvreBundle\Entity\Order;

class ItemRepository extends EntityRepository
{
    public function getItemsDataFromOrder(Order $order)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.ticket', 't')
            ->addSelect('t')
            ->innerJoin('t.rate', 'r')
            ->addSelect('r')
            ->where('i.order = :order')
            ->setParameter('order', $order)
            ->select('r.name , COUNT(i) as number, t.price as unit_price,  SUM(t.price) as total_price')
            ->groupBy('r.name')
            ->getQuery()
            ->getResult();
    }

    public function getTotalAmountOfOrder(Order $order)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin('i.ticket', 't')
            ->addSelect('t')
            ->where('i.order = :order')
            ->setParameter('order', $order)
            ->select('SUM(t.price) as total_price')
            ->getQuery()
            ->getSingleResult();

    }
}