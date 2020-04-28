<?php
/*
Plugin Name: サバイバルチームプラグイン
Plugin URI: https://miyakoshi.app/
Description: サバイバルチームに必須のプラグイン！
Version: 0.9.1
Author: 宮腰 悠希 (Yuki Miyakoshi)
Author URI: https://miyakoshi.app/
Tested up to: 5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

define( 'STI_PLUGIN', __FILE__ );

define( 'STI_PLUGIN_BASENAME', plugin_basename( STI_PLUGIN ) );

require_once ('lib/StiMemberClass.php');
require_once ('lib/StiScheduleClass.php');
require_once ('lib/ajax.php');
require_once ('lib/widget.php');
require_once ('lib/enqueue.php');

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/miyakoshi-yuki/survival-team-plugin/',
	STI_PLUGIN,
	'survival-team-plugin'
);

$myUpdateChecker->getVcsApi()->enableReleaseAssets();
