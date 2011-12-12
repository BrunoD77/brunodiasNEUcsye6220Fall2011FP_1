<html>
<head>
<title>Bruno Dias - Final Project</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="src/css/bdias.css" rel="stylesheet" type="text/css">
<script src="http://code.jquery.com/jquery-latest.js">
	$(document).ready(function(){
		$("a[class='bdias-views-inheritanceLink']").hover(function() {
			$('bdias-views-inheritance').css("visibility", "visible")
		},
		function() {
			$('bdias-views-inheritance').css("visibility", "hidden")
		});
	});
</script>
<script language="JavaScript">
	function show(name){
		document.getElementById('bdias-views-inheritance-'+name).style.visibility= 'visible';
	}
	function hide(name){
		document.getElementById('bdias-views-inheritance-'+name).style.visibility= 'hidden'
	}
</script>
</head>
<body>
<?php 
include "src/php/header.php";
include "src/php/projectManager.php";
include "src/php/FileView.php";
include "src/php/GlobalVariableView.php";
include "src/php/ResourcesView.php";
include "src/php/FunctionCallView.php";

// getCurrProj() does File IO, so in an effort to speed things up, I'm only making this call once per reload.
$projDir = getCurrProj();
$currentView = getCurrentView();
$javaScriptObjects = array();

if( $projDir == "NONE" ){
	echo "<div class='bdias-welcome'>
			<div style='position:absolute; left:240px; top:93px; width:488px; height:342px; z-index:1'> 
  				The first thing you should know about this web-application is that you DON'T 
  				need to copy / paste your source code into it. Follow this steps to get started:</div>
			<div style='position:absolute; left:150px; top:55px; width:447px; height:27px; z-index:2'>
				Welcome	to the Java Script Source Code Visualizer:</div>
			<div style='position:absolute; left:260px; top:132px; width:436px; height:288px; z-index:3'>
  				<p>1) Copy the folder where your Java Script Source code resides. For this final 
    				project, this would be the 'MainMenu' directory.</p>
  				<p>2) Paste the 'MainMenu' (or project) directory into the same folder where 
    				this PHP file exists. If you are not sure where to paste the project directory, here is the path of this php file: <BR><strong>" . getcwd() . "</strong></p>
  				<p>3) Click on the scan button.</p>
				<form name='scan' action='src/php/scanDir.php'><input type='submit' name='scan' value='Scan'/></form></div></div>";
}else{
?>
	<div class="bdias-buttons">
<?php 	echo ($currentView!="FV") ?"<a class='bdias-button-inactive' href='src/php/changeViews.php?view=FV' >Files View</a>"           :"<span class='bdias-button-active'>Files View</span>";
		echo ($currentView!="IV") ?"<a class='bdias-button-inactive' href='src/php/changeViews.php?view=IV' >Inheritance View</a>"     :"<span class='bdias-button-active'>Inheritance View</span>";
		echo ($currentView!="CV") ?"<a class='bdias-button-inactive' href='src/php/changeViews.php?view=CV' >Composition View</a>"     :"<span class='bdias-button-active'>Composition View</span>";
		echo ($currentView!="GVV")?"<a class='bdias-button-inactive' href='src/php/changeViews.php?view=GVV'>Global Variables View</a>":"<span class='bdias-button-active'>Global Variable View</span>";
		echo ($currentView!="FCV")?"<a class='bdias-button-inactive' href='src/php/changeViews.php?view=FCV'>Function Calls View</a>"  :"<span class='bdias-button-active'>Function Calls View</span>";
		echo ($currentView!="RV") ?"<a class='bdias-button-inactive' href='src/php/changeViews.php?view=RV' >Resources View</a>"       :"<span class='bdias-button-active'>Resources View</span>";
?>
	</div>
<?php
	
	
	$fv = new FileView( getcwd(), $projDir );
	$gvv = new GlobalVariablesView(); 
	$rv = new ResourcesView();
	$fcv = new FunctionCallView();
	
	/********************* File View *****************************/
	if( $currentView == "FV" ){
		
		// I NOW HAVE ACCESS TO ALL KINDS OF FILE VIEW INFO... TIME TO PLAY WITH UI.
		echo "<div class='bdias-views'><div class='bdias-view-header'>Files View</div><div class='bdias-view-body'>";
		echo "<p class='bdias-description'>This view provides an overview of your project. Here you'll find vauable information regarding file";
		echo " structure of your JavaScript project including path and file information.</p>";
		echo "<p><span class='bdias-label'>Project Name: </span><span class='bdias-result'>" . $fv->getProjectName() . "</span></p>";
		echo "<p><span class='bdias-label'>Project Path:</span><span class='bdias-result'>" . $fv->getProjectLocation() . "</span></p>";
		echo "<p><span class='bdias-label'>Number of Files:</span><span class='bdias-result'>" . $fv->getNumberOfFiles() . "</span></p>";
		echo "<p><span class='bdias-label'>Number of Java Script Files:</span><span class='bdias-result'>" . $fv->getNumberOfJavaScripts() . "</span></p>";
		echo "<p><span class='bdias-label'>Number of Non-Java Script Files:</span><span idclass'bdias-result'>" . $fv->getNumberOfNonJavaScripts() . "</span></p>";
		echo "<p><div class='bdias-views-fileView1'>";
		echo "<span class='bdias-label'>Java Script Files:</span><BR>";
		echo "<div class='bdias-views-fileView-fileList'><ul>";
		
		$files = $fv->getJavaScriptFilesArray();
		$rows = count($files)/2;
		for($i = 0; $i < count($files); $i++ ){
			if( $i == $rows ){
				echo "</ul></div><div class='bdias-views-fileView-fileList'><ul>";
			}
			echo "<li><a href='" . $projDir . "/" . $files[$i] . "'  target='_blank'>" . $files[$i] . "</a></li>";
		}
		echo "</ul></div></div>";
		echo "</p>";
		
		echo "<p><div class='bdias-views-fileView2'>";
		echo "<span class='bdias-label'>Other Files:</span><BR><div class='bdias-views-fileView-fileList'>";
		
		$files = $fv->getNonJavaScriptFilesArray();
		echo "<ul>";
		$rows = count($files)/2;
		for($i = 0; $i < count($files); $i++ ){
			if( $i == $rows ){
				echo "</ul></div><div class='bdias-views-fileView-fileList'><ul>";
			}
			echo "<li class='bdias-jsfiles'><a href='" . $projDir . "/" . $files[$i] . "'  target='_blank'>" . $files[$i] . "</a></li>";
		}
		echo "</ul>";
		echo "</div></div></p>";
		
		echo "</div></div>";
		
		/********************* Global Variable View *****************************/	
	}else if( $currentView == "GVV"){ // Global Variable View
		// echo "SCAN<BR><BR><BR>";
		$gvv->scanProjectForGlabalVariables();
		echo "<div class='bdias-views'><div class='bdias-view-header'>Global Variables View</div><div class='bdias-view-body'>";
		echo "<p class='bdias-description'>This view provides information regarding the usage of global variables in this JavaScript project.<BR>";
		echo "More specifically, this view shows where all global variables have been declared, initialized, changed and used.</p>";
		$globVars = $gvv->getGlobalVarObjects();
		for( $i = 0; $i<count($globVars); $i++){
			$gv = $globVars[$i];
			echo "<p><span class='bdias-label'>Global Variable Name: </span><span class='bdias-result'>" . $gv->getVariableName() . "</span></p>";
			echo "<ul>";
			echo "<li><p><span class='bdias-label'>Declared in:</span><span class='bdias-result'>" . $gv->getDeclarationFile() . "</span></p></li>";
			echo "<li><p><span class='bdias-label'>Initialized in:</span><span class='bdias-result'>" . $gv->getInitializationPoint() . "</span></p></li>";
			echo "<li><span class='bdias-label'>Changed in:</span><ul>";
			$changedAt = $gv->getChangedPoints();
			for($j = 0; $j < count($changedAt); $j++ ){
				echo "<li><p><span class='bdias-result'>" . $changedAt[$j] . "</span></p></li>";
			}
			echo "</ul></li>";
			echo "<li><span class='bdias-label'>Used in:</span><ul>";
			$usedAt = $gv->getUsagePoints();
			for($j = 0; $j < count($usedAt); $j++ ){
				echo "<li><p><span class='bdias-result'>" . $usedAt[$j] . "</span></p></li>";
			}
			echo "</ul></li>";
			echo "</ul>";
			echo "</span></p>";
		}
		echo "</div></div>";
		
		/********************* Resources View *****************************/
	}else if( $currentView == "RV"){		
		echo "<div class='bdias-views'><div class='bdias-view-header'>Resources View</div><div class='bdias-view-body'>";
		echo "<p class='bdias-description'>This view provides information about resources used in this JavaScript project.</p>";
		echo "<p><span class='bdias-label'>Resources: </span><span class='bdias-result'>";
		echo "<ul>";
		$resList = $rv->getResources();
		for( $i = 0; $i<count($resList);$i++){
			if( $resList[$i] != "" ){
				echo "<li><a href='" . $projDir . "/" . $resList[$i] . "'  target='_blank'>" . $resList[$i] . "</a></li><BR>";
			}
		}
		echo "</ul></span></p>";
		
		/********************* Function Call View *****************************/
	}else if( $currentView == "FCV" ){
		echo "<div class='bdias-views'><div class='bdias-view-header'>Function Call View</div><div class='bdias-view-body'>";
		echo "<p class='bdias-description'>This view provides information about Function Calls made in each Java Script Object. ";
		echo "Select a class to see a list of functions for that class. Then Select a particular function to find out where this ";
		echo "function is used.</p>";
		echo "<strong>COLOR CODE MAP:<BR><font color='#900'>&nbsp;&nbsp;&nbsp;LOCAL FUNCTION</font><BR><font color='#009'>&nbsp;&nbsp;&nbsp;CONSTRUCTOR</font><br><font color='#090'>&nbsp;&nbsp;&nbsp;FUNCTION IS USED IN THIS CLASS, BUT SOME OTEHR CLASS OWNS IT!</font><br></strong><br>";
		echo "<p><span class='bdias-label'>Classes: </span><span class='bdias-result'>";
		$classes = $fcv->getClasses();
		for( $i = 0; $i<count($classes);$i++){
			$class = $classes[$i];
			if( $class != null && $class->getClassName() != "" ){				
				$functions = $class->getFunctions();
				echo "<p>Class Name:<a href='#'>" . $class->getClassName() . "</a><BR>Functions used in this class:<ul>";
				for( $j = 0; $j<count($functions);$j++){
					$type = substr($functions[$j], 0, strpos($functions[$j], "-") );
					if( $type == "o" ){
						$color = "#009900";
					}else if( $type == "c" ){
						$color = "#000099";
					}else if( $type == "n"){
						$color = "#990000";
					}else{
						$color = "#333333";	
					}
					$funcName = substr($functions[$j], 2, strlen($functions[$j]));
					echo "<li><a href='#'><font color='" . $color . "'>" . $funcName . "</font></a></li>";				
				}
				echo "</ul></p>";
			}
		}
		echo "</span></p>";
		
		/********************** Inheritance View *********************************/
	}else if( $currentView == "IV" ){
		echo "<div class='bdias-views'><div class='bdias-view-header'>Inheritance View</div><div class='bdias-view-body'>";
		echo "<p class='bdias-description'>This view provides information about Inheritance in each Java Script Object. ";
		echo "Select a class to see if it inherits from a parent class and also to see which classes inherit from the ";
		echo "selected class.<BR>Please hover your mouse pointer on top of the Class Name Link to show a diagram. Click on the link to show the source code in another page.</p>";
		echo "<p><span class='bdias-label'>Classes: </span><BR>";
		$classes = $fcv->getClasses();
		
		for( $i = 0; $i < count($classes); $i++ ){
			$hasChildren = false;
			$class = $classes[$i];
			$className = trim($class->getClassName());
			if( $className != "" ){
				echo "<span class='bdias-result'>Class Name:<a href='" . $class->getDefinedInFile() . "' target='_blank'  onmouseover=javaScript:show('" . $className . "'); onmouseout=javaScript:hide('" . $className . "')>" . $className . "</a></span><BR>";
				$daddy = ( $class->getParent() != "" )?$class->getParent():"NONE";
				echo "<ul><li>Parent: " . $daddy . "</li>";
				echo "<li>Children: ";
				$children = $class->getChildren();
				if( count($children) == 0 ){
					echo "NONE";
				}else{
					echo "<ul>";
					for( $j = 0; $j < count($class->getChildren()); $j++){
						echo "<li>" . $children[$j] . "</li>";
					}
					$hasChildren = true;
					echo "</ul>";
				}	
				echo "</li></ul></p><BR>";
			}
			
			echo "<div class='bdias-views-inheritance' id='bdias-views-inheritance-" . $className . "'>";
			if( $class->getParent() != "" ){
				echo "<div id='bdias-views-inheritance-parent'>Class: " . $class->getParent() . "<BR><strong>PARENT</strong></div>";
				echo "<div id='bdias-views-inheritance-vertLine'><img src='src/images/inheritance.gif' width='100' height='100'></div>";
			}
			echo "<div id='bdias-views-inheritance-class'>Class: " . $className . "<BR><strong>CURRENTLY SELECTED CLASS</strong></div>";
			if( $hasChildren ){
				for( $j = 0; $j < count($children); $j++){
					if( $j == 0 ){
						echo "<div id='bdias-views-inheritance-tiltedRightLine'><img src='src/images/child-left.gif' width='100' height='100'></div>";	
					}
					if( $j == 1 ){
						echo "<div id='bdias-views-inheritance-tiltedLeftLine'><img src='src/images/child-Right.gif' width='100' height='100'></div>";
					}					
					echo "<div id='bdias-views-inheritance-child" . ($j+1) . "'>Class: " . $children[$j] . "<BR><strong>CHILD " . ($j+1) . "</strong></div>";
				}
				
			}
			echo "</div>";
			
		}
		
		
		/**************************** Composition View ****************************/
	}else if( $currentView == "CV" ){
		echo "<div class='bdias-views'><div class='bdias-view-header'>Composition View</div><div class='bdias-view-body'>";
		echo "<p class='bdias-description'>This view provides information about Composition in each Java Script Object. ";
		echo "Select a class to see if it composed of other objects.</p>";
		echo "<p><span class='bdias-label'>Classes: </span><span class='bdias-result'>";
		echo "<ul>";
		$classes = $fcv->getClasses();
		
		for( $i = 0; $i < count($classes); $i++ ){
			$class = $classes[$i];
			echo "Class Name: " . $class->getClassName() . "<BR><ul>";
			echo "<li>Composed Of: " . count($class->getComposition()) . "</li><ul>";
			$composition = $class->getComposition();
			for( $j = 0; $j < count($class->getComposition()); $j++ ){
				echo "<li>" . $composition[$j] . "</li>";
			}
			echo "</ul>";
			echo "</ul><BR>";
		}
	}
}
?>
</body>
</html>
