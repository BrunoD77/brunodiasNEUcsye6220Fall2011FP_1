<?php
/** Global Variables View Class:
 * 	This class provides data for the user interface to accomplish the following listed in the Final Project Changes and Additions document
 *  dated: 11/30/11
 *
 *  Description:
 *  A user selects a particular global variable. Then the view shows where the varible is declared, initialized, changed, and used. 
 */
class GlobalVariablesView{
	
	private $globalVars = array();
	private $globalVarObjects = array();
	private $globVarCount = 0;
	
	public function getGlobalVarObjects(){
		return $this->globalVarObjects;
	}
	public function __construct(){
		global $fv;	
		if( $fv == null ){
			// echo "FILE VIEW IS NULL";
			$fv = new FileView();
		}
	}
	
	public function scanProjectForGlabalVariables(){
		// echo "<BR><BR><BR><BR>Start Scan for Global Variables!<BR>";
		global $javaScriptObjects;
		for($i = 0; $i < count($javaScriptObjects); $i++ ){
			$jsObj = $javaScriptObjects[$i];			
			//echo "count : " . count($this->globalVarObjects).", jsObj:count: ". $jsObj->getGVCount() . "<BR>";
			if( $jsObj->getGVCount() > 0 ){			
				$globalVars = $jsObj->getGlobalVars();
				for($j = 0; $j < count($globalVars); $j++){
					// echo "CREATING GV OBJECT : " . $globalVars[$j] . ", FILE: " . $jsObj->getObjectFile() . "<br>";
					$this->globalVarObjects[$this->globVarCount] = new GlobalVariable($globalVars[$j], $jsObj->getObjectFile() );	
					$this->globVarCount++;				
				}// for
			}// if
		}// for
		// echo "Scan for Global Variables complete!";
	}// function	
}// class

class GlobalVariable{
	private $variableName;
	private $declarationFile;
	private $initializedAt = "";
	private $valueChangedAt = array();
	private $valueChangedCount = 0;
	private $usedIn = array();
	private $usedCount = 0;
	
	public function getVariableName(){
		return $this->variableName;
	}
	public function getDeclarationFile(){
		return $this->declarationFile;
	}
	public function getInitializationPoint(){
		return $this->initializedAt;
	}
	public function getChangedPoints(){
		return $this->valueChangedAt;
	}
	public function getUsagePoints(){
		return $this->usedIn;
	}
	
	public function __construct($varName, $varFile){
		$this->variableName = $varName;
		$this->declarationFile = $varFile;		
		$this->scanForUsage($varName);
	}
	
	private function scanForUsage($vn){
		global $fv;
		$files = $fv->getJavaScriptFilesArray();
		//echo "***********FILE COUNT: " . count($files) . "<BR>";
		
		if( "" == $this->initializedAt ){
			$this->populateObj($vn, $fv->getProjectName(), $files);
			//echo $vn . "is INITIALIZED IN: " . $this->initializedAt . "<BR><BR><BR><BR>";
		}
	}
	
	private function populateObj($vn, $pn, $files){		
		$initFound = false;
		$valChangeCount = 0;
		$usedCount = 0;
		//echo "Populating " . $vn . "<BR>";
		for( $i = 0; !$initFound && $i<count($files); $i++ ){
			$file = $pn . "/" . $files[$i];
			//echo "<BR>CHECKING : " . $file;
			$file_handle = fopen($file, "r");			
			while (!feof($file_handle)) {
	   			$line = fgets($file_handle);
	   			$strPos = strpos($line, $vn); // find the variable name in the current line.
				
	   			if( $strPos != false ){ // found something.
	   				
	   				// Found the variable, so it must be used here!
	   				$this->usedIn[$usedCount] = $file;
					$usedCount++;
					
	   			//	echo "<BR>FOUND IN " . $file . " Full String: " . $line . " VAR IS USED IN THIS FILE. <BR>USECOUNT = " . $usedCount;
	   				
	   			//	echo "<BR>STARTING TO ANALIZE LINE....";
					$strPos = strpos($line, " = null");// find out if there's a null initialization.
					if( $strPos == false ){ // if NOT, 
						// Then the variable surelly changed value here.
						$this->valueChangedAt[$valChangeCount] = $file;
						$valChangeCount++;
						
					//	echo "<BR> VAR HAS CHANGED VALUE HERE: " . $file . "<BR>Found Match!!!<BR>";
						
						// If the variable is declared here, then this is the initialization point as well:
					//	echo " ___ SUBSTR: '" . substr($line, 0, 4) . "'<BR>";
						if( ( "var " == substr($line, 0, 4) ) ){
							$this->initializedAt = $file;
						//	echo "FILE IS INITIALIZED HERE: " . $file . "<BR>";
						}
						$strPos = strpos($line, " = new");// if there's a 'new' assignment, then surelly this is the init point.
						if($this->initializedAt == "" &&  $strPos != false ){
							// Initialization must be done here.
							$this->initializedAt = $file;
						//	echo "FILE IS INITIALIZED HERE: " . $file . "<BR>";
						}
						$strPos = strpos($line, " = this");// this assignment must mean init.
						if($this->initializedAt == "" &&  $strPos != false ){
							// Initialization must be done here.
							$this->initializedAt = $file;
						//	echo "FILE IS INITIALIZED HERE: " . $file . "<BR>";
						}//if
						
					}//if
					
	   			}//if
	   			
	   		}
			fclose($file_handle);
		}
	}
}
?>