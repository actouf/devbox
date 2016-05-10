<?php

namespace Disko\ExampleBundle\Services;

use Disko\CoreBundle\Services\BaseService;

use Disko\ExampleBundle\Entity\Example;

/**
 * Class ExampleService
 * `
 * Object manager of example
 *
 * @package Disko\PageBundle\Services
 */
class ExampleService extends BaseService
{
    /**
     * @var ExampleRepository
     */
    protected $exampleRepository;

    /**
     * Save a example
     *
     * @param Example $example
     */
    public function save(Example $example)
    {
        $example->setUpdated(new \DateTime());
        $this->getEntityManager()->persist($example);
        $this->getEntityManager()->flush();

        return $example;
    }

    /**
     * Remove one example
     *
     * @param Example $example
     */
    public function remove(Example $example)
    {
        $this->getEntityManager()->remove($example);
        $this->getEntityManager()->flush();
    }

    /**
     * Get all example
     *
     * @param array $filters
     *
     * @return mixed
     */
    public function getQueryForSearch($filters = array())
    {
        return $this->exampleRepository->queryForSearch($filters);
    }

    /**
     * Find example by slug for edit profil
     *
     * @param string $id
     *
     * @return mixed
     */
    public function findOneToEdit($id)
    {
        return $this->exampleRepository->findOneToEdit($id);
    }

    /**
     * Find all
     *
     * @return mixed
     */
    public function findAll()
    {
        return $this->exampleRepository->findAll();
    }

    /**
     * Find one by
     *
     * @param array $filters
     * @return mixed
     */
    public function findOneBy($filters = array())
    {
        return $this->exampleRepository->findOneBy($filters);
    }
}
