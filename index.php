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

<script>
	$(function() {
		//scrollpane parts
		var scrollPane = $( ".scroll-pane" ),
			scrollContent = $( ".scroll-content" );
		
		//build slider
		var scrollbar = $( ".scroll-bar" ).slider({
			slide: function( event, ui ) {
				if ( scrollContent.width() > scrollPane.width() ) {
					scrollContent.css( "margin-left", Math.round(
						ui.value / 100 * ( scrollPane.width() - scrollContent.width() )
					) + "px" );
				} else {
					scrollContent.css( "margin-left", 0 );
				}
			}
		});
		
		//append icon to handle
		var handleHelper = scrollbar.find( ".ui-slider-handle" )
		.mousedown(function() {
			scrollbar.width( handleHelper.width() );
		})
		.mouseup(function() {
			scrollbar.width( "100%" );
		})
		.append( "<span class='ui-icon ui-icon-grip-dotted-vertical'></span>" )
		.wrap( "<div class='ui-handle-helper-parent'></div>" ).parent();
		
		//change overflow to hidden now that slider handles the scrolling
		scrollPane.css( "overflow", "hidden" );
		
		//size scrollbar and handle proportionally to scroll distance
		function sizeScrollbar() {
			var remainder = scrollContent.width() - scrollPane.width();
			var proportion = remainder / scrollContent.width();
			var handleSize = scrollPane.width() - ( proportion * scrollPane.width() );
			scrollbar.find( ".ui-slider-handle" ).css({
				width: handleSize,
				"margin-left": -handleSize / 2
			});
			handleHelper.width( "" ).width( scrollbar.width() - handleSize );
		}
		
		//reset slider value based on scroll content position
		function resetValue() {
			var remainder = scrollPane.width() - scrollContent.width();
			var leftVal = scrollContent.css( "margin-left" ) === "auto" ? 0 :
				parseInt( scrollContent.css( "margin-left" ) );
			var percentage = Math.round( leftVal / remainder * 100 );
			scrollbar.slider( "value", percentage );
		}
		
		//if the slider is 100% and window gets larger, reveal content
		function reflowContent() {
				var showing = scrollContent.width() + parseInt( scrollContent.css( "margin-left" ), 10 );
				var gap = scrollPane.width() - showing;
				if ( gap > 0 ) {
					scrollContent.css( "margin-left", parseInt( scrollContent.css( "margin-left" ), 10 ) + gap );
				}
		}
		
		//change handle position on window resize
		$( window ).resize(function() {
			resetValue();
			sizeScrollbar();
			reflowContent();
		});
		//init scrollbar size
		setTimeout( sizeScrollbar, 10 );//safari wants a timeout
	});
	</script>
<script language="JavaScript">
	function show(name){
		document.getElementById('bdias-views-'+name).style.visibility= 'visible';
	}
	function hide(name){
		document.getElementById('bdias-views-'+name).style.visibility= 'hidden';
	}
	function showCode(name){
		document.getElementById('bdias-views-'+name).style.visibility= 'visible';
		document.getElementById('bdias-views-'+name).style.width='400';
		document.getElementById('demo').style.visibility= 'visible';
	}
	function hideCode(name){
		document.getElementById('bdias-views-'+name).style.visibility= 'hidden';
		document.getElementById('bdias-views-'+name).style.width='0';
		document.getElementById('demo').style.visibility= 'hidden';
		
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
	echo "<div class='bdias-welcome'>";
	if( isset($_GET["err"]) && $_GET["err"] == "NP"){
		echo "<div style='position:absolute; left:80px; top:40px; width:554px; height:69px; z-index:3; background: #FF9900; layer-background-color: #FF9900; border: 1px none #000000;'>
			<font color='#660000' size='+2'><strong><em>ERROR!:</em></strong></font> <font size='+2' face='Georgia, Times New Roman, Times, serif'>Could not open the project directory. Please follow the instructions detailed below.</font></div>";
	}
	echo "  <div style='position:absolute; left:80px; top:100px; width:600px; height:264px; z-index:1'> 
  				<p><strong><font color='#660000' size='+2'><em>Welcome</em></font></strong>
  				<font size='+1'> 
    				to the JavaScript Source Code Visualizer. This web-application will enable 
    				&#8220;you&#8221;, the developer, to gain a fast understanding of how a java 
    				script project is organized in terms of resources, inheritance, composition, 
    				and more. By understanding how a project is organized, and where the variables 
    				and functions are declared and used, you will be able to modify and augment 
    				any java script project sources much faster.
    			</font>
    			</p>
  				<p>
  					<font size='+2'><br>
    					<font color='#660000'><em><strong>The first thing you should know</strong></em></font></font>
    					<font size='+1'> 
    						about this web-application is that you don&#8217;t need to copy/paste, or 
    						write down the source code into it. Simply follow these steps to get started:
    					</font><br>
  				</p>
			</div>
			<div style='position:absolute; left:180px; top:360px; width:500px; height:176px; z-index:2'> 
  				<p><strong><font color='#660000' size='+1'><em>
  				1)</em></font></strong> Open the folder where you keep the root directory for your javaScript code.<br>
    			<font color='#660000' size='+1'><em><strong>
    			2)</strong></em></font> Copy the root directory of your java Script project. (For the final project, this would 
    			be &#8220;MainMenu&#8221;).<br><em><strong><font color='#660000' size='+1'>
    			3)</font></strong></em> Open the folder where this php file resides. If you are not sure, check the following 
    			path:<br>
    			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>" . getcwd() . "</strong><br>
    			<em><strong><font color='#660000' size='+1'>
    			4)</font></strong></em> Paste the entire folder into this directory. (for the final project this would be 
    			&#8220;MainMenu&#8221;)<br>
    			<em><strong><font color='#660000' size='+1'>
    			5) </font></strong></em>Click on the Scan Button.</p>
    			<form name='scan' action='src/php/scanDir.php'><input type='submit' name='scan' value='Scan'/></form>
			</div>";
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
		echo "<p><strong>HINT:</strong> You can click on the file links to open the source code in a new tab/window.</p>";
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
		echo "<p><strong>HINT:</strong> Hover your mouse cursor / pointer over the links to show you the images.</p>";
		echo "<p><span class='bdias-label'>Resources: </span><span class='bdias-result'>";
		echo "<ul>";
		$resList = $rv->getResources();
		for( $i = 0; $i<count($resList);$i++){
			if( $resList[$i] != "" ){
				echo "<li><a href='" . $projDir . "/" . $resList[$i] . "'  target='_blank' onmouseover=javaScript:show('resource-" . $resList[$i] . "'); onmouseout=javaScript:hide('resource-" . $resList[$i] . "')>" . $resList[$i] . "</a></li><BR>";
				echo "<div class='bdias-views-resource' id='bdias-views-resource-". $resList[$i] . "'><img src='" . $projDir . "/" . $resList[$i] . "' width='600'></div>";
			}
		}
		echo "</ul></span></p>";
		
		/********************* Function Call View *****************************/
	}else if( $currentView == "FCV" ){
		echo "<div class='bdias-views'>
				<div class='bdias-view-header'>Function Call View</div>
				<div class='bdias-view-body'>";
		echo "		<p class='bdias-description'>This view provides information about Function Calls made in each Java Script Object. ";
		echo "		Select a class to see a list of functions for that class. Then Select a particular function to find out where this ";
		echo "		function is used.</p>";
		echo "		<strong>COLOR CODE MAP:<BR>
						<font color='#900'>&nbsp;&nbsp;&nbsp;LOCAL FUNCTION</font><BR>
						<font color='#009'>&nbsp;&nbsp;&nbsp;CONSTRUCTOR</font><br>
						<font color='#090'>&nbsp;&nbsp;&nbsp;FUNCTION IS USED IN THIS CLASS, <br>&nbsp;&nbsp;&nbsp;BUT SOME OTEHR CLASS OWNS IT!</font><br>
					</strong><br>";
		echo "		<p>
						<span class='bdias-label'>Classes: </span>
						<span class='bdias-result'>";
		$classes = $fcv->getClasses();
		for( $i = 0; $i<count($classes);$i++){
			$class = $classes[$i];			
			if( $class != null && $class->getClassName() != "" ){				
				$functions = $class->getFunctions();
				echo "<p>Class Name:<a href='" . $class->getDefinedInFile() . "'>" . $class->getClassName() . "</a>
						<input type='button' onClick=javaScript:showCode('code-" . $class->getClassName() . "'); value='Show Source Code'></input>
				<BR>Functions used in this class:<ul>";
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
		echo "<div  id='demo' class='demo'><div class='bdias-views-code-windowControl'><input type='button' onclick=javaScript:hideCode('code-" . $class->getClassName() . "'); value='CLOSE SOURCE CODE VIEW'></input></div>";
		echo "<div class='scroll-pane ui-widget ui-widget-header ui-corner-all'>";
		echo "<div class='scroll-content'>";
		for( $i = 0; $i<count($classes);$i++){
			$class = $classes[$i];			
			if( $class != null && $class->getClassName() != "" ){	
				echo "<div class='ui-widget-header bdias-views-code' id='bdias-views-code-" . $class->getClassName() . "'>
						<input type='button' onclick=javaScript:hide('code-" . $class->getClassName() . "'); value='CLOSE'></input> - SHOWING: " . $class->getClassName();
							$formattedFile = $class->getFormatedFile();
							include($formattedFile);
				echo "</div>";
				
			}
		}
		echo "</div></div>";
		echo "</div>
			<div class='scroll-bar-wrap ui-widget-content ui-corner-bottom'>
				<div class='scroll-bar'>
				</div>
			</div></div></div>";
		
		echo "</span></p>";
		echo "</div>";
		
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
			
			echo "<div class='bdias-views-inheritance' id='bdias-views-" . $className . "'>";
			if( $class->getParent() != "" ){
				echo "<div id='bdias-views-inheritance-parent'>Class: <strong>" . $class->getParent() . "</strong><BR>PARENT</div>";
				echo "<div id='bdias-views-inheritance-vertLine'><img src='src/images/inheritance.gif' width='100' height='100'></div>";
			}
			echo "<div id='bdias-views-inheritance-class'>Class: <strong>" . $className . "</strong><BR>CURRENTLY SELECTED CLASS</div>";
			if( $hasChildren ){
				for( $j = 0; $j < count($children); $j++){
					if( $j == 0 ){
						echo "<div id='bdias-views-inheritance-tiltedRightLine'><img src='src/images/child-left.gif' width='100' height='100'></div>";	
					}
					if( $j == 1 ){
						echo "<div id='bdias-views-inheritance-tiltedLeftLine'><img src='src/images/child-Right.gif' width='100' height='100'></div>";
					}					
					echo "<div id='bdias-views-inheritance-child" . ($j+1) . "'>Class: <strong>" . $children[$j] . "</strong><BR>CHILD " . ($j+1) . "</div>";
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
			$className = trim($class->getClassName());
			echo "<span class='bdias-result'>Class Name:<a href='" . $class->getDefinedInFile() . "' target='_blank'  onmouseover=javaScript:show('comp-" . $className . "'); onmouseout=javaScript:hide('comp-" . $className . "')>" . $className . "</a></span><BR>";
			echo "<ul><li>Composed Of: " . count($class->getComposition()) . "</li><ul>";
			$composition = $class->getComposition();
			
			for( $j = 0; $j < count($class->getComposition()); $j++ ){
				echo "<li>" . $composition[$j] . "</li>";				
			}
			echo "</ul>";
			echo "</ul><BR>";
			echo "<div class='bdias-views-composition' id='bdias-views-comp-" . $className . "'>";
			echo "<div id='bdias-views-composition-class'>Class: <strong>" . $className . "</strong><BR>CURRENTLY SELECTED CLASS</div>";
			for( $j = 0; $j < count($class->getComposition()); $j++ ){
				echo "<div id='bdias-views-composition-lines'><img src='src/images/comp" . ($j+1) . ".gif'></div>";
				echo "<div id='bdias-views-composition-obj". ($j+1) ."'>Class: <strong>" . $composition[$j] . "</strong><BR>IMPLEMENTED IN " . $className . "</div>";
			}
			echo "</div>";
		}
		
	}
}
?>
</body>
</html>
