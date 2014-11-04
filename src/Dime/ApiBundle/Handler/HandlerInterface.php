<?php

interface HandlerInterface
{
    /**
     * Get a entity given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return HandlerInterface
     */
    public function get($id);

    /**
     * Get a list of Pages.
     *
     * @param int $limit the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 25, $offset = 0);

    /**
     * Post Page, creates a new Page.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return HandlerInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Page.
     *
     * @api
     *
     * @param PageInterface $page
     * @param array $parameters
     *
     * @return HandlerInterface
     */
    public function put(PageInterface $page, array $parameters);

    /**
     * Partially update a Page.
     *
     * @api
     *
     * @param PageInterface $page
     * @param array $parameters
     *
     * @return HandlerInterface
     */
    public function patch(PageInterface $page, array $parameters);
} 