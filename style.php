<?php 
	//Style.php
	//This file compresses and minifies style.css, then outputs it to be used on the site.
	//It uses the excellent CssMin library available here: http://code.google.com/p/cssmin/
	
	//Import CssMin
		include('includes/cssmin/CssMin.php');
	
	//Fetch and minify the stylesheet
		$stylesheet 			= file_get_contents("style.css");
		$minified_stylesheet 	= CssMin::minify($stylesheet);
	
	//Compress the output
		ob_start("ob_gzhandler");
	
	//Output the stylesheet
		echo( $minified_stylesheet );
		
	//Set the headers and send the file
		header("Content-Type: text/css");
		header("Content-Length: ".ob_get_length());
		ob_end_flush();
?>