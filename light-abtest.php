<?php
/**
 * Plugin Name: Light AB Test
 * Plugin URI: 
 * Description: A simple AB Testing plugin.
 * Version: 0.0.0
 * Author: Djane Rey Mabelin
 * Author URI: codertalks.com
 * License: GPL2
 */
defined('ABSPATH') or die("No script kiddies please!");


require "WordpressPlugin.php";
require "LightABTest.php";


$instance = LightABTest::make();