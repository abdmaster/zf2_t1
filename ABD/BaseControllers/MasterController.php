<?php namespace ABD\BaseControllers;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;

abstract class MasterController extends AbstractActionController {
	
	protected $entityManager;

    protected $requestNames;

    /**
     * Set Request Names of Module, Controllers etc.
     *
     * @param MvcEvent $e
     */
    protected function setRequestNames(MvcEvent $e)
    {
        // If values already set, no need to execute
        if ($this->requestNames) {
            return;
        }

        $sm = $e->getApplication()->getServiceManager();

        $router = $sm->get('router');
        $request = $sm->get('request');
        $matchedRoute = $router->match($request);

        $params = $matchedRoute->getParams();

        $controller = $params['controller'];
        $action = $params['action'];

        $module_array = explode('\\', $controller);
        $module = array_pop($module_array);

        $route = $matchedRoute->getMatchedRouteName();

        $this->requestNames['module'] = $module;
        $this->requestNames['controller'] = $controller;
        $this->requestNames['action'] = $action;
        $this->requestNames['route'] = $route;

        $e->getViewModel()->setVariables([
            'requestNames' => $this->requestNames,
        ]);
    }

    /**
     * onDispatch description
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->setRequestNames($e);

        return parent::onDispatch($e);
    }

    /**
     * Get Doctrine Entity Manager
     *
     * @return Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        if (null === $this->entityManager)
        {
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->entityManager;
    }

    
    /**
     * Set Page Status to Not Found
     *
     * @return void
     */
    protected function pageNotFound()
    {
        $this->getResponse()->setStatusCode(404);
        return;
    }
    

    /**
     * Get the Requested Entity
     *
     * @param  string $entity
     * @return Entity
     */
    protected function entity($entity)
    {
        return $this->getEntityManager()->getRepository($entity);
    }

}