<?php
/** Resources View Class:
 * 	This class provides data for the user interface to accomplish the following listed in the Final Project Changes and Additions document
 *  dated: 11/30/11
 *
 *  Description:
 *  This view shows a list of project's resources and the associated URLs. 
 */
class ResourcesView{
	private $resources = array();
	private $resourcesCount = 0;
	
	public function __construct(){
		global $fv;	
		if( $fv == null ){
			// echo "FILE VIEW IS NULL";
			$fv = new FileView();
		}
	}
	
	public function getResources(){
		global $javaScriptObjects;
		for($i = 0; $i < count($javaScriptObjects); $i++ ){
			$jsObj = $javaScriptObjects[$i];
			if( 0 < $jsObj->getResourceCount() ){	
				$list = $jsObj->getResourceList();
				for( $j=0; $j < count($list); $j++ ){	
					$this->resources[$this->resourcesCount] = $list[$j];
					$this->resourcesCount++;
				}// for
			}// if
		}// for
		return $this->resources;
	}// function	
}// class
?>