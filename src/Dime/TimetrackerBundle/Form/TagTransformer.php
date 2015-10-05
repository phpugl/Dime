<?php

namespace Dime\TimetrackerBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dime\TimetrackerBundle\Entity\Tag;
use Dime\TimetrackerBundle\Entity\User;

class TagTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var User
     */
    private $user;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $em, User $user)
    {
        $this->em = $em;
        $this->user = $user;
    }

    public function transform($value)
    {
        if (null === $value) {
            return "";
        }

        return $value;
    }

    /**
     * Called on bind
     *
     * @param mixed $value
     * @return mixed|void
     */
    public function reverseTransform($value)
    {
        $result = array();
        if (null == $value) {
            return $result;
        }

        if (is_string($value)) {
            $value = explode(' ', $value);
        }

        $tagNames = array();
        $tagIds = array();
        foreach ($value as $tag) {
            if (is_numeric($tag)) {
                $tagIds[] = $tag;
            } else {
                $tagNames[] = $tag;
            }
        }

        $repository = $this->em->getRepository('DimeTimetrackerBundle:Tag');

        if (!empty($tagIds)) {
            $qb = $repository->createQueryBuilder('t');
            $qb->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->in('t.id', ':ids'),
                    $qb->expr()->eq('t.user', ':user')
                )
            );
            $qb->setParameters(array('ids' => $tagIds, 'user' => $this->user->getId()));

            $dbResults = $qb->getQuery()->getResult();
            foreach ($dbResults as $tag) {
                $result[] = $tag;
            }
        }

        if (!empty($tagNames)) {
            $qb = $repository->createQueryBuilder('t');
            $qb->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->in('t.name', ':tags'),
                    $qb->expr()->eq('t.user', ':user')
                )
            );
            $qb->setParameters(array('tags' => $tagNames, 'user' => $this->user->getId()));

            $existingTags = array();
            $dbResults = $qb->getQuery()->getResult();
            foreach ($dbResults as $tag) {
                $result[] = $tag;
                $existingTags[] = $tag->getName();
            }

            $missingTags = array_diff($tagNames, $existingTags);
            if (count($missingTags) > 0) {
                foreach ($missingTags as $name) {
                    $name = trim($name);
                    if (!empty($name)) {
                        $newTag = new Tag();
                        $newTag->setName($name);
                        $newTag->setUser($this->user);
                        $result[] = $newTag;
                    }
                }
            }
        }
        return $result;
    }

}
