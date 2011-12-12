<?php
	include "projectManager.php";
	echo "changeViews.php";
	
	if( isset($_GET["view"]) ){
		setCurrentView($_GET["view"]);
	}
header("Location:../../index.php");