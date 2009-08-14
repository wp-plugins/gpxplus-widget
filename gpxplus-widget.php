<?php
/*
Plugin Name: GPXPlus Widget
Plugin URI: http://www.macaronicode.se/
Description: A Widget for displaying your Party on GPXPlus.net
Author: MacaroniCode Software
Version: 1.0
Author URI: http://www.macaronicode.se/
*/

function gpxWidget()
{
	$username = get_option("gpxWidget_username", "");
	$cache = get_option("gpxWidget_cache", "");
	$timestamp = get_option("gpxWidget_timestamp", 0);
	
	if($timestamp < (time() - 1000) || $cache == "" || $timestamp == 0)
	//if(true == true)
	{
		$freshcache = gpxWidget_cache();
		update_option("gpxWidget_cache", htmlentities($freshcache));
		update_option("gpxWidget_timestamp", time());
		print $freshcache;
	}
	else
	{
		print html_entity_decode($cache);
	}
}

function gpxWidget_cache()
{
	define("GPXWIDGET_NOPRINT", "true");
	define("GPXWIDGET_USERNAME", get_option("gpxWidget_username", ""));
	include "show_pokes.php";
	return GPXWIDGET_POKEHTML;
}

function widget_gpxWidget($args)
{
	extract($args);
	print $before_widget;
	print $before_title.'GPXPlus'.$after_title;
	gpxWidget();
	print $after_widget;
}

function gpxWidget_init()
{
	register_sidebar_widget(__('GPXPlus'), 'widget_gpxWidget');
	register_widget_control('GPXPlus', 'gpxWidget_control');
}

function gpxWidget_control()
{
	if($_POST['gpxWidget-Check'])
	{
		update_option("gpxWidget_username", $_POST['gpxWidget-Username']);
		update_option("gpxWidget_cache", "");
		update_option("gpxWidget_timestamp", 0);
	}
	
	$username = get_option("gpxWidget_username", "");
	$cache = get_option("gpxWidget_cache", "");
	$timestamp = get_option("gpxWidget_timestamp", 0);
	?>
	<p align="left" style="text-align: left;">
		<label for="gpxWidget-Username">Display&nbsp;Name:&nbsp;</label>
	<input type="text" name="gpxWidget-Username" id="gpxWidget-Username" value="<?php print $username; ?>" />
		<br />
		<i>
			To check your Display Name, check the top of the GPXPlus site, 
			it should say: "Welcome Back, (thisisyourdisplayname)".<br />
			This is not always the same as your username(login name).
		</i>
		<input type="hidden" name="gpxWidget-Check" id="gpxWidget-Check" value="1" />
	</p>
	<?php
}

add_action("plugins_loaded", "gpxWidget_init");
?>