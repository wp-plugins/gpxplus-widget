=== GPXPlus Widget ===
Contributors: uppfinnarn
Donate link: http://macaronicode.se/
Tags: gtsplus, gpxplus, pokemon, game, widget
Requires at least: 2.1
Tested up to: 2.8
Stable tag: 1.3.1

Displays your party at GPXPlus.net

== Description ==

This plugin adds a Widget that displays all Pokémons and Eggs in your Global Pokédex Plus(GPXPlus.net)
party.
This plugin only requires that you know your display name to work, as it will figure out the rest itself.

== Installation ==

1. Upload the `gpxplus` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the Widget screen and add the GPXPlus widget to the list
4. Click the customization button and enter your Display Name in the field
5. Click Save Changes and check out your site!

== Screenshots ==
1. Message Activated with the Standard WP-Theme
2. Message Disabled with NDesign's iTheme theme

== Frequently Asked Questions ==

= Where do i find my Display Name? =
You can look in several different places, but the easiest is to check at the top of the page.
It will say "Welcome back, (this is your display name)!", just copy-paste that name into the settings.

= But i don't have 6 Pokés in my party, will it still work? =
Yes, it will.
Myself i always have an open slot in my party, just in case of events i've missed or The Little Man, 
or if i find a super-rare event egg in the Lab/Shelter.

== Changelog ==

= 1.3.1 =
* Aaaah! How stupid could you possibly be? I forgot to remove the Auto-Decay mode i use for debugging, 
  everyone <b><i>UPDATE!!!</i></b> the Auto-decay mode causes the plugin to think that the cache is always
  too old, and thus fetch a new cache, which is exactly what i want when i fix something about the caching
  function, but i certainly don't want everyone else to bypass my caching system!!
* The smallest update yet: 1 word changed, and it makes miracles.
  When i rewrote the caching routine i forgot to include support for the sprite selection system, so the
  Display-box in the prefs had no effect...

= 1.3 =
* Rewrote the Fetching-mechanics so that it now uses a single REGEX to fetch the list, and does no
  longer rely heavily on `str_replace` to insert stuff into the tags, now it re-creates the links from
  scratch and inserts when it comes to the right position.
* Considering if i should rewrite the fetch-pokes module(`gpxWidget_cache()`) to a stand-alone API...?

= 1.2.1 =
* You can now choose what size to resize to.
  If you set either width or height to 0, they will be rescaled according to the other number, 
  or set both to 0 for no resizing at all(or simply uncheck the box -.-).

= 1.2 =
* Fixed some bugs... If your widget only prints `GPXWIDGET_POKEHTML`, try updating

= 1.1 =
* Added the option to resize all sprites to the size of an egg, solves layout problems for certain parties
* Added message field, can now optionally display a short message above the eggs
* Merged `show_pokes.php` into `gpxplus-widget.php` to allow for better integration
* Fixed a bug that disabled the cache, i accidentally released a debug version with the cache-checking
  routine sat to `if(true == true)` which caused it to always assume that the cache was too old.

= 1.0 =
* Uses Regular Expressions(REGEX) to find your Pokés: Less code for me, and more speed for you ;)

= 0.9 =
* Original version
* Uses JavaScript to extract the Pokés from the Party, but needs to load the entire page
into an invisible DIV, which is very slow and clumsy...
