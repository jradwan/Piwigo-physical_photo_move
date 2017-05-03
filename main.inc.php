<?php 
/*
Plugin Name: Physical Photo Move
Version: 0.1
Description: Move a photo from one physical album to another, preserving all metadata
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=
Author: windracer (Jeremy C. Radwan)
Author URI: http://www.windracer.net/blog
*/

if (!defined('PHPWG_ROOT_PATH'))
{
  die('Hacking attempt!');
}

define('PHYS_PHOTO_MOVE_PATH', PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)).'/');

add_event_handler('tabsheet_before_select','phys_photo_move_add_tab', 50, 2);
function phys_photo_move_add_tab($sheets, $id)
{  
  load_language('plugin.lang', PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)).'/');
  
  if ($id == 'photo')
  {
    $sheets['update'] = array(
      'caption' => l10n('Move Photo'),
      'url' => get_root_url().'admin.php?page=plugin-phys-photo-move-'.$_GET['image_id'],
      );
  }
  
  return $sheets;
}
?>
