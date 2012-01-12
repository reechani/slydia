<?php

/**
 * Main class for Lydia, holds everything.
 *
 * @package LydiaCore
 */
class CLydia implements ISingleton {

	private static $instance = null;

	/**
	 * Constructor
	 */
	protected function __construct() {
		// set default exception handler
		set_exception_handler(array($this, 'DefaultExceptionHandler'));

		// include the site specific config.php and create a ref to $ly to be used by config.php
		$ly = &$this;
		require(LYDIA_INSTALL_PATH . '/site/config.php');
		$ly->db = db::GetInstance();


		// create the empty template holder for content to be displayed in templateengine render
		$this->regions = array();

		$ly->template = new stdClass();
		$ly->template->regions = new stdClass();
		$this->template->tplfile = 'default';
		$this->template->title = $this->cfg["site"]["head"]["title"];
	}

	/**
	 * Singleton pattern. Get the instance of the latest created object or create a new one. 
	 * @return CLydia The instance of this class.
	 */
	public static function GetInstance() {
		if (self::$instance == null) {
			self::$instance = new CLydia();
		}
		return self::$instance;
	}

	/**
	 * Create a common exception handler 
	 */
	public static function DefaultExceptionHandler($aException) {
		// CWatchdog to store logs
		die("<h3>Exceptionhandler</h3><p>File " . $aException->getFile() . " at line" . $aException->getLine() . "<p>Uncaught exception: " . $aException->getMessage() . "<pre>" . print_r($aException->getTrace(), true) . "</pre>");
	}

	public function getMainMenuNavHTML() {
		$ly = &$this;
		$menu = $this->cfg["site"]["header"]["main-menu"];
		// Add pages from datbase to the menu
		$res = $ly->db->select(TBL_PREFIX . "Pages");
		if ($res) {
			while ($row = $res->fetch_object()) {
				$menu["items"][] = array("name" => $row->title, "url" => ("page/show/" . $row->id), "class" => "", "admin" => false);
			}
		}
		$current = $ly->req->controller;
		if ($ly->req->controller == "page" && $ly->req->action == "show") {
			$current .= "/" . $ly->req->action . "/" . $ly->req->args[2];
		}
		$nav = "<nav><ul>";
		foreach ($menu["items"] as $item) {
			if ($item["url"] == $current) {
				$item["class"] .= " current";
			}
			if (($item["admin"] && isset($_SESSION["id"])) || !$item["admin"]) {
				$nav .= "<li class='" . $item["class"] . "'><a href='" . $ly->cfg["baseurl"] . "/" . $item["url"] . "' title='" . $item["name"] . "'>" . $item["name"] . "</a></li>";
			}
			// GET PAGES FROM DATABASE
		}
		$nav .= "</ul></nav>";

		return $nav;
	}

	/**
	 * Frontcontroller, route to controllers.
	 */
	public function FrontControllerRoute($aController = null, $aAction = null) {
		// Step 1
		// Take current url and divide it in controller, action and parameters
		$query = substr($_SERVER['REQUEST_URI'], strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/')));
		$splits = explode('/', trim($query, '/'));

		// Step 2
		// Set controller, action and parameters
		$controller = !empty($splits[0]) ? $splits[0] : 'index';
		$action = !empty($splits[1]) ? $splits[1] : 'index';
		$args = $splits;
		unset($args[0]);
		unset($args[1]);

		// Step 3
		// Store it
		$this->req = new stdClass(); // I want to use class instead of array to reference values
		$this->req->query = $query;
		$this->req->splits = $splits;
		$this->req->controller = $controller;
		$this->req->action = $action;
		$this->req->args = $args;

		// Step 4
		// Is the module enabled in config.php?
		$moduleExists = isset($this->cfg['controllers'][$controller]);
		$moduleEnabled = false;
		$class = false;
		$classExists = false;

		if ($moduleExists) {
			$moduleEnabled = ($this->cfg['controllers'][$controller]['enabled'] == true);
			$class = $this->cfg['controllers'][$controller]['class'];
			$classExists = class_exists($class);
		}

		// Step 5
		// Check if controller, action has a callable method in the controller class, if then call it
		if ($moduleExists && $moduleEnabled && $classExists) {
			$rc = new ReflectionClass($class);
			if ($rc->implementsInterface('IController')) {
				if ($rc->hasMethod($action)) {
					$controllerObj = $rc->newInstance();
					$method = $rc->getMethod($action);
					$method->invokeArgs($controllerObj, $this->req->args);
				} else {
					throw new Exception(get_class() . ' error: Controller does not contain action.');
				}
			} else {
				throw new Exception(get_class() . ' error: Controller does not implement interface IController.');
			}
		}
		// Page not found 404
		else {
			echo "<p>THIS SHOULD BE A 404 REDIRECT</p>"; //$this->FrontControllerRoute('error', 'code404'); // internal redirect
		}
	}

	/**
	 * Template Engine Render, renders the views using the selected theme.
	 */
	public function TemplateEngineRender() {
		$ly = &$this;
		$ly->template->regions->top = $ly->getLoginMenu();
		$regions = $ly->template->regions;
		$menu = $ly->getMainMenuNavHTML();
		$tpl = LYDIA_INSTALL_PATH . '/theme/' . $this->template->tplfile . '.tpl.php';
		if (is_file($tpl)) {
			include($tpl);
		} else {
			throw new Exception(get_class() . ' error: Template file does not exists.');
		}
	}

	public function getLoginMenu() {
		$html = "";
		$ly = &$this;
		if (isset($_SESSION["user"])) {
			$html = "<a href='{$ly->cfg["baseurl"]}/admin/'>Logout</a>";
		} else {
			$html = "<a href='{$ly->cfg["baseurl"]}/admin/'>Login</a>";
		}
		return $html;
	}

	public function getStylesheetHTML() {
		$ly = &$this;
		$html = "";
		$css = $ly->cfg["site"]["head"]["stylesheet"];
		foreach($css as $style) {
			$html .= "<link rel='stylesheet' type='text/css' href='$style' />";
		}
		return $html;
//		return "<link rel='stylesheet' media='screen' type='text/css' href='" . $ly->template->stylesheet . "'/>";
	}

//	public function getScriptsHTML() {
//		$ly = &$this;
//		$html = "";
//		foreach ($ly->cfg["site"]["head"]["js"] as $script) {
//			$html .= "<script type='" . $script["type"] . "' src='" . $script["src"] . "'></script>";
//		}
//		return $html;
//	}

	public function getMeta() {
		$ly = &$this;
		$html = "<meta ";
		$meta = $ly->cfg["site"]["head"]["meta"];
		foreach ($meta as $key => $value) {
			$html .= "$key = '$value' ";
		}
		$html .= "/>";
		return $html;
	}

	public function getJS() {
		$ly = &$this;
		$html = "";
		$js = $ly->cfg["site"]["head"]["js"];
		foreach($js as $link) {
			$html .= "<script src='$link' ></script>";
		}
		return $html;
	}

}
