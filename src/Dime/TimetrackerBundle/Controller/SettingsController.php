<?php

namespace Dime\TimetrackerBundle\Controller;

use Dime\TimetrackerBundle\Entity\Setting;
use Dime\TimetrackerBundle\Entity\SettingRepository;
use Dime\TimetrackerBundle\Form\SettingType;
use FOS\RestBundle\View\View;

class SettingsController extends DimeController
{
    /**
     * @var array allowed filter keys
     */
    protected $allowed_filter = array('namespace', 'name', 'user');

    /**
     * get service repository
     *
     * @return SettingRepository
     */
    protected function getSettingRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Setting');
    }

    /**
     * get a list of all settings
     *
     * [GET] /settings
     *
     * @return View
     */
    public function getSettingsAction()
    {
        $settings = $this->getSettingRepository();

        $settings->createCurrentQueryBuilder('sg');

        // Filter
        $filter = $this->getRequest()->get('filter');
        if ($filter) {
            $settings->filter($this->cleanFilter($filter, $this->allowed_filter));
        }

        // Scope by current user
        if (!isset($filter['user'])) {
            $settings->scopeByField('user', $this->getCurrentUser()->getId());
        }

        // Sort by name
        $settings->getCurrentQueryBuilder()->addOrderBy('sg.namespace', 'ASC');
        $settings->getCurrentQueryBuilder()->addOrderBy('sg.name', 'ASC');

        // Pagination
        return $this->paginate($settings->getCurrentQueryBuilder(),
            $this->getRequest()->get('limit'),
            $this->getRequest()->get('offset')
        );
    }

    /**
     * get a service by its id
     * [GET] /settings/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function getSettingAction($id)
    {
        // find service
        $service = $this->getSettingRepository()->find($id);

        // check if it exists
        if ($service) {
            // send array
            $view = $this->createView($service);
        } else {
            // service does not exists send 404
            $view = $this->createView("Setting does not exists.", 404);
        }

        return $view;
    }

    /**
     * create a new service
     * [POST] /settings
     *
     * @return View
     */
    public function postSettingsAction()
    {
        // create new service
        $service = new Setting();

        // create service form
        $form = $this->createForm(new SettingType(), $service);

        // convert json to assoc array from request content
        $data = json_decode($this->getRequest()->getContent(), true);

        return $this->saveForm($form, $data);
    }

    /**
     * modify service by its id
     * [PUT] /settings/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function putSettingsAction($id)
    {
        // find service
        $service = $this->getSettingRepository()->find($id);

        // check if it exists
        if ($service) {
            // create form, decode request and save it if valid
            $view = $this->saveForm(
                $this->createForm(new SettingType(), $service),
                json_decode($this->getRequest()->getContent(), true)
            );
        } else {
            // service does not exists send 404
            $view = $this->createView("Setting does not exists.", 404);
        }

        return $view;
    }

    /**
     * delete service by its id
     * [DELETE] /settings/{id}
     *
     * @param  int  $id
     * @return View
     */
    public function deleteSettingsAction($id)
    {
        // find service
        $service = $this->getSettingRepository()->find($id);

        // check if it exists
        if ($service) {
            // remove service
            $em = $this->getDoctrine()->getManager();
            $em->remove($service);
            $em->flush();

            // send status message
            $view = $this->createView("Setting has been removed.");
        } else {
            // service does not exists send 404
            $view = $this->createView("Setting does not exists", 404);
        }

        return $view;
    }
}
