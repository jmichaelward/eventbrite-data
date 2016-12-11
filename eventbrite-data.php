<?php
namespace BGW\EventBrite;

/**
 * Plugin Name: EventBrite Data
 * Plugin URI: https://boardgamesweek.com
 * Description: Connects to the EventBrite API to retrieve and adapt event data for use by the BGW API.
 * Author: J. Michael Ward
 * Author URI: https://jmichaelward.com
 */

require_once plugin_dir_path( __FILE__ ) . 'src/EventBrite.php';

$plugin = new EventBrite;
$plugin->run();
