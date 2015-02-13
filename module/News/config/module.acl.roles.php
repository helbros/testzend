<?php
return array(	
		'guest' => array(
				'Auth/login',
				'Auth/logout',
				'Chat/index',
				'Chat/postchat',
				'Chat/getchat',
		),
		'member' => array(
				'Auth/login',
				'Manager/index',
				'Chat/index',
				'Chat/postchat',
				'Chat/getchat',
		),
		'admin' => array(
				'Auth/login',
				'Auth/checkAuth',
				'Manager/index',
				'Manager/adduser',
				'Manager/edit',
				'Manager/delete',
				'Manager/setmod',
				'Manager/view',
				'Manager/home',
				'Chat/index',
				'Chat/postchat',
				'Chat/getchat',
				'Index/db'
		),
);