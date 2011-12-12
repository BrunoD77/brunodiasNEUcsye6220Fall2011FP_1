<?php
// This class will provide information regarding inheritance, composition, and function calls
class ClassObj{
	private $definedInFile;
	private $className;
	private $functions = array();
	private $parent;
	private $children = array();
	private $composedOf = array();
	private $usedIn = array();
	
	
	private $numberOfOpenComm = 0;
	private $numberOfCloseComm = 0;
	private $inComment = false;
	
	public function __construct($cn, $defInFile, $fncs, $daddy){
		$this->className = $cn;
		$this->definedInFile = $defInFile;
		$this->functions = $fncs;
		$this->parent = $daddy;
	}
	
	public function getClassName(){
		return $this->className;
	}
	public function getDefinedInFile(){
		return $this->definedInFile;
	}
	public function getParent(){
		return $this->parent;
	}
	public function getFunctions(){
		return $this->functions;
	}
	public function getChildren(){
		return $this->children;
	}
	public function getComposition(){
		return $this->composedOf;
	}
	public function getUsedIn(){
		return $this->usedIn;
	}
	
	public function scanForUsage(){
		global $fv;
		$files = $fv->getJavaScriptFilesArray();

		for( $i = 0; $i < count($files); $i++ ){
			$file = $files[$i];			
			$fh = fopen($fv->getProjectName() . "/" . $file, 'r');
			while(!feof($fh)){
				$line = fgets($fh);
				if( !$this->inComment($line) && ("" != $this->className) ){
					if( strstr( $line, $this->className ) ){				    
						if( strstr( $line, ("function " . $this->className . "(") ) ){
							// This must be the constructor
							$functionName = substr( $line, strlen("function "), strlen($line) );
							$functionName = substr( $functionName, 0, strpos($functionName, "(" ) );						
							$this->functions[count($this->functions)] = "c-" . $functionName;
						}else if( strstr( $line, "this." ) &&
							strstr( $line, $this->className ) ){
							$functionName = substr($line, strpos($line, "this.")+strlen("this."), strlen($line) );
							$functionName = substr($functionName, 0, strpos($functionName, $this->className) );														
							if($this->definedInFile == $fv->getProjectName() . "/" .$file){
								// These are functions that belong to this class
								$this->functions[count($this->functions)] = "o-" . $functionName;
							}else{
								$this->usedIn[count($this->usedIn)] = $functionName;
							}
						}
						
					}
					// find composition (what objects are used in this class)
					if( $fv->getProjectName() . "/" . $file == $this->definedInFile ){
						// find composition
						if( strstr( $line, "new " ) && !(strstr($line, "prototype") ) ){
							$objName = substr($line, strpos($line, "new ")+strlen("new "), strlen($line) -1 );
							$endChar = ';';
							if( preg_match("/\b\(\b/i", $objName ) || strstr($objName, "()") ){
								$endChar = '(';
							}							
							$objName = substr($objName, 0, strpos($objName, $endChar) );
							$this->composedOf[count($this->composedOf)] = $objName;
						}
					} 
					// find children
					if( strstr($line, ".prototype = new ".$this->className ) ){
						$child = substr($line, 0, strpos($line, ".prototype") );
						$this->children[count($this->children)] = $child;  
					}	
				}
			}
			fclose($fh);
		}
		$this->functions = array_unique($this->functions);
	}

	private function inComment($line){
		if( preg_match("/\/\//", $line ) && !$this->inComment ){ // This is a java script comment that is of form '//' - so ignore
			$this->inComment = true;
		}else{
			 if( ( 0 < ( $this->numbOfOpenComm = preg_match_all("/\/\*/", $line, $out, PREG_PATTERN_ORDER ) ) ) && !$this->inComment ){ // This is a javaScript comment "START" of type '/*' - so ignore till the end of comment!
				if( $this->numbOfOpenComm == 1 ){		
					$this->inComment = true;
				}
			}
			if ( ( 0 < ( $this->numberOfCloseComm = preg_match_all("/\*\//", $line, $out, PREG_PATTERN_ORDER) ) ) && $this->inComment ){ // This is the "END" of the comment of type '*/' - so start processing again.
				if( $this->numberOfCloseComm == 1 ){
					$this->inComment = false;
				}else{
					if( $this->numberOfCloseComm == $this->numberOfOpenComm ){
						$this->inComment = false;
					}
				}
			} 
			return $this->inComment;
		}
	}
	public function getFunctionUsage($functionName){
		$usedIn = array();
		global $fv;
		$files = $fv->getJavaScriptFilesArray();

		for( $i = 0; $i < count($files); $i++ ){
			$file = $files[$i];			
			$fh = fopen($fv->getProjectName() . "/" . $file, 'r');
			while(!feof($fh)){
				$line = fgets($fh);
				if( !$this->inComment($line) && ("" != $this->className) && strstr($line, $functionName) ){
					$usedIn[count($usedIn)] = $this->className;
				}
			}
			fclose($fh);
		}
		$usedIn = array_unique($usedIn);
		return $usedIn;
	}
}
?>