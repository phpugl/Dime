<?php

namespace Dime\TimetrackerBundle\Entity;

use Doctrine\ORM\EntityRepository as Base;
use Doctrine\ORM\QueryBuilder;

/**
 * EntityRepository
 *
 * Abstract repository to keep code clean.
 */
abstract class EntityRepository extends Base
{
    /**
     * @var QueryBuilder
     */
    protected $builder;

    /**
     * @abstract
     *
     * @param string            $text
     * @param QueryBuilder      $qb
     *
     * @return QueryBuilder
     */
    abstract public function search($text, QueryBuilder $qb = null);

    /**
     * @abstract
     *
     * @param                   $date
     * @param QueryBuilder      $qb
     *
     * @return QueryBuilder
     */
    abstract public function scopeByDate($date, QueryBuilder $qb = null);

    /**
     * Create a new current query builder
     *
     * @param $alias
     * @return EntityRepository
     */
    public function createCurrentQueryBuilder($alias)
    {
        $this->builder = $this->createQueryBuilder($alias);
        return $this;
    }

    /**
     * Return the current query builder
     *
     * @return QueryBuilder
     */
    public function getCurrentQueryBuilder()
    {
        return $this->builder;
    }

    /**
     * Set the current query builder
     *
     * @param QueryBuilder $qb
     * @return EntityRepository
     */
    public function setCurrentQueryBuilder(QueryBuilder $qb)
    {
        $this->builder = $qb;
        return $this;
    }

    /**
     * Scope by any field with value
     *
     * @param $field
     * @param $value
     * @param QueryBuilder $qb
     * @return EntityRepository
     */
    public function scopeByField($field, $value, QueryBuilder $qb = null)
    {
        if ($qb == null) {
            $qb = $this->builder;
        }

        $aliases = $qb->getRootAliases();
        $alias = array_shift($aliases);

        $qb->andWhere(
            $qb->expr()->eq($alias . '.' . $field, ':' . $field)
        );
        $qb->setParameter($field, $value);

        return $this;
    }

    /**
     * Add different filter option to query
     *
     * @param array        $filter
     * @param QueryBuilder $qb
     *
     * @return EntityRepository
     */
    public function filter(array $filter, QueryBuilder $qb = null)
    {
        if ($qb == null) {
            $qb = $this->builder;
        }

        if ($filter != null) {
            foreach ($filter as $key => $value) {
                switch($key) {
                    case 'date':
                        $this->scopeByDate($value, $qb);
                        break;
                    case 'search':
                        $this->search($value, $qb);
                        break;
                    default:
                        $this->scopeByField($key, $value, $qb);
                }
            }
        }
        return $this;
    }

    /**
     * Check if a join alias already taken. getRootAliases() doesn't do this for me.
     *
     * @param \Doctrine\ORM\QueryBuilder $qb, QueryBuilder you wanne check
     * @param                            $alias, alias string
     *
     * @return bool
     */
    public function existsJoinAlias(QueryBuilder $qb, $alias)
    {
        $result = false;

        foreach ($qb->getDQLPart('join') as $joins) {
            foreach ($joins as $j) {
                $join = $j->__toString();
                if (substr($join, strrpos($join, ' ') + 1) == $alias) {
                    $result = true;
                    break;
                }
            }
            if ($result) {
                break;
            }
        }

        return $result;
    }

    protected function scopeWithTags($tags, QueryBuilder $qb = null)
    {
        if (false == is_array($tags)) {
            $tags = array($tags);
        }
        foreach ($tags as $tag) {
            $qb = $this->scopeWithTag($tag, $qb);
        }
        return $this;
    }

    protected function scopeWithoutTags($tags, QueryBuilder $qb = null)
    {
        if (false == is_array($tags)) {
            $tags = array($tags);
        }
        foreach ($tags as $tag) {
            $qb = $this->scopeWithoutTag($tag, $qb);
        }
        return $this;
    }
}
