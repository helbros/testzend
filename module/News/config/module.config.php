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
													/* 	'getdatafb' => array (
																'type' => 'Literal',
																'options' => array (
																		'route' => '/getdatafb',																		
																		'defaults' => array (
																				'action' => 'getdatafb' 
																		) 
																) 
														) */
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
												)
										)
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
						'translator' => 'MvcTranslator' 
				),
				'factories' => array (
						'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory' 
				) 
		),
		'navigation' => array (
				'default' => array (
						
						// config of first page
						'news' => array (
								'label' => 'ĐIỂM BÁO',
								'route' => 'news' 
						),
						
						// config of another page
						'phantich' => array (
								'label' => 'PHÂN TÍCH',
								'route' => 'news'
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
						
						
						'manager' => array (
								'label' => 'QUẢN LÝ',
								'route' => 'news/manager',
								'controller' => 'manager',
								'action' => 'index' 
						)
						 
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
						'News\Controller\Stock' => 'News\Controller\StockController'
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
				) 
		),
		
		// Placeholder for console routes
		'console' => array (
				'router' => array (
						'routes' => array () 
				) 
		) 
); 

