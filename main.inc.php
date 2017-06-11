<?php 
/*
Plugin Name: Physical Photo Move
Version: 0.8
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

// Add ppm drop down menu item to the batch manager
add_event_handler('loc_end_element_set_global', 'ppm_batch_global');

// Add ppm handler to the submit event of the batch manager
add_event_handler('element_set_global_action', 'ppm_batch_global_submit', 50, 2);


function ppm_add_tab($sheets, $id)
{  
  if ($id == 'photo')
  {
    // only add tab for "physical" photos (FTP sync, not uploaded)
    $query = 'SELECT 
                  storage_category_id 
              FROM '.IMAGES_TABLE.'
              WHERE id = '.$_GET['image_id'].' 
              AND storage_category_id is not NULL;';
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


function ppm_batch_global()
{
  global $template;
 
  // assign the template for batch management
  $template->set_filename('ppm_batch_global', PPM_PATH.'/batch_global.tpl');

  // populate target category scroll with physical albums only
  $query = '
  SELECT
      id,
      name,
      uppercats,
      global_rank
    FROM '.CATEGORIES_TABLE. '
    WHERE dir IS NOT NULL
  ;';
  $cat_selected = 0;
  display_select_cat_wrapper($query, $cat_selected, 'categories', false);

  // add item to the "choose action" dropdown in the batch manager
  $template->append('element_set_global_plugins_actions', array(
    'ID' => 'ppm',
    'NAME' => l10n('BATCH_MENU'),
    'CONTENT' => $template->parse('ppm_batch_global', true)
    )
  );
}


// process the submit action
function ppm_batch_global_submit($action, $collection)
{
  if ($action == 'ppm')
  {
    $target_cat_id = pwg_db_real_escape_string($_POST['target_cat_id']);

    // need to call existing code here! just debug for now to make sure the
    // target album is captured
    $temp_debug = l10n('DEST_ALBUM').': '.$target_cat_id;
    var_dump($temp_debug);

  }
}

?>
