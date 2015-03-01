<?php

	namespace Milkwood\Accessable;

	trait Accessable{

		public function hasAccessToAction($routeActionName = false, $flashErrors = false){

			//If routeActionName is false then set it to the current route action
			$routeActionName = ($routeActionName) ? $routeActionName : \Route::getCurrentRoute()->getActionName();

			// If App\Http\Controllers\ is no in front of $routeActionName add it
			$routeActionName = \CH::getActionWithNamespace($routeActionName);

			//Get the route actions which could be "reqRoles", "minAccessLevel" or other requirements
			$routeActions = \Route::getRoutes()->getByAction($routeActionName)->getAction();

			//KeysToCheckFor is an array of possible requirement set on the class itself example reqRole, maxAccessLevel or other
			foreach($this->keysToCheckFor as $key){

				// Does the requirements exists, if yes then run it
				if( array_key_exists($key, $routeActions) ){

					//Run checkRequirements with the found requirement
					if( ! $this->checkRequirements( [$key => $routeActions[$key]] ) ){

						// If flashErrors is true, find the objects error varible and send it via flash
						if($flashErrors){ 
							\Flash::error( $this->error );
						}

						// Unset ui error text if one was added, as it would already had been send above via Flash::errror
						unset($this->error);
						
						return false;
					}

				}

			}

			return true;
		
		}

		public function checkRequirements(Array $restrictionsArray){
		
			foreach($restrictionsArray as $restrictionName => $restrictionValue){

				// Check the requirement, if it fails return false and flash errors if needed
				if( ! $this->$restrictionName($restrictionValue) ){

					return false;
				}

			}

			return true;
		
		}

		// Create an html link if the user has access
		public function linkToActionIfHasAccess($action,$title,$parametres = [],$attributes = []){
		
			if( $this->hasAccessToAction( $action ) ){
				return link_to_action($action,$title,$parametres,$attributes);
			}
		
		}

		// Used if you want a model to check it's requirements of an restrictions array
		protected function sendOfAccess($shouldBeSendTo,$restrictionsArray){
		
			// CheckRequirements of  the given glass
			$returnVal = $this->$shouldBeSendTo->checkRequirements( $restrictionsArray );

			// If passes return true
			if( $returnVal ){
				return true;
			}

			// Else set errors of the object equal to the sub elements errors
			$this->error = $this->$shouldBeSendTo->error;
			unset($this->$shouldBeSendTo->error);
			return false;
		
		}
	}

?>