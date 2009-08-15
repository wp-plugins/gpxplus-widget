<?php
/*
Plugin Name: GPXPlus Widget
Plugin URI: http://www.macaronicode.se/
Description: A Widget for displaying your Party on GPXPlus.net
Author: MacaroniCode Software
Version: 1.1
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
	$username = get_option("gpxWidget_username", "");
	$cache = get_option("gpxWidget_cache", "");
	$timestamp = get_option("gpxWidget_timestamp", 0);
	$message = get_option("gpxWidget_message", "");
	$resize = get_option("gpxWidget_resize", "false");
	$return = "";
	
	$return = $return."<div id=\"gpxWidget-content\">";
	$return = $return."<style type=\"text/css\">";
	$return = $return."	a{border: none;}";
	$return = $return."	img{border: none;}";
	$return = $return."	a img{border: none;}";
	$return = $return."</style>";
	
	$html = file_get_contents("http://gpxplus.net/user/".urlencode($username));
	$html = stristr($html, '<table class="view_party_table" cellspacing="0px" cellpadding="2px">');
	$endpos = strpos($html, "</table>");
	$html = substr($html, 0, $endpos);
	$html = str_replace("http://gpxplus.net/info/", "http://gpxplus.net/", $html);
	
	preg_match_all('<a href="http://gpxplus.net/[A-Za-z0-9]*" title="[^"]*">', $html, $links);
	preg_match_all('<img src="[^"]*" alt="[A-Za-z0-9]*" />', $html, $imgs);
	
	$pokehtml = "";
	if($message != "") $pokehtml = $message."<br />";
	
	for($i = 0; $i < sizeof($links[0]); $i++)
	{
		$pokehtml = $pokehtml."<".$links[0][$i]."><".$imgs[0][$i]."></a>";
	}
	
	if($resize == "true")
		$pokehtml = str_replace("img src", "img width=\"28\" height=\"30\" src", $pokehtml);
	
	$return = $return.$pokehtml;
	$return = $return."</div>";
	return $return;
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
		update_option("gpxWidget_message", $_POST['gpxWidget-Message']);
		update_option("gpxWidget_resize", $_POST['gpxWidget-Resize']);
	}
	
	$username = get_option("gpxWidget_username", "");
	$cache = get_option("gpxWidget_cache", "");
	$timestamp = get_option("gpxWidget_timestamp", 0);
	$message = get_option("gpxWidget_message", "");
	$resize = get_option("gpxWidget_resize", "false");
	?>
	<p align="left" style="text-align: left;">
		<label for="gpxWidget-Username">Display&nbsp;Name:&nbsp;</label>
		<input type="text" name="gpxWidget-Username" id="gpxWidget-Username"
			value="<?php print $username; ?>" />
		<a href="#" onclick="alert('To check your Display Name, check the top of the GPXPlus site,\n' + 
											'it should say: Welcome Back, (thisisyourdisplayname).\n' + 
											'This is not always the same as your username(login name).');">?</a>
		<br />
		<label for="gpxWidget-Message">Message</label>
		<input type="text" name="gpxWidget-Message" id="gpxWidget-Message" value="<?php print $message; ?>" />
		<a href="#" onclick="alert('Enter a message to display above your eggs.\n' + 
											'You can use HTML formatting.\n' + 
											'Leave blank for no message.');">?</a>
		<br />
		<label for="gpxWidget-Resize">Resize Sprites</label>
		<input type="checkbox" name="gpxWidget-Resize" id="gpxWidget-Resize" 
			value="true"<?php if($resize == "true") print " checked=\"checked\""; ?> />
		<a href="#" onclick="alert('If checked, all Pokémon Sprites will be ' + 
											'resized to the same size as an egg');" >?</a>
		<input type="hidden" name="gpxWidget-Check" id="gpxWidget-Check" value="1" />
	</p>
	<?php
}

add_action("plugins_loaded", "gpxWidget_init");
?>
