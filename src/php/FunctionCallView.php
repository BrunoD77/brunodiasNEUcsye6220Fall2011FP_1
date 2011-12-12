<?php
/** Function Call View Class:
 * 	This class provides data for the user interface to accomplish the following listed in the Final Project Changes and Additions document
 *  dated: 11/30/11
 *
 *  Description:
 *  A user selects a class and then a function in that class. Then the view shows where the function is used. 
 */
class FunctionCallView{
	private $classes = array();
	private $classesCount = 0;
	
	public function __construct(){
		global $javaScriptObjects;
		if( count($javaScriptObjects) > 0 ){
			// complete scan for functions
			$this->scanProjectForFunctions();
		}
	}
	
	private function scanProjectForFunctions(){
		global $javaScriptObjects;		
		for( $i = 0; $i<count($javaScriptObjects); $i++){
			$jso = $javaScriptObjects[$i]; // each jso has information about classes in the javaScript Object (file).
			if( $jso->getClassCount() > 0 ){
				$cs = $jso->getClasses();
				for( $j = 0; $j<count($cs); $j++ ){
					$cls = $cs[$j];
					$cls->scanForUsage();
					$this->classes[$this->classesCount] = $cls;
					$this->classesCount++;										
				}
			} // if
		}
	}
	public function getClasses(){
		return $this->classes;
	}
}
?>