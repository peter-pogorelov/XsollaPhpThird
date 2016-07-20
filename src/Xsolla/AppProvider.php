<?php

namespace Xsolla{
	use \Silex\Api\ControllerProviderInterface as ControllerProviderInterface;
	use \Silex\Application as Application;
	
	class AppProvider implements ControllerProviderInterface {
		public function connect(Application $app) {
			$routes = $app["controllers_factory"];
			
			$routes->get("/", "Xsolla\AppController::getRows");
			$routes->post("/", "Xsolla\AppController::addRow");
			$routes->get("/{offset}", "Xsolla\AppController::getRow")
				->assert('offset', "^[1-9]|[0-9]{2,}$");
			$routes->put("/{offset}", "Xsolla\AppController::updateRow")
				->assert('offset', "^[1-9]|[0-9]{2,}$");
			$routes->delete("/{offset}", "Xsolla\AppController::deleteRow")
				->assert('offset', "^[1-9]|[0-9]{2,}$");
			
			return $routes;
		}
	}
}