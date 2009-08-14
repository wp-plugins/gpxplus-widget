<div id="gpxWidget-content">
	<style type="text/css">
		a{border: none;}
		img{border: none;}
		a img{border: none;}
	</style>
	
	<?php
	$html = file_get_contents("http://gpxplus.net/user/".urlencode(GPXWIDGET_USERNAME));
	$html = stristr($html, '<table class="view_party_table" cellspacing="0px" cellpadding="2px">');
	$endpos = strpos($html, "</table>");
	$html = substr($html, 0, $endpos);
	$html = str_replace("http://gpxplus.net/info/", "http://gpxplus.net/", $html);
	
	preg_match_all('<a href="http://gpxplus.net/[A-Za-z0-9]*" title="[^"]*">', $html, $links);
	preg_match_all('<img src="[^"]*" alt="[A-Za-z0-9]*" />', $html, $imgs);
	$pokehtml = "";
	
	for($i = 0; $i < sizeof($links[0]); $i++)
	{
		$pokehtml = $pokehtml."<".$links[0][$i]."><".$imgs[0][$i]."></a>";
	}
	
	if(!defined("GPXWIDGET_NOPRINT")) print $pokehtml;
	define("GPXWIDGET_POKEHTML", $pokehtml);
	?>
</div>