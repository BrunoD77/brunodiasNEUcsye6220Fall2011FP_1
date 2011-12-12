<?php
include "JavaScriptObject.php";

class FileView{
	public function getNumberOfFiles(){
		return $this->numberOfFiles;
	}
	
	public function getNumberOfJavaScripts(){
		return $this->numberOfJavaScripts;
	}
	
	public function getNumberOfNonJavaScripts(){
		return $this->numberOfNonJavaScripts;
	}
	
	public function getProjectLocation(){
		return $this->rp . "/" . $this->directoryName;
	}
	
	public function getProjectName(){
		return $this->directoryName;
	}
	
	public function getJavaScriptFilesArray(){
		return $this->javaScriptFiles;
	}
	
	public function getNonJavaScriptFilesArray(){
		return $this->nonJavaScriptFiles;
	}
	
	public function getListOfFiles(){
		return $this->listOfFiles;
	}
	
	public function scanProject(){
		$projDir = $this->getProjectName();
		if( !file_exists($projDir)){
			$projDir = "../../" . $this->getProjectName();
		}
		
		if( file_exists($projDir) ){		
			if ($handle = opendir($projDir)) {
				/* This is the correct way to loop over the directory. */
				while (false !== ($file = readdir($handle))) {
					if( $file != "." && $file != ".." ){
						$this->listOfFiles[$this->numberOfFiles] = $file;
						$this->numberOfFiles++;
					
						if( strrpos($file, ".js" ) > 0 ){
							$this->javaScriptFiles[$this->numberOfJavaScripts] = $file;
							
							
							// Create a javascript object.
							global $javaScriptObjects;							
							$javaScriptObjects[$this->numberOfJavaScripts] = new JavaScriptObject($projDir . "/" .$file);
							$this->numberOfJavaScripts++;							
						}else{
							$this->nonJavaScriptFiles[$this->numberOfNonJavaScripts] = $file;
							$this->numberOfNonJavaScripts++;						
						}
					}
				}
				closedir($handle);
			}	
		}
	}// end scanProject
	
	public function __construct($rpa, $dn){
		$this->rp = $rpa;
		$this->directoryName = $dn;
		$this->numberOfFiles = 0;
		$this->numberOfJavaScripts = 0;
		$this->numberOfNonJavaScripts = 0;
		$this->scanProject();
	}// end __construct;
	
	private $rp = "";
	private $directoryName = "";
	private $numberOfFiles;
	private $numberOfJavaScripts;
	private $numberOfNonJavaScripts;
	private $javaScriptFiles = array();
	private $listOfFiles     = array();
	private $nonJavaScriptFiles = array();
}
