<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Admin\Model\UsersTable;
use Admin\Model\Users;
use Zend\ModuleManager\ModuleManager;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $e->getApplication ()->getServiceManager ()->get ( 'translator' );
        $sharedEvents = $eventManager->getSharedManager();
    $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
        $controller = $e->getTarget();
        $route = $controller->getEvent()->getRouteMatch();
        $e->getViewModel()->setVariables(
            array('controllerName'=> $route->getParam('__CONTROLLER__', 'index'),
                'actionName' => $route->getParam('action', 'index'),
                'moduleName' => strtolower(__NAMESPACE__))
        );
    }, 100);
         //handle the dispatch error (exception) 
     /*	$sharedManager = $e->getApplication()->getEventManager()->getSharedManager();
    	$sm = $e->getApplication()->getServiceManager();
    	$sharedManager->attach('Zend\Mvc\Application', 'dispatch.error',
        function($e) use ($sm) 
        {
            if ($e->getParam('exception'))
            {
                $sm->get('Logger')->crit($e->getParam('exception'));
            }
        }
    );*/
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Admin\Model\UsersTable' =>  function($sm) {
                    $tableGateway = $sm->get('UsersTableGateway');
                    $table = new UsersTable($tableGateway);
                    return $table;
                },
                'UsersTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();                    
                    $resultSetPrototype->setArrayObjectPrototype(new Users());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },               
            ),
        );
    }
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    public function init(ModuleManager $mm)
    {
        $mm->getEventManager()->getSharedManager()->attach(__NAMESPACE__, 'dispatch', function($e) {
            $e->getTarget()->layout('admin/layout');
        });
    }

}
