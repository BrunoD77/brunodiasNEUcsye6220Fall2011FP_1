<?php
include "ClassObj.php";
class JavaScriptObject{
		
	/*Code to handle GV*/
	private $globalVars = array();
	private $gvCount = 0;
	
	/* vars for file identification */
	private $fileOfObject;
	
	/* vars for Resource Identification */
	private $resourceList = array();
	private $resourceCount = 0;
	
	/* vars for information regarding function calls, inheritance and composition */
	private $classes = array();
	private $classCount = 0;
	
	// Getters for class Objects array
	public function getClasses(){
		return $this->classes;
	}
	public function getClassCount(){
		return $this->classCount;
	}
	
	// Getters for Glob Vars
	public function getGVCount(){
		return $this->gvCount;
	}
	public function getGlobalVars(){
		return $this->globalVars;
	}
	
	// Getters for file info
	public function getObjectFile(){
		return $this->fileOfObject;
	}
	
	// Getters for Resource info
	public function getResourceList(){
		return $this->resourceList;
	}
	public function getResourceCount(){
		return $this->resourceCount;
	}
	
	
	// private $usedList = array();
	
	public function __construct($fp){
		$fileOfObject = $fp;
		$this->parseFile($fp);
	}
	// Setters and Getters
	
	// Parse the file associated with this object
	private function parseFile($file){
		$globalInitInSameFile = "false"; // reset the flag.
		$inComment = false; // flag that let's us know if we are parsing comment lines.
		$braceCount = 0; 	// counter to keep track of brace levels in the code;
		$file_handle = fopen($file, "r");
		
		$class = "";
		$functions = array();
		$parent = "";
		$numberOfOpenComm = 0;
		$numberOfCloseComm = 0;
		while (!feof($file_handle) ){
	   		$line = fgets($file_handle);
			
			if( preg_match("/\/\//", $line ) && !$inComment ){ // This is a java script comment that is of form '//' - so ignore
				continue;
			} else {
				
				if( ( 0 < ( $numbOfOpenComm = preg_match_all("/\/\*/", $line, $out, PREG_PATTERN_ORDER ) ) ) && !$inComment ){ // This is a javaScript comment "START" of type '/*' - so ignore till the end of comment!
					if( $numbOfOpenComm == 1 ){		
						$inComment = true;
					}
					//echo "InCommnet<BR>";
				}
				if ( ( 0 < ( $numberOfCloseComm = preg_match_all("/\*\//", $line, $out, PREG_PATTERN_ORDER) ) ) && $inComment ){ // This is the "END" of the comment of type '*/' - so start processing again.
					if( $numberOfCloseComm == 1 ){
						$inComment = false;
					}else{
						if( $numberOfCloseComm == $numberOfOpenComm ){
							$inComment = false;
						}
					}
					//echo "NOT InComment<BR>";
				} 
				// If we are not parsing comment lines, then:
				if( !$inComment ){
				
					// Count parenthesis - so that we know where in the class structure we are.
					if( preg_match("/{/", $line ) ){
						$braceCount++;
					}
					if( preg_match("/}/", $line ) ){
						$braceCount--;
					}
								
					if( preg_match("/\bvar\b/i", $line) && $braceCount == 0 ){
						$gv = substr ( $line, strcmp("var", $line), strlen($line) - strlen("var ") );
						$endLenght = strlen(strstr($gv, "="));
						if( $endLenght <= 0){
							$endLenght = strlen(strstr($gv, ";"));
						}
						$gv = substr ($gv, 0, strlen($gv) - $endLenght);
						$this->globalVars[$this->gvCount] = $gv;
						$this->gvCount++;
						$this->fileOfObject = $file;
					}
					
					// Check if there are resources in this line
					//echo $line . "<BR>";
					if( strstr($line, "src:") ){
						$res = substr ( $line, strpos($line, "src:"), strlen($line) );
						$res = substr ( $res, strpos($res, "'")+1, strlen($res) );
						$res = substr ( $res, 0, strpos($res, "'") );
						$this->resourceList[$this->resourceCount] = $res;
						$this->resourceCount++;						
					}
										
					// Work on indentifying Classes and their functions, inheritance and composition.					
					// Check if the function is a Constructor - This will give us the class name as well.
					if( preg_match("/\bfunction\b/i", $line ) && $braceCount == 0 ){
						// echo "<font color='#000099'><strong>Found a Constructor:</strong></font><BR>";
						$class = substr ( $line, strcmp("function", $line), strlen($line) - strlen("function ") );
						if( strstr( $class, "()" ) ){
							$class = substr ( $class, 0, strlen($class) - strlen("()") );
						}						
						//echo "<strong><font color='#009900'>Class : " . $class . "</font></strong><BR>";						
					}
					
					if( preg_match("/\bfunction\b/i", $line ) && $braceCount == 1 ){
						$functionName = substr($line, strpos($line, "this.")+strlen("this."), strlen($line));
						$functionName = substr($functionName, 0, strpos($functionName, "= function") );
						$functions[count($functions)] = "n-" . $functionName;
						//echo "<font color='#990000'>Found a function</font><BR>";						
						// $functionName = substr( $line, strspn("this.", strlen($line) - 5 - strrchr( $line , '=') ) );
						//echo "<font color='#006600'><strong>Function Name: ". $functionName . "</strong></font><BR>";
						//$functions[count($functionCount)] = $functionName;
					}// if
					
					if( preg_match("/\b.prototype\b/i", $line ) && $braceCount == 0 ){
						$res = substr ( $line, 0, strpos($line, ".prototype") );
						$class = trim($class, " ");
						if( $class == $res ){
							$parent = substr ( $line, strpos($line, "new ") + strlen("new "), strlen($line));
							$endChar = ';';
							if( $parent != "" ){
								if( preg_match("/\b\(\b/i", $parent ) ){
									$endChar = '\(';
								}
								$parent = substr ( $parent, 0, strpos($parent, $endChar) );
							}
						}					
						// Problems here - make sure you save the function somehow .
					}
						
				}// if
				
				// $fileContents .= $line . "<BR>";
			}// if
		}// while
		$cObj = new ClassObj($class, $file, $functions, $parent);
		$this->classes[$this->classCount] = $cObj;
		$this->classCount++;
		
		fclose($file_handle);
	}// function 
}
?>