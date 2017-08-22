<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 16/08/2017
 * Time: 10:29
 */

namespace LouvreBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CountryRepository extends EntityRepository
{
    public function getByAlphabeticalOrder()
    {
        return $this
            ->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC');
    }
}