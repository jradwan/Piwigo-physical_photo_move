<?php 
/*
Plugin Name: Physical Photo Move
Version: 0.7
Description: Move a photo (the actual file) from one physical album to another, preserving all metadata.
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=859
Author: windracer
Author URI: http://www.windracer.net/blog
*/

if (!defined('PHPWG_ROOT_PATH'))
{
  die('Hacking attempt!');
}

define('PPM_PATH', PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)).'/');

// add ppm tab to items in physical albums
add_event_handler('tabsheet_before_select','ppm_add_tab', 50, 2);

// add language/translation support
add_event_handler('loading_lang', 'ppm_loading_lang');  


function ppm_add_tab($sheets, $id)
{  
  if ($id == 'photo')
  {
    // only add tab for "physical" photos (FTP sync, not uploaded)
    $query = 'SELECT storage_category_id FROM '.IMAGES_TABLE.' where id = '.$_GET['image_id'].' and storage_category_id is not NULL;';
    $result = pwg_query($query);
    if (!pwg_db_num_rows($result)) return $sheets;

    $sheets['ppm'] = array(
      'caption' => l10n('MOVE_BUTTON'),
      'url' => get_root_url().'admin.php?page=plugin-physical_photo_move-'.$_GET['image_id'],
      );
  }
  
  return $sheets;
}


function ppm_loading_lang()
{
  load_language('plugin.lang', PPM_PATH);
}

?>
