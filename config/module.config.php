<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Logger\Controller\Logger' => 'Logger\Controller\LoggerController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'logger' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/logger[/:action][/:id][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Logger\Controller\Logger',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'addlog' => function ($sm) {
                $locator = $sm->getServiceLocator();
                $viewHelper = new Logger\View\Helper\Add;
                $viewHelper->setMapper($locator->get('logger_mapper'));
                return $viewHelper;
            },
        )
    ),
    'controller_plugins' => array(
        'factories' => array(
            'addlog' => function ($sm) {
                $locator = $sm->getServiceLocator();
                $viewHelper = new Logger\Controller\Plugin\Add;
                $viewHelper->setMapper($locator->get('logger_mapper'));
                return $viewHelper;
            },
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'Logger\Mapper\Logger' => 'Logger\Mapper\Logger',
            'Logger\Mapper\LoggerHydrator' => 'Logger\Mapper\LoggerHydrator',
            'Logger\Entity\Logger' => 'Logger\Entity\LoggerEntity',
        ),
        'factories' => array(
            'logger_hydrator' => function ($sm) {
                $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
                return $hydrator;
            },
            'logger_mapper' => function ($sm) {
                $mapper = new Logger\Mapper\Logger;
                $mapper->setAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                $mapper->setHydrator(new Logger\Mapper\LoggerHydrator());
                $mapper->setEntity(new Logger\Entity\Logger());

                $mapper->setTableName('logs');

                return $mapper;
            },
            'get_config' => function ($sm) {
                $config = $sm->get('Config');
                return $config['Logger'];
            },
            'logger' => function ($sm) {
                $mapper = new Logger\Mapper\Logger;
                $mapper->setAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                $mapper->setHydrator(new Logger\Mapper\LoggerHydrator());
                $mapper->setEntity(new Logger\Entity\Logger());

                $mapper->setTableName('logs');
                return $mapper;
            },
        ),
    ),

);