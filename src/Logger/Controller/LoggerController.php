<?php
namespace Logger\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Class LoggerController
 *
 * @package Logger\Controller
 */
class LoggerController extends AbstractActionController
{
    /**
     * @return JsonModel
     */
    public function getAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $mapper = $this->getServiceLocator()->get('logger_mapper');
        $result = $mapper->findByType("error");

        if (! empty($result)) {
            return new JsonModel(array('success' => $result));
        }

        return new JsonModel(array('error' => 'ID is not valid'));
    }

    /**
     * @return JsonModel
     */
    public function addAction()
    {
        if ($this->getRequest()->isPost()) {
            $mapper = $this->getServiceLocator()->get('logger_mapper');
            $result = $mapper->save($this->params()->fromQuery());

            if (! empty($result)) {
                return new JsonModel(array('success' => $result));
            }

            return new JsonModel(array('error' => 'ID is not valid'));
        }

        return new JsonModel(array('error' => 'Post data is not valid'));
    }
}