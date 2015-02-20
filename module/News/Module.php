<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace News;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use News\Model\UserTable;
use News\Model\User;
use Zend\Authentication\AuthenticationService;
use News\Controller\ManagerController;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Authentication\Adapter\DbTable;
use Zend\Validator\Db\RecordExists;
use News\Model\Article;
use News\Model\ArticleTable;
class Module
{
    public function onBootstrap(MvcEvent $e)
    {
    	define('BASE_URL', 'qweqwe');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener ();
		$moduleRouteListener->attach ( $eventManager );
		/* $eventManager->attach ( MvcEvent::EVENT_ROUTE, array (
				$this,
				'checkAuthAcl' 
		) ); */
		// $eventManager->attach(MvcEvent::EVENT_ROUTE,array($this,'checkAcl'));
	}
	function checkAuthAcl(MvcEvent $e) {
		$controller = $e->getRouteMatch ()->getParam ( 'controller' );
		
		$auth = new AuthenticationService ();
		
		if (! $auth->hasIdentity () && $controller != 'News\Controller\Auth') {
			
			$response = $e->getResponse ();
			$response->getHeaders ()->addHeaderLine ( 'Location', 'http://localhost/workspace/testzend/public/news/auth/login' );
			$response->setStatusCode ( 302 );
			return $response;
		} elseif ($auth->hasIdentity ()) {			
			$username = $auth->getStorage ()->read ()->username;
			$title_user = $auth->getStorage ()->read ()->title_user;
			//echo 'username :' . $username . '<br>';
			//echo 'type :' . $title_user . '<br>';
			$validator = new RecordExists ( array (
					'table' => 'userz',
					'field' => 'username',
					'adapter' => $e->getApplication ()->getServiceManager ()->get ( 'Zend\Db\Adapter\Adapter' ) 
			) );
			$validator->setMessage('Tài khoản không tồn tại');
			if ($validator->isValid ( $username )) {
				$acl_role = include __DIR__ . '\config\module.acl.roles.php';
				$acl = new Acl ();
				foreach ( $acl_role as $role => $resource ) {
					$acl->addRole ( new GenericRole ( $role ) );
					foreach ( $resource as $res ) {
						if (! $acl->hasResource ( $res )) {
							$acl->addResource ( new GenericResource ( $res ) );
						}
						$acl->allow ( $role, $res );
					}
				}
				//echo '<br>';
				$action = $e->getRouteMatch ()->getParam ( 'action' );
				$controller2 = explode ( '\\', $e->getRouteMatch ()->getParam ( 'controller' ) )[2];
				$route = $controller2 . '/' . $action;
				//echo $title_user . '<br>';
				//echo $route . '<br>';
				if ($acl->isAllowed ( $title_user, $route )) {
					//echo 'true <br>';
				} else {
					echo 'forbiden access';
				}
			}else {
				foreach ( $validator->getMessages () as $message ) {
					echo "$message\n";
				}
			}
			
		}
	}

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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
    function getServiceConfig(){
    	return array(
    			'factories'=>array(
    					'UserTableGateway'=>function ($sm){
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype=new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new User());
    						return new TableGateway('userz', $dbAdapter,null,$resultSetPrototype);
    					},
    					'News\Model\UserTable'=>function ($sm){
    						$tableGateway=$sm->get('UserTableGateway');
    						$table=new UserTable($tableGateway);
    						return $table;
    					},    		
    					'News\Model\ArticleTable'=>function ($sm){
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype=new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Article());
    						$tableGateway=new TableGateway('article', $dbAdapter,null,$resultSetPrototype);
    						$table=new ArticleTable($tableGateway);
    						return $table;
    					},
    					'News\Model\ChatTable'=>function($sm){
    						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype=new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Chat());
    						$tableGateway= new TableGateway('chat',$dbAdapter,null,$resultSetPrototype)
    						$table=new ChatTable($tableGateway);
    						return $table;
    					},
    					'checkAuthBand'=>function ($sm){
    						$auth = new AuthenticationService ();
    						$validator_band=new RecordExists(array(
    								'table'=>'userz_ban',
    								'field'=>'username',
    								'adapter'=>$sm->get('Zend\Db\Adapter\Adapter'),
    						));
    						$result_band=($validator_band->isValid($auth->getIdentity()->username))?	true:false;
    						return $result_band;
    					}			    					
    			)
    			
    	);
    }
    
	
}
