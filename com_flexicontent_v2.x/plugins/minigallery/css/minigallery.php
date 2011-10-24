<?php
header("Content-type: text/css");
$id = @$_REQUEST['id'];
$id = $id?$id:'slideshow';
?>
/**
Stylesheet: Slideshow.css
	CSS for Slideshow.

License:
	MIT-style license.

Copyright:
	Copyright (c) 2008 [Aeron Glemann](http://www.electricprism.com/aeron/).
	
HTML:
	<div class="slideshow">
		<div class="slideshow-images" />
		<div class="slideshow-captions" />
		<div class="slideshow-controller" />
		<div class="slideshow-loader" />
		<div class="slideshow-thumbnails" />
	</div>
	
Notes:
	These next five rules are required for Slideshow to work correctly.
	Override at your own risk.
*/

.<?php echo $id;?> {
	display: block; position: relative; z-index: 0;
}
.<?php echo $id;?>-images {
	display: block; overflow: hidden; position: relative;
}		
.<?php echo $id;?>-images img {
	display: block; position: absolute; z-index: 1;
}		
.<?php echo $id;?>-thumbnails {
	overflow: hidden;
}
.<?php echo $id;?>-thumbnails ul {
	left: 0; position: absolute; top: 0; width: 100000px;
}

/**
HTML:
	<div class="slideshow-images">
		<img />
		<img />
	</div>
	
Notes:
	The images div is where the slides are shown.
	Customize the visible / prev / next classes to effect the slideshow transitions: fading, wiping, etc.
*/

.<?php echo $id;?>-images {
	height: 300px; width: 400px;
}		
.<?php echo $id;?>-images-visible { 
	opacity: 1;
}	
.<?php echo $id;?>-images-prev { 
	opacity: 0;
}
.<?php echo $id;?>-images-next { 
	opacity: 0;
}
.<?php echo $id;?>-images img {
	float: left; left: 0; top: 0;
}	

/**
Notes:
	These are examples of user-defined styles.
	Customize these classes to your usage of Slideshow.
*/

.<?php echo $id;?> {
	height: auto; margin: 0 auto; width: 400px;
}
.<?php echo $id;?> a img {
	border: 0;
}

/**
HTML:
	<div class="slideshow-captions">
		...
	</div>
	
Notes:
	Customize the hidden / visible classes to affect the captions animation.
*/

.<?php echo $id;?>-captions {
	background: #000; bottom: 0; color: #FFF; font: normal 12px/22px Arial, sans-serif; left: 0; overflow: hidden; position: absolute; text-indent: 10px; width: 100%; z-index: 10000;
}
.<?php echo $id;?>-captions-hidden {
	height: 0; opacity: 0;
}
.<?php echo $id;?>-captions-visible {
	height: 22px; opacity: .7;
}

/**
HTML:
	<div class="slideshow-controller">
		<ul>
			<li class="first"><a /></li>
			<li class="prev"><a /></li>
			<li class="play"><a /></li>
			<li class="next"><a /></li>
			<li class="last"><a /></li>
		</ul>
	</div>
	
Notes:
	Customize the hidden / visible classes to affect the controller animation.
*/

.<?php echo $id;?>-controller {
	background: url(controller.png) no-repeat; height: 48px; left: 50%; margin: -24px 0 0 -122px; overflow: hidden; position: absolute; top: 50%; width: 244px; z-index: 10000;
}
.<?php echo $id;?>-controller * {
	margin: 0; outline: none; padding: 0;
}
.<?php echo $id;?>-controller-hidden { 
	opacity: 0;
}
.<?php echo $id;?>-controller-visible {
	opacity: 1;
}
.<?php echo $id;?>-controller a {
	background: url(controller-controls.png) no-repeat -47px 0; cursor: pointer; display: block; height: 18px; left: 112px; overflow: hidden; position: absolute; top: 15px; width: 20px;
}
.<?php echo $id;?>-controller a.active {
	background-position: -47px -18px;
}
.<?php echo $id;?>-controller li {
	list-style: none;
}			 
.<?php echo $id;?>-controller li.first a {
	background-position: 0 0; left: 36px; width: 19px;
}
.<?php echo $id;?>-controller li.first a.active {
	background-position: 0 -18px;
}
.<?php echo $id;?>-controller li.prev a {
	background-position: -19px 0; left: 68px; width: 28px;
}
.<?php echo $id;?>-controller li.prev a.active {
	background-position: -19px -18px;
}
.<?php echo $id;?>-controller li.play a {
	background-position: -67px 0;
}
.<?php echo $id;?>-controller li.play a.active {
	background-position: -67px -18px;
}
.<?php echo $id;?>-controller li.next a {
	background-position: -87px 0; left: 148px; width: 28px;
}
.<?php echo $id;?>-controller li.next a.active {
	background-position: -87px -18px;
}
.<?php echo $id;?>-controller li.last a {
	background-position: -115px 0; left: 189px; width: 19px;
}
.<?php echo $id;?>-controller li.last a.active {
	background-position: -115px -18px;
}

/**
HTML:
	<div class="slideshow-loader" />
	
Notes:
	Customize the hidden / visible classes to affect the loader animation.
*/

.<?php echo $id;?>-loader {
	background: url(loader.png); height: 30px; right: 2px; position: absolute; top: 2px; width: 30px; z-index: 10001;
}
.<?php echo $id;?>-loader-hidden {
	opacity: 0;
}
.<?php echo $id;?>-loader-visible {
	opacity: 1;
}

/**
HTML:
	<div class="slideshow-thumbnails">
		<ul>
			<li><a class="slideshow-thumbnails-active" /></li>
			<li><a class="slideshow-thumbnails-inactive" /></li>
			...
			<li><a class="slideshow-thumbnails-inactive" /></li>
		</ul>
	</div>
	
Notes:
	Customize the active / inactive classes to affect the thumbnails animation.
	Use the !important keyword to override FX without affecting performance.
*/

.<?php echo $id;?>-thumbnails {
	bottom: -55px; height: 55px; left: 0; position: absolute; width: 100%;
}
.<?php echo $id;?>-thumbnails * {
	margin: 0; padding: 0;
}
.<?php echo $id;?>-thumbnails li {
	float: left; list-style: none;
}
.<?php echo $id;?>-thumbnails a {
	display: block; float: left; outline: none; margin: 5px 5px 0 0; padding: 5px;
}
.<?php echo $id;?>-thumbnails a:hover {
	background-color: #FF9 !important; opacity: 1 !important;
}
.<?php echo $id;?>-thumbnails img {
	display: block;
}
.<?php echo $id;?>-thumbnails-hidden {
	background-color: #FFF; opacity: 0;
}
.<?php echo $id;?>-thumbnails-inactive {
	background-color: #FFF; opacity: .5;
}
.<?php echo $id;?>-thumbnails-active {
	background-color: #9FF; opacity: 1;
}
