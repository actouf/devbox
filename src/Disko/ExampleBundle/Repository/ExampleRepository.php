<?php

namespace Disko\ExampleBundle\Repository;

use Disko\CoreBundle\Repository\BaseRepository;

/**
 * Class ExampleRepository
 *
 * @package Disko\ExampleBundle\Repository
 */
class ExampleRepository extends BaseRepository
{

    /**
     * Get all user query, using for pagination
     *
     * @param array $filters
     *
     * @return mixed
     */
    public function queryForSearch($filters = array())
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->orderBy('e.name', 'asc');

        if (count($filters) > 0) {
            foreach ($filters as $key => $filter) {

                $qb->andWhere('e.'.$key.' LIKE :'.$key);
                $qb->setParameter($key, '%'.$filter.'%');
            }
        }

        return $qb->getQuery();
    }

    /**
     * Find one for edit profile
     *
     * @param $id
     * @return mixed
     */
    public function findOneToEdit($id)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.id = :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }
}
