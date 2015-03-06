<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array (
		'router' => array (
				'routes' => array (
						'home' => array (
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array (
										'route' => '/',
										'defaults' => array (
												'controller' => 'Application\Controller\Index',
												'action' => 'index' 
										) 
								) 
						),
						
						// The following is a route to simplify getting started creating
						// new controllers and actions without needing to create a new
						// module. Simply drop new controllers in, and you can access them
						// using the path /application/:controller/:action
						'news' => array (
								'type' => 'Literal',
								'options' => array (
										'route' => '/news',
										'defaults' => array (
												'__NAMESPACE__' => 'News\Controller',
												'controller' => 'Index',
												'action' => 'index' 
										) 
								),
								'may_terminate' => true,
								'child_routes' => array (
										'default' => array (
												'type' => 'Segment',
												'options' => array (
														'route' => '/[:controller[/:action]]',
														'constraints' => array (
																'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
																'action' => '[a-zA-Z][a-zA-Z0-9_-]*' 
														),
														'defaults' => array () 
												) 
										),
										
										'manager' => array (
												'type' => 'Literal',
												'options' => array (
														'route' => '/manager',
														'defaults' => array (
																'controller' => 'News\Controller\Manager',
																'action' => 'index' 
														) 
												),
												'may_terminate' => true,
												'child_routes' => array (
														'paginator' => array (
																'type' => 'Segment',
																'options' => array (
																		'route' => '/home/[page/:page]',
																		'defaults' => array (
																				'action' => 'home' 
																		) 
																) 
														),	
														'home' => array (
																'type' => 'Literal',
																'options' => array (
																		'route' => '/home',
																		'defaults' => array (
																				'action' => 'home'
																		)
																)
														),
												) 
										)
										,
										'auth' => array (
												'type' => 'Literal',
												'options' => array (
														'route' => '/auth',
														'defaults' => array (
																'controller' => 'News\Controller\Auth',
																'action' => 'index' 
														) 
												),
												'may_terminate' => true,
												'child_routes' => array (
														'login' => array (
																'type' => 'Literal',
																'options' => array (
																		'route' => '/login',
																		'defaults' => array (
																				'action' => 'login' 
																		) 
																) 
														),
														'logout' => array (
																'type' => 'Literal',
																'options' => array (
																		'route' => '/logout',
																		'defaults' => array (
																				'action' => 'logout' 
																		) 
																) 
														),
														'register' => array (
																'type' => 'Literal',
																'options' => array (
																		'route' => '/register',																		
																		'defaults' => array (
																				'action' => 'register' 
																		) 
																) 
														)
												) 
										)
										,
										'chat' => array (
												'type' => 'Literal',
												'options' => array (
														'route' => '/chat',
														'defaults' => array (
																'controller' => 'News\Controller\Chat',
																'action' => 'index' 
														) 
												),
												'may_terminate' => true,
												'child_routes' => array (													
														'postchat' => array (
																'type' => 'Literal',
																'options' => array (
																		'route' => '/postchat',
																		'defaults' => array (
																				'action' => 'postchat' 
																		) 
																) 
														),
														'clearall' => array (
																'type' => 'Literal',
																'options' => array (
																		'route' => '/clearall',
																		'defaults' => array (
																				'action' => 'clearall'
																		)
																)
														),
														'banchat' => array (
																'type' => 'segment',
																'options' => array (
																		'route' => '/banchat[/:id]',
																		'constrains'=>array(
																			'id'=>'[0-9]+'	
																		),
																		'defaults' => array (
																				'action' => 'banchat'
																		)
																)
														),
														'unbanchat' => array (
																'type' => 'segment',
																'options' => array (
																		'route' => '/unbanchat[/:id]',
																		'constrains'=>array(
																				'id'=>'[0-9]+'
																		),
																		'defaults' => array (
																				'action' => 'unbanchat'
																		)
																)
														),
														'manager' => array (
																'type' => 'Literal',
																'options' => array (
																		'route' => '/manager',																		
																		'defaults' => array (
																				'action' => 'manager'
																		)
																)
														)
												) 
										),
										'stock' => array (
												'type' => 'Literal',
												'options' => array (
														'route' => '/stock',
														'defaults' => array (
																'controller' => 'News\Controller\Stock',
																'action' => 'index'
														)
												),
												'may_terminate' => true,
												'child_routes' => array (
														'stockinfo' => array (
																'type' => 'Segment',
																'options' => array (
																		'route' => '/stockinfo/[:stockname]',
																		'constraints'=>array(
																			'stockname'=>'[a-zA-Z][a-zA-Z0-9_-]*'	
																		),
																		'defaults' => array (
																				'action' => 'stockinfo'
																		)
																)
														),
														'viewstock' => array (
																'type' => 'Segment',
																'options' => array (
																		'route' => '/viewstock/[:stockname]',
																		'constraints'=>array(
																				'stockname'=>'[a-zA-Z][a-zA-Z0-9_-]*'
																		),
																		'defaults' => array (
																				'action' => 'viewstock'
																		)
																)
														),
														'getstockinfo' => array (
																'type' => 'Segment',
																'options' => array (
																		'route' => '/getstockinfo/[:stockname]',
																		'constraints'=>array(
																				'stockname'=>'[a-zA-Z][a-zA-Z0-9_-]*'
																		),
																		'defaults' => array (
																				'action' => 'getstockinfo'
																		)
																)
														),
												)
										),
										'article' => array (
												'type' => 'Literal',
												'options' => array (
														'route' => '/article',														
														'defaults' => array (
																'controller' => 'News\Controller\article',
																'action' => 'index'
														)
												),
												'may_terminate' => true,
												'child_routes' => array (
														'index' => array (
																'type' => 'segment',
																'options' => array (
																		'route' => '/index[/cate/:cate][/page/:page]',
																		'constrains'=>array(
																				'cate'=>'[0-9]+',
																				'page'=>'[0-9]+'
																		),
																		'defaults' => array (
																				'action' => 'index'
																		)
																)
														),
														'detail-article' => array (
																'type' => 'segment',
																'options' => array (
																		'route' => '/detail-article[/:id]',
																		'constrains'=>array(
																			'id'=>'[a-zA-Z][a-zA-Z0-9_-]*' 
																		),
																		'defaults' => array (
																				'action' => 'detailArticle'
																		)
																)
														),
														'list-article' => array (
																'type' => 'segment',
																'options' => array (
																		'route' => '/list-article/id[/:id][/page/:page]',
																		'constrains'=>array(
																				'id'=>'[a-zA-Z][a-zA-Z0-9_-]*',
																				'page'=>'[a-zA-Z][a-zA-Z0-9_-]*'
																		),
																		'defaults' => array (
																				'action' => 'listArticle'
																		)
																)
														),
														'add-cate-article' => array (
																'type' => 'Literal',
																'options' => array (
																		'route' => '/add-cate-article',																		
																		'defaults' => array (
																				'action' => 'addCateArticle'
																		)
																)
														),
														'edit-cate-article' => array (
																'type' => 'segment',
																'options' => array (
																		'route' => '/edit-cate-article[/:id]',
																		'constrains'=>array(
																				'id'=>'[0-9]+',																				
																		),
																		'defaults' => array (
																				'action' => 'editCateArticle'
																		)
																)
														),
														'delete-cate-article' => array (
																'type' => 'segment',
																'options' => array (
																		'route' => '/delete-cate-article[/:id]',
																		'constrains'=>array(
																				'id'=>'[0-9]+',
																		),
																		'defaults' => array (
																				'action' => 'deleteCateArticle'
																		)
																)
														),
														'add-article' => array (
																'type' => 'Literal',
																'options' => array (
																		'route' => '/add-article',
																		'defaults' => array (
																				'action' => 'addArticle'
																		)
																)
														),
														'delete-article' => array (
																'type' => 'segment',
																'options' => array (
																		'route' => '/delete-article[/:id]',
																		'constrains'=>array(
																				'id'=>'[0-9]+',																				
																		),
																		'defaults' => array (
																				'action' => 'deleteArticle'
																		)
																)
														),
														'edit-article' => array (
																'type' => 'segment',
																'options' => array (
																		'route' => '/edit-article[/:id]',
																		'constrains'=>array(
																				'id'=>'[0-9]+',
																		),
																		'defaults' => array (
																				'action' => 'editArticle'
																		)
																)
														),
														'publish-article' => array (
																'type' => 'segment',
																'options' => array (
																		'route' => '/publish-article[/:id]',
																		'constrains'=>array(
																				'id'=>'[0-9]+',
																		),
																		'defaults' => array (
																				'action' => 'publishArticle'
																		)
																)
														),
														'featured-article' => array (
																'type' => 'segment',
																'options' => array (
																		'route' => '/featured-article[/:id]',
																		'constrains'=>array(
																				'id'=>'[0-9]+',
																		),
																		'defaults' => array (
																				'action' => 'featuredArticle'
																		)
																)
														),
														
												)
										),
								) 
						),
						'manager' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/manager[/:action][/:id]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+' 
										),
										'defaults' => array (
												'controller' => 'News\Controller\Manager',
												'action' => 'index' 
										) 
								) 
						) 
				) 
		),
		'service_manager' => array (
				'abstract_factories' => array (
						'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
						'Zend\Log\LoggerAbstractServiceFactory' 
				),
				'aliases' => array (
						'translator' => 'MvcTranslator',
						'Zend\Authentication\AuthenticationService' => 'my_auth_service',
				),
				'invokables' => array(
						'my_auth_service' => 'Zend\Authentication\AuthenticationService',
				),
				'factories' => array (
						'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory' 
				),				
				
		),
		'navigation' => array (
				'default' => array (
						
						// config of first page
						'news' => array (
								'label' => 'HOME',
								'route' => 'news/manager/home' 
						),
						
						// config of another page
						'phantich' => array (
								'label' => 'PHÂN TÍCH',
								'route' => 'news/article/list-article',
								'params'=>array('id'=>1)
						),
						'nhandinh' => array (
								'label' => 'NHẬN ĐỊNH',
								'route' => 'news'
						),
						'kinhnghiem' => array (
								'label' => 'KINH NGHIỆM',
								'route' => 'news'
						),
						'aboutus' => array (
								'label' => 'ABOUT US',
								'route' => 'news'
						),						
						// 'module'=>'news',
						
											
						 
				)
				 
		),
		'translator' => array (
				'locale' => 'en_US',
				'translation_file_patterns' => array (
						array (
								'type' => 'gettext',
								'base_dir' => __DIR__ . '/../language',
								'pattern' => '%s.mo' 
						) 
				) 
		),
		'controllers' => array (
				'invokables' => array (
						'News\Controller\Index' => 'News\Controller\IndexController',
						'News\Controller\Upload' => 'News\Controller\UploadController',
						'News\Controller\Manager' => 'News\Controller\ManagerController',
						'News\Controller\Auth' => 'News\Controller\AuthController',
						'News\Controller\Chat' => 'News\Controller\ChatController', 
						'News\Controller\Stock' => 'News\Controller\StockController',
						'News\Controller\Article' => 'News\Controller\ArticleController',
						'News\Controller\Admin' => 'News\Controller\AdminController'
				) 
		),
		'view_manager' => array (
				'display_not_found_reason' => true,
				'display_exceptions' => true,
				'doctype' => 'HTML5',
				'not_found_template' => 'error/404',
				'exception_template' => 'error/index',
				'template_map' => array (
						'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
						'news/index/index' => __DIR__ . '/../view/news/index/index.phtml',
						'error/404' => __DIR__ . '/../view/error/404.phtml',
						'error/index' => __DIR__ . '/../view/error/index.phtml',
						'view_chat'=>__DIR__ . '/../view/news/chat/index.phtml',
						'view_stock'=>__DIR__ . '/../view/news/stock/index.phtml',
				),
				'template_path_stack' => array (
						'news' => __DIR__ . '/../view',
				),
				'strategies' => array (
						'ViewJsonStrategy' 
				),
				//'base_path' =>'http://localhost/workspace/testzend/public',
		),
		
		// Placeholder for console routes
		'console' => array (
				'router' => array (
						'routes' => array () 
				) 
		) 
); 

