<?php
// +-----------------------------------------------------------------------+
// | This file is part of Piwigo.                                          |
// |                                                                       |
// | For copyright and license information, please view the COPYING.txt    |
// | file that was distributed with this source code.                      |
// +-----------------------------------------------------------------------+

if( !defined("PHPWG_ROOT_PATH") )
{
  die ("Hacking attempt!");
}

include_once(PPM_PATH.'include/functions.inc.php');

// Add ppm drop down menu item to the batch manager
add_event_handler('loc_end_element_set_global', 'ppm_batch_global');

// Add ppm handler to the submit event of the batch manager
add_event_handler('element_set_global_action', 'ppm_batch_global_submit', 50, 2);


// show ui elements when plugin is selected in global batch manager mode
function ppm_batch_global()
{
  global $template;

  // assign the template for batch management
  $template->set_filename('ppm_batch_global', PPM_PATH.'/template/batch_global.tpl');

  // populate the selection scroll with physical albums
  $album_select = ppm_list_physical_albums(0, false);
  $template->assign(
    array(
      'ppm_album_select' => $album_select,
      'PPM_PATH'         => PPM_PATH,
    )
  );

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
    global $page;

    $ppm_test_mode = ppm_check_test_mode();

    // check selected target category and act accordingly
    if (isset($_POST['cat_id']))
    {
      $target_cat = $_POST['cat_id'];

      // $collection will be an array of image ids so loop through this 
      // and process each individually 
      foreach ($collection as $id)
      {

        // filter out any selected virtual photos
        $query = '
        SELECT
          name,
          path,
          storage_category_id
          FROM '.IMAGES_TABLE.'
          WHERE id = '.$id.'
          ;';
        $result = pwg_query($query);
        $row = pwg_db_fetch_assoc($result);
        $virtual_cat_id = $row['storage_category_id'];

        if (!is_null($virtual_cat_id))
        {
          // this is a physical photo, go ahead and process the move
          ppm_move_item($target_cat, $id, $ppm_test_mode, 'photo');
        }
        else
        {
          // build warning message that a virtual photo was skipped
          $virtual_msg = l10n('MSG_SKIPPED_VIRTUAL').' "'.$row['name'].'" (id #'.$id.', path: '.$row['path'].')';

          array_push(
            $page['warnings'],
            l10n($virtual_msg)
            );
        }
      }
    }
    else // no destination selected
    {
      array_push(
        $page['messages'],
        l10n('MSG_NO_DEST')
        );
    } 
  } // check $action == 'ppm'
}

?>
