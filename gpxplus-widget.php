<?php
/*
Plugin Name: GPXPlus Widget
Plugin URI: http://www.macaronicode.se/
Description: A Widget for displaying your Party on GPXPlus.net
Author: MacaroniCode Software
Version: 1.3.1
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
	$height = get_option("gpxWidget_height", 0);
	$width = get_option("gpxWidget_width", 0);
	$display = get_option("gpxWidget_display", "image");
	$return = "";
	
	$return = $return."<div id=\"gpxWidget-content\">";
	
	$html = file_get_contents("http://gpxplus.net/user/".urlencode($username));
	$html = stristr($html, '<table class="view_party_table" cellspacing="0px" cellpadding="2px">');
	$endpos = strpos($html, "</table>");
	$html = substr($html, 0, $endpos);
	$html = str_replace("http://gpxplus.net/info/", "http://gpxplus.net/", $html);
	
	preg_match_all('#img/[^"/]*/[^"/]*/[^"/]*#', $html, $r_urls);
	
	for($i = 0; $i < sizeof($r_urls[0]); $i++)
	{
		$ids[$i] = substr(strrchr($r_urls[0][$i], "/"), 1);
		$dirs[$i] = substr(substr($r_urls[0][$i], 0, strrpos($r_urls[0][$i], "/")), 3);
		
		print "<a href=\"http://gpxplus.net/".$ids[$i]."\" target=\"_blank\">";
		print "<img";
		if($resize == "true")
		{
			if($height != 0 && $width != 0)
				print "img width=\"".$width."\" height=\"".$height."\"";
			else if($height != 0 && $width == 0)
				print "img height=\"".$height."\"";
			else if($height == 0 && $width != 0)
				print "img width=\"".$width."\"";
		}
		print " src=\"http://img.gpxplus.net/".$dirs[$i]."/".$ids[$i]."/".$display.".png\" border=\"0\" />";
		print "</a>\n";
	}
	
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
		update_option("gpxWidget_height", intval($_POST['gpxWidget-Height']));
		update_option("gpxWidget_width", intval($_POST['gpxWidget-Width']));
		update_option("gpxWidget_display", $_POST['gpxWidget-Display']);
	}
	
	$username = get_option("gpxWidget_username", "");
	$cache = get_option("gpxWidget_cache", "");
	$timestamp = get_option("gpxWidget_timestamp", 0);
	$message = get_option("gpxWidget_message", "");
	$resize = get_option("gpxWidget_resize", "false");
	$height = get_option("gpxWidget_height", 30);
	$width = get_option("gpxWidget_width", 28);
	$display = get_option("gpxWidget_display", "image");
	
	$sl = "selected=\"selected\" ";
	?>
	<p align="left" style="text-align: left;">
		<label for="gpxWidget-Username">Name:</label>
		<input type="text" align="right" name="gpxWidget-Username" id="gpxWidget-Username"
			value="<?php print $username; ?>" />
		<a href="#" onclick="alert('To check your Display Name,' + 
											'check the top of the GPXPlus site,\n' + 
											'it should say: Welcome Back, (thisisyourdisplayname).\n' + 
											'This is not always the same as your username(login name).');">?</a>
		<br />
		<label for="gpxWidget-Message">Message</label>
		<input type="text" align="right" name="gpxWidget-Message" id="gpxWidget-Message"
			value="<?php print $message;?>" />
		<a href="#" onclick="alert('Enter a message to display above your eggs.\n' + 
											'You can use HTML formatting.\n' + 
											'Leave blank for no message.');">?</a>
		<br />
		<label for="gpxWidget-Display">Display</label>
		<select name="gpxWidget-Display" id="gpxWidget-Display">
	<option value="image" <?php if($display == "image") print $sl; ?>>Sprite</option>			
	<option value="badge" <?php if($display == "badge") print $sl; ?>>Badge</option>
	<option value="icon" <?php if($display == "icon") print $sl; ?>>Gen III Party</option>
  		</select>
		<br />
		<br />
		<label for="gpxWidget-Resize">Resize Sprites</label>
		<input type="checkbox" align="right" name="gpxWidget-Resize" id="gpxWidget-Resize" 
			value="true"<?php if($resize == "true") print " checked=\"checked\""; ?> />
		<a href="#" onclick="alert('If checked, all Sprites will be ' + 
											'resized to the size specified below.');">?</a>
		<br />
		<label for="gpxWidget-Width">Width:</label>
		<input type="text" align="right" name="gpxWidget-Width" id="gpxWidget-Width"
			value="<?php print $width; ?>" />
		<a href="#" onclick="alert('Sets the width of resized sprites.\n' + 
											'Only used if Resize Sprites is checked.\n' + 
											'If 0, image is scaled according to Height instead.\n\n' + 
											'Default: 28');">?</a>
		<br />
		<label for="gpxWidget-Height">Height:</label>
		<input type="text" align="right" name="gpxWidget-Height" id="gpxWidget-Height"
			value="<?php print $height; ?>" />
		<a href="#" onclick="alert('Sets the height of resized sprites.\n' + 
											'Only used if Resize Sprites is checked.\n' + 
											'If 0, image is scaled according to Width instead.\n\n' + 
											'Default: 30');">?</a>
		
		<input type="hidden" name="gpxWidget-Check" id="gpxWidget-Check" value="1" />
	</p>
	<?php
}

add_action("plugins_loaded", "gpxWidget_init");
?>
