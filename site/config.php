<?php

define("DB_HOST", "blu-ray.student.bth.se");
define("DB_USER", "jast10");
define("DB_PASSWORD", "oIcm&=RL");
define("DB_DATABASE", "jast10");

/**
 * Site configuration, this file is change by user per site.
 *
 */
/*
 * Set level of error reporting
 */
error_reporting(-1);

/*
 * Define session name
 */
$ly->cfg['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);

/*
 * Define server timezone
 */
$ly->cfg['timezone'] = 'Europe/Stockholm';

/*
 * Define internal character encoding to UTF-8
 */
$ly->cfg['character_encoding'] = 'UTF-8';

$ly->cfg["baseurl"] = "http://www.student.bth.se/~jast10/dbwebb2/lydia";

/**
 * Define the controllers, their classname and enable/disable them.
 *
 * The array-key is matched against the url, for example: 
 * the url 'user/login' would instantiate the controller with the key "user", that is CCtrl4User
 * and call the method "login" in that class. This process is managed in:
 * $ly->FrontControllerRoute();
 * and this method is called in the frontcontroller phase from index.php.
 */
$ly->cfg['controllers'] = array(
	'index' => array('enabled' => true, 'class' => 'CCtrl4Index'),
	'mumintrollet' => array('enabled' => true, 'class' => 'CCtrl4Mumintrollet'),
	"page" => array("enabled" => true, "class" => "CCtrl4Page"),
	"db" => array("enabled" => true, "class" => "CCtrl4Database"),
	"admin" => array("enabled" => true, "class" => "CCtrl4Login")
);

$ly->cfg['site'] = array(
	"head" => array(
		"stylesheet" => array(
			"screen" => $ly->cfg["baseurl"] . "/style/blueprint/screen.css"
		),
		"meta" => array(
			"charset" => "utf-8"
		),
		"js" => array(
		//array("type" => "text/javascript", "src" => "")
		),
		"title" => "Default title"
	),
	"header" => array(
		"logo" => "",
		"site-title" => "Lydia",
		"main-menu" => array(
			"items" => array(
				array("name" => "Home", "url" => "index", "class" => "", "admin" => false),
				array("name" => "Mumintrollet", "url" => "mumintrollet", "class" => "", "admin" => false),
				array("name" => "Page", "url" => "page", "class" => "", "admin" => true)
			)
		)
	),
	"footer" => array(
		"info" => "",
		"copyright" => "Sylvanas"
	)
);
