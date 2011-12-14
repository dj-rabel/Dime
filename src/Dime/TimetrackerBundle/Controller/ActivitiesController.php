<?php

namespace Dime\TimetrackerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;
use Dime\TimetrackerBundle\Entity\Activity;
use Dime\TimetrackerBundle\Form\ActivityType;
use Dime\TimetrackerBundle\Controller\DimeController as Controller;

class ActivitiesController extends Controller
{
    /**
     * get activity repository 
     * 
     * @return Dime\TimetrackerBundle\Entity\ActivityRepository
     */
    protected function getActivityRepository()
    {
        return $this->getDoctrine()->getRepository('DimeTimetrackerBundle:Activity');
    }

    /**
     * [GET] /activities
     *
     * @Route("/")
     */
    public function getActivitiesAction()
    {
        $activities = $this->getActivityRepository()->toArray();

        $view = View::create()->setData($activities);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * load activity
     *
     * [GET] /activity/{id}
     */
    public function getActivityAction($id)
    {
        $activity = $this->getActivityRepository()->find($id);
        if ($activity) {
            $view = View::create()->setData($activity->toArray());
        } else {
            $view = View::create()->setStatusCode(404);
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * create activity
     * [POST] /activity
     * 
     * @return void
     */
    public function postActivityAction()
    {
        // create new activity
        $activity = new Activity();

        // create activity form
        $form = $this->createForm(new ActivityType(), $activity);

        // get request
        $request = $this->getRequest();

        // convert json to assoc array
        $data = json_decode($request->getContent(), true);

        return $this->get('fos_rest.view_handler')->handle($this->saveForm($form, $data));
    }

    /**
     * modify activity
     * [PUT] /activity/{id}
     * 
     * @param string $id
     * @return void
     */
    public function putActivityAction($id)
    {
        if ($activity = $this->getActivityRepository()->find($id)) {
            $view = $this->saveForm(
                $this->createForm(new ActivityType(), $activity),
                json_decode($this->getRequest()->getContent(), true)
            );
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Activity does not exist.");
        }
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * delete activity
     * [DELETE] /activity/{id}
     *
     * @param int $id
     * @return void
     */
    public function deleteActivityAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        if ($activity = $this->getActivityRepository()->find($id)) {
            $em->remove($activity);
            $em->flush();

            $view = View::create()->setData("Activity has been removed.");
        } else {
            $view = View::create()->setStatusCode(404);
            $view->setData("Activity does not exists.");
        }
    }
}
