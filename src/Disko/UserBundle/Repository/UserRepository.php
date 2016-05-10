<?php

/**
 * Repository
 *
 * @author Adrien Jerphagnon <adrien.j@disko.fr>
 */

namespace Disko\UserBundle\Repository;

use Disko\CoreBundle\Repository\BaseRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class UserRepository
 *
 * @package Disko\UserBundle\Repository
 */
class UserRepository extends BaseRepository
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
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->orderBy('u.lastName', 'asc');

        if (count($filters) > 0) {
            foreach ($filters as $key => $filter) {
                $qb->andWhere('u.'.$key.' LIKE :'.$key);
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
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }


    /**
     * Get User by week
     *
     * @param int $nbWeek
     */
    public function getUsersByWeek($nbWeek = 20)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('semaine', 'semaine');
        $rsm->addScalarResult('annee', 'annee');
        $rsm->addScalarResult('nb', 'nb');

        $sql = 'SELECT YEAR(u.created) as annee, DATE_FORMAT(u.created, "%u") AS semaine , COUNT(u.id) AS nb
         FROM dk_user u
         GROUP BY YEAR(u.created), semaine
         ORDER BY  u.created DESC
         LIMIT 0,'.$nbWeek
        ;

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        $results = $query->getResult();

        $results = array_reverse($results);

        return $results;
    }
}
