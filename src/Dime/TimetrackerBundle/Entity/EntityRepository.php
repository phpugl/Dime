<?php

namespace Dime\TimetrackerBundle\Entity;

use Doctrine\ORM\EntityRepository as Base;

/**
 * EntityRepository
 *
 * Abstract repository to keep code clean.
 */
class EntityRepository extends Base
{

    /**
     * find all entities and converts them into arrays
     *
     * @return array
     */
    public function toArray()
    {
        $data = array();
        
        $entities = $this->findAll();
        foreach ($entities as $entity)
        {
            $data[] = $entity->toArray();
        }

        return $data;
    }
}
