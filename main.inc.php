<?php 
/*
Plugin Name: Physical Photo Move
Version: 0.6
Description: Move a photo (the actual file) from one physical album to another, preserving all metadata.
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=
Author: windracer
Author URI: http://www.windracer.net/blog
*/

if (!defined('PHPWG_ROOT_PATH'))
{
  die('Hacking attempt!');
}

define('PPM_PATH', PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)).'/');

add_event_handler('tabsheet_before_select','ppm_add_tab', 50, 2);

function ppm_add_tab($sheets, $id)
{  
  load_language('plugin.lang', PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)).'/');
  
  if ($id == 'photo')
  {
    // only add tab for "physical" photos (FTP sync, not uploaded)
    $query = 'SELECT storage_category_id FROM '.IMAGES_TABLE.' where id = '.$_GET['image_id'].' and storage_category_id is not NULL;';
    $result = pwg_query($query);
    if (!pwg_db_num_rows($result)) return $sheets;

    $sheets['ppm'] = array(
      'caption' => l10n('Move'),
      'url' => get_root_url().'admin.php?page=plugin-physical_photo_move-'.$_GET['image_id'],
      );
  }
  
  return $sheets;
}
?>
