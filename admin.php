<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

global $template;
$template->set_filenames(
 array(
   'plugin_admin_content' => dirname(__FILE__).'/admin.tpl'
 )
);
 
$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
?>
