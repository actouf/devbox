<?php

/**
 * Model for search
 *
 * @author Adrien Jerphagnon <adrien.j@disko.fr>
 */

namespace Disko\ExampleBundle\Form\Model;

/**
 * Class SearchExample
 *
 * @package Disko\ExampleBundle\Form\Model
 */
class SearchExample
{
    /**
     * Id
     *
     * @var string
     */
    protected $id;

    /**
     * Name
     *
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get search data
     *
     * @return array
     */
    public function getSearchData()
    {
        $tab = array();

        if (!empty($this->id)) {
            $tab['id'] = $this->id;
        }
        if (!empty($this->name)) {
            $tab['name'] = $this->name;
        }

        return $tab;
    }
}
