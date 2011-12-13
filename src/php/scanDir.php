<?php
include "projectManager.php";

// scan for project directory
function scanProjDir($dir){
	$projectName = "NONE";
	$hasJS = false;
	$err = "";
	if( file_exists($dir) ){	
		if ($handle = opendir($dir)) {
		
			/* This is the correct way to loop over the directory. */
			while (false !== ($file = readdir($handle))) {
				if( ( $file != ".git" ) 		&& 
					( $file != "index.php" ) 	&& 
					( $file != "src" ) 			&&
					( $file != "." ) 			&& 
					( $file != ".." ) 			&& 
					( $file != "README" )		&& 
					( $file != "README.txt" )	){
						echo "THE PROJECT DIRECTORY IS : " . $file . "<BR>";
						$projectName = $file;
				}				
			}
			closedir($handle);
		}
	}else{
		echo "no projects";
		$err = "?err=NP";
	}
	if( $projectName != "NONE" ){
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
	}else{
		$err = "?err=NP";
	}
	if( $hasJS ){
		setCurrProj($projectName);
	}
	return $err;
}



$directoryToScan = "../../";

if( is_dir($directoryToScan) ){
	$err = scanProjDir($directoryToScan);
}else{
	echo "dir not found " . getcwd();
}
echo $err;
header("location:../../index.php" . $err);

?>