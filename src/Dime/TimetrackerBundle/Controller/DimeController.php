<?php

namespace Dime\TimetrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use FOS\RestBundle\View\View;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DimeController extends Controller
{
    protected $currentUser = null;

    /**
     * Create a rest view
     *
     * @param null $data
     * @param int  $statuscode
     *
     * @return \FOS\RestBundle\View\View
     */
    protected function createView($data = null, $statuscode = 200)
    {
        $view = new View($data, $statuscode);

        return $view;
    }

    /**
     * Generate paginated output
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param int                        $limit
     * @param int                        $offset
     *
     * @return \FOS\RestBundle\View\View
     */
    protected function paginate(\Doctrine\ORM\QueryBuilder $qb, $limit = null, $offset = null)
    {
        if ($offset != null && intval($offset) > 0) {
            $qb->setFirstResult($offset);
        }
        if ($limit != null && intval($limit) > 0) {
            $qb->setMaxResults($limit);
        }

        $paginator = new Paginator($qb, $fetchJoinCollection = true);

        $view = $this->createView($paginator->getQuery()->getResult());
        $view->setHeader('X-Pagination-Total-Results', count($paginator));

        return $view;
    }

    /**
     * Get the current user
     *
     * @return \Dime\TimetrackerBundle\Entity\User
     */
    protected function getCurrentUser()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof \Symfony\Component\Security\Core\User\UserInterface) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException(
                'This user does not have access to this section.');
        }

        return $user;
    }

    /**
     * Clean up filter array
     *
     * @param array $filter
     * @param array $allowed
     *
     * @return array clean filter array
     */
    protected function cleanFilter(array $filter, array $allowed) {
        $result = array();

        foreach ($filter as $key => $name) {
            if (in_array($key, $allowed)) {
                $result[$key] = $name;
            }
        }

        return $result;
    }

    /**
     * handle tags in input data array, if we get them as array of text instead of array of int
     *
     * @param array $data
     * @return array
     */
    protected function handleTagsInput($data)
    {
        if (isset($data['tags'])) {
            $tagRepository = $this->getTagRepository();
            $tagRepository->createQueryBuilder('t');
            $data['tags'] = $tagRepository->getTagIds($data['tags'], $this->getCurrentUser());
        }

        return $data;
    }

    /**
     * get tag repository
     *
     * @return TagRepository
     */
    protected function getTagRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Tag');
    }

    /**
     * save form
     *
     * @param Form  $form
     * @param array $data
     *
     * @return View
     */
    protected function saveForm(Form $form, $data)
    {
        // clean array from non existing keys to avoid extra data
        foreach ($data as $key => $value) {
            if (!$form->has($key)) {
                unset($data[$key]);
            }
        }

        // bind data to form
        $form->bind($data);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $entity = $form->getData();

            if (is_object($entity) && method_exists($entity, 'setUser')) {
                $entity->setUser($this->getCurrentUser());
            }

            // save change to database
            $em->persist($entity);
            $em->flush();
            $em->refresh($entity);

            // push back the new object
            $view = $this->createView($entity, 200);
        } else {
            $errors = array();

            $text = '';
            foreach ($form->getErrors() as $error) {
              if (!empty($text)) $text .= "\n";
              $text .= $error->getMessage();
            }

            if (!empty($text)) {
              $errors['global'] = $text;
            }

            foreach ($form as $child) {
              if ($child->hasErrors()) {
                $text = '';
                foreach ($child->getErrors() as $error) {
                  if (!empty($text)) $text .= "\n";
                  $text .= $error->getMessage();
                }

                $errors[$child->getName()] = $text;
              }
            }
            // return error string from form
            $view = $this->createView(array('errors' => $errors), 400);
        }

        return $view;
    }
}
