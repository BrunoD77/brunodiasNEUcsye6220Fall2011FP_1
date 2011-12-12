<?php
include "projectManager.php";

// scan for project directory
function scanProjDir($dir){
	$projectName = "NONE";
	$hasJS = false;
	if( file_exists($dir) ){	
		if ($handle = opendir($dir)) {
			echo "Directory handle: $handle<BR>";
			echo "Files:<BR>";
		
			/* This is the correct way to loop over the directory. */
			while (false !== ($file = readdir($handle))) {
				echo "$file<BR>";
				if( ( $file != ".git" ) 		&& 
					( $file != "index.php" ) 	&& 
					( $file != "src" ) 			&&
					( $file != "." ) 			&& 
					( $file != ".." ) ){
						echo "THE PROJECT DIRECTORY IS : " . $file . "<BR>";
						$projectName = $file;
					}
			}
			closedir($handle);
		}
	}else{
		echo "no projects";
	}
	if( file_exists("../../" . $projectName) ){
		$dir = "../../" . $projectName;
		if ($handle = opendir($dir)) {
			echo "Directory handle: $handle<BR>";
			echo "Files:<BR>";
		
			/* This is the correct way to loop over the directory. */
			while (false !== ($file = readdir($handle))) {
				echo "$file<BR>";
				if( strrpos($file, ".js" ) > 0 ){
						echo "This is a java script file : " . $file . "<BR>";
						$hasJS = true;
					}
			}
			closedir($handle);
		}
	}
	if( $hasJS ){
		setCurrProj($projectName);
	}
}



$directoryToScan = "../../";

if( is_dir($directoryToScan) ){
	scanProjDir($directoryToScan);
}else{
	echo "dir not found " . getcwd();
}
header("location:../../index.php");

?>