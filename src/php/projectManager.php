<?php
	// This file will manage projects for the JSSCV
	$pathFromRoot = "src/php/";
	
	// setCurrentProject
	function setCurrentView($viewName){
		$fh = fopen("currView.viw", 'w') or die("PHP: Can't update current project file!<br>");
		fwrite($fh, $viewName);
		fclose($fh);
	}
	
	// getCurrentView
	function getCurrentView(){
		$currView = "FV";// IV, CV, GVV, FCV, RV
		$currViewFile = "currView.viw";
		
		// check if a currProj.proj file exists
		global $pathFromRoot;
		if( file_exists( $pathFromRoot . $currViewFile ) ){
			$currViewFile = $pathFromRoot . $currViewFile;
		}
		if( file_exists($currViewFile) ){
			$fh = fopen($currViewFile, 'r');
			while( !feof($fh) ){
				global $currView;
				$currView = fgets($fh);		
			}	
			fclose($fh);
		}
		return $currView;
	}
	
	// setCurrentProject
	function setCurrProj($projName){
		$fh = fopen("currProj.proj", 'w') or die("PHP: Can't update current project file!<br>");
		fwrite($fh, $projName);
		fclose($fh);
	}
	
	// getCurrentProject
	function getCurrProj(){
		$currProj = "NONE";
		$currProjFile = "currProj.proj";
		
		// check if a currProj.proj file exists
		global $pathFromRoot;
		if( file_exists( $pathFromRoot . $currProjFile ) ){
			$currProjFile = $pathFromRoot . $currProjFile;
		}
		if( file_exists($currProjFile) ){
			$fh = fopen($currProjFile, 'r');
			while( !feof($fh) ){
				$currProj = fgets($fh);		
			}	
			fclose($fh);
		}
		return $currProj;
	}
	
	
	
	
	
	
	
	
/*	// getAvailableFiles
	function getAvailableFiles(){
		$projectNames = array();
		$projectsDir = getCurrProj(); // Will return NONE if no valid proj is found.
		if( $projectsDir != "NONE" && 
			( 	!file_exists( $projectsDir ) ||
				!is_dir( $projectsDir ) ) ){
				$projectsDir = $path . $projectsDir;
		}
		if( file_exists($projectsDir) ){	
			if ($handle = opendir($projectsDir)) {
				echo "Directory handle: $handle\n";
				echo "Files:\n";
			
				/* This is the correct way to loop over the directory. *
				while (false !== ($file = readdir($handle))) {
					echo "$file<BR>";
				}
				closedir($handle);
			}
		}else{
			echo "no projects";
		}
		return $projectNames;
	}
	
	// saveNewProject
	function saveProject($projName){
		if( !file_exists("projects") ){
			mkdir("projects");
		}
		$fh = fopen( "projects/" . $projName . ".proj", 'w') or die("PHP: Can't create project file!<br>");		
		fclose($fh);
	}
	
	
	
	// checkDuplicateNames
	function alreadyUsed($projName){
		return file_exists("projects/". $projName . ".proj");
	}
	
	// readFiles
	function readFiles(){
		$directory = getCurrProj();
		if( file_exists($directory) ){	
			if ($handle = opendir($directory)) {
				echo "Directory handle: $handle\n";
				echo "Files:\n";
			
				/* This is the correct way to loop over the directory. *
				while (false !== ($file = readdir($handle))) {
					echo "$file\n";
					$projectNames[] = $file;
				}
				closedir($handle);
			}
		}else{
			echo "no projects";
		}
		return $projectNames;
		
	}*/
?>