<?php
/*
	Plugin Name: Export Themes
	Description: A simple plugin to export templates in a .zip file and then install them from the same package in other servers.
	Version: 2.1
	Author: Sergio Milardovich
	Author URI: http://milardovich.com.ar/
*/

define("WPCT_PATH", dirname(__FILE__).'/', true);
add_action('activate_wp-clone-template/main.php','install_wpct');
add_action('deactivate_wp-clone-template/main.php', 'uninstall_wpct');

if (!function_exists('clone_plugin_url')){
	function clone_plugin_url(){
		return get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));	
	}
}


if (!isset($_SESSION['wpct_buffer'])) {
	$_SESSION['wpct_buffer'] = false;
}

function wpct_install_wpct(){
	@mkdir(WPCT_PATH.'templates', 0755);
	return true;
}
function wpct_uninstall_wpct(){
	return true;
}

function wpct_admin_actions(){
	add_theme_page('Clone Template options', 'Export', 'manage_options', 'clone_template', 'wpct_menu');
}
function wpct_menu(){
	include_once WPCT_PATH.'views/export.php';
}
add_action('admin_menu', 'wpct_admin_actions');
function wpct_get_templates_options(){
	$root = substr($_SERVER['SCRIPT_FILENAME'],0,-19).'wp-content/themes';
	$dirs = array();
	if(is_dir($root)){
		$dir = opendir($root);
	}
	while ($direc = readdir($dir)){
		if(is_dir($root.'/'.$direc) && $direc !== '..' && $direc !== '.'){
			array_push($dirs, '<option value="'.$direc.'">'.$direc);
		}
	}
	echo implode('</option>',$dirs).'</option>';		
}
/*
 * Recursive scan, based on php.net function
 */
function rscandir($base='', &$data=array()) {  
	$array = array_diff(scandir($base), array('.', '..')); # remove ' and .. from the array */   
	foreach($array as $value){ /* loop through the array at the level of the supplied $base */  
		if (is_dir($base.$value)){ /* if this is a directory */
			$data[] = $base.$value.'/'; /* add it to the $data array */
			$data = rscandir($base.$value.'/', $data); /* then make a recursive call with the 
				current $value as the $base supplying the $data array to carry into the recursion */      
		} elseif (is_file($base.$value)) { /* else if the current $value is a file */
			$data[] = array($base,$value); /* just add the current $value to the $data array */
		}
	}      
	return $data;
}

function wpct_show_warn($warn){
	echo '<div class="error"><p>'.$warn.'</p></div>';
}
function wpct_export_template($template){
	require_once WPCT_PATH.'lib/pclzip.lib.php';
	if(!is_dir(WPCT_PATH.'templates')){
		@mkdir(WPCT_PATH.'templates', 0755) or wpct_show_warn(__("I'm not able to create the tmp directory 'templates', please, check your permissons or create it manually."));
	}
	@chmod(WPCT_PATH."templates", 0755);
	if(file_exists(WPCT_PATH.'templates/'.$template.'.zip')){
		@unlink(WPCT_PATH.'templates/'.$template.'.zip') or wpct_show_warn(__("I'm not able to delete the zip file in the directory 'templates', please, check your permissons or delete it manually."));
	}
	$export = new PclZip(WPCT_PATH.'templates/'.$template.'.zip');
	$template_root = substr($_SERVER['SCRIPT_FILENAME'],0,-19).'wp-content/themes/'.$template.'/';
	$files = rscandir($template_root);
	foreach($files as $file){
		if($file[0].$file[1] !== '/v' && (is_file($file[0].$file[1]) or is_dir($file[0].$file[1]))){
			$add[] = $file[0].$file[1];
		}
	}
	$add = implode(',',$add);
	$make = $export->add($add, PCLZIP_OPT_REMOVE_PATH, substr($_SERVER['SCRIPT_FILENAME'],0,-19).'wp-content/themes');
	if ($make == 0) {
	    die("Error : ".$export->errorInfo(true));
	}

    $_SESSION['wpct_buffer'] = true;
    wp_redirect(get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__)).'/templates/'.$template.'.zip', 301); exit;
}

add_action('init', 'wpct_clean_output_buffer');
function wpct_clean_output_buffer() {
	ob_start();
}

add_action('init', 'wpct_check_session_buffer');
function wpct_check_session_buffer(){
	if($_SESSION['wpct_buffer'] == true){
		$files = glob(WPCT_PATH.'templates/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
			unlink($file); // delete file
		}
		$_SESSION['wpct_buffer'] = false;
	}
}

add_action('init', 'wpct_init_session', 1);
function wpct_init_session(){
	session_start();
}