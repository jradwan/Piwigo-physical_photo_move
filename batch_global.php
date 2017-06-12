<?php
// +-----------------------------------------------------------------------+
// | Piwigo - a PHP based picture gallery                                  |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2008-2017 Piwigo Team                  http://piwigo.org |
// | Copyright(C) 2003-2008 PhpWebGallery Team    http://phpwebgallery.net |
// | Copyright(C) 2002-2003 Pierrick LE GALL   http://le-gall.net/pierrick |
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation                                          |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, |
// | USA.                                                                  |
// +-----------------------------------------------------------------------+

if( !defined("PHPWG_ROOT_PATH") )
{
  die ("Hacking attempt!");
}

// Add ppm drop down menu item to the batch manager
add_event_handler('loc_end_element_set_global', 'ppm_batch_global');

// Add ppm handler to the submit event of the batch manager
add_event_handler('element_set_global_action', 'ppm_batch_global_submit', 50, 2);


// show ui elements when plugin is selected in global batch manager mode
function ppm_batch_global()
{
  global $template;

  // assign the template for batch management
  $template->set_filename('ppm_batch_global', PPM_PATH.'/batch_global.tpl');

  // populate target category scroll with physical albums only
  require(PPM_PATH.'include/ppm_dest_album_list.inc.php');

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

    $target_cat_id = pwg_db_real_escape_string($_POST['target_cat_id']);

    // need to call existing code here! just debug for now to make sure the
    // target album is captured
    $infos = l10n('DEST_ALBUM').': '.$target_cat_id;
    $warnings = 'Images: ';
    // $collection will be an array of image ids ... loop through this and pass each
    // individually into the move function?
    foreach ($collection as $id)
    {
      $warnings .= $id.',';
    }

    $page['warnings'] = $warnings;
    $page['infos'] = $infos;

  }
}

?>
