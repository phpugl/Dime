<?php

namespace Dime\TimetrackerBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetaData,
    Doctrine\ORM\Query\Filter\SQLFilter;

class ActivityByDateFilter extends SQLFilter
{
  /**
   * List with possible Fields
   * @var array
   */
  protected $fieldList = array();

  /**
   * Gets the SQL query part to add to a query.
   *
   * @return string The constraint SQL if there is available, empty string otherwise
   */
  public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
  {

    return $targetTableAlias.'.locale = ' . $this->getParameter('locale');
  }
}
