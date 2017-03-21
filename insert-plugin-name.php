<?php
/*
  Plugin Name: Insert Plugin Name
  Description: Insert Plugin Description.
  Version: x.x
  Author: Mariano Scazzariello
  Author URI: https://www.facebook.com/mariano.scazzariello
 */
/* Exit if we've reached this page without using WordPress */
if (!defined('ABSPATH')) {
    exit;
}

/* Standard defines. */
define('WEBSITE_NICE_NAME', 'Insert Website Name'); /* Can be useful to have the plugin's name in a constant */
define('PLUGIN_SLUG', 'insert-plugin-name'); /* This is the same slug used for the main .php file (ex. abc-plugin.php, this should be abc-plugin) */
define('PLUGIN_NAME', 'name'); /* This is the text domain used for localization */
define('VERSION', 'x.x.x');
define('PLUGIN_PATH', plugin_dir_path(__FILE__));

/* Registers the autoloader of this plugin */
require_once('includes/lib/Autoloader.php');

/* Call the WPHooker to hook the plugin's actions to WordPress core */
WPHooker::hookPlugin();
?>