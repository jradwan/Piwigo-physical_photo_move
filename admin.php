<?php
// +-----------------------------------------------------------------------+
// | Piwigo - a PHP based picture gallery                                  |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2008-2018 Piwigo Team                  http://piwigo.org |
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

include_once(PHPWG_ROOT_PATH.'admin/include/functions.php');
include_once(PHPWG_ROOT_PATH.'admin/include/tabsheet.class.php');
include_once(PPM_PATH.'include/functions.inc.php');


// +-----------------------------------------------------------------------+
// | Check access and exit when user status is not ok                      |
// +-----------------------------------------------------------------------+

check_status(ACCESS_ADMINISTRATOR);


// +-----------------------------------------------------------------------+
// | Basic checks                                                          |
// +-----------------------------------------------------------------------+

$_GET['image_id'] = $_GET['tab'];
$_GET['cat_id'] = $_GET['tab'];

check_input_parameter('image_id', $_GET, false, PATTERN_ID);
check_input_parameter('cat_id', $_GET, false, PATTERN_ID);
check_input_parameter('ppm_type', $_GET, false, '/^(photo|album)$/');

$admin_photo_base_url = get_root_url().'admin.php?page=photo-'.$_GET['image_id'];
$admin_album_base_url = get_root_url().'admin.php?page=album-'.$_GET['cat_id'];


// +-----------------------------------------------------------------------+
// | Process form                                                          |
// +-----------------------------------------------------------------------+

if (isset($_POST['move_item']))
{

  $ppm_test_mode = ppm_check_test_mode();

  // check selected option and act accordingly
  if (isset($_POST['cat_id']))
  {
    // make sure category AND root album aren't both selected
    if (isset($_POST['root_album']))
    {
      array_push(
        $page['messages'],
        l10n('MSG_BOTH_OPTIONS_ERR')
        );
    }
    else
    {
      // move the item to the selected category
      $target_cat = $_POST['cat_id'];
      ppm_move_item($target_cat, $_GET['image_id'], $ppm_test_mode, $_GET['ppm_type']);
    }
  }
  else
  {
    // move to root album option has been selected
    if (isset($_POST['root_album']))
    {
      // move the item to the root album
      ppm_move_item('ROOT', $_GET['image_id'], $ppm_test_mode, $_GET['ppm_type']);
    }
    else // no destination selected
    {
      array_push(
        $page['messages'],
        l10n('MSG_NO_DEST')
        );
    }
  }
} 


// +-----------------------------------------------------------------------+
// | Tab setup                                                             |
// +-----------------------------------------------------------------------+

include_once(PHPWG_ROOT_PATH.'admin/include/tabsheet.class.php');

$page['tab'] = 'ppm';
$tabsheet = new tabsheet();
$item_id = ($_GET['image_id']);
$tabsheet->set_id($_GET['ppm_type']);
$tabsheet->select('ppm');
$tabsheet->assign();


// +-----------------------------------------------------------------------+
// | Template init                                                         |
// +-----------------------------------------------------------------------+

$template->set_filenames(
  array(
    'plugin_admin_content' => dirname(__FILE__).'/admin.tpl'
    )
  );

// initialize the HTML for the item's thumbnail
$item_thumb  = '';

if ($_GET['ppm_type'] == 'photo')
{
  $image_info = get_image_infos($item_id);
  $storage_cat_info = get_cat_info($image_info['storage_category_id']);
  $storage_cat_path = $image_info['path'];
  $storage_cat_nav = get_cat_display_name($storage_cat_info['upper_names']);

  // set template items
  $item_thumb .= '<img src="';
  $item_thumb .= DerivativeImage::thumb_url($image_info);
  $item_thumb .= '" alt={\'THUMBNAIL\'|@translate}" class=Thumbnail">';
  $item_path  = $storage_cat_path;
  $header_text = 'EDIT_PHOTO';
  $legend_text = 'MOVE_PHOTO';
  $dir_text = 'CURR_FILE_LOC';
  $help_text = 'DEST_ALBUM_HELP';

  // populate the selection scroll with physical albums
  ppm_list_physical_albums();
}
elseif ($_GET['ppm_type'] == 'album')
{
  $image_info = get_cat_info($item_id);
  $storage_cat_info = $image_info;
  $storage_cat_path = get_fulldirs(explode(',', $image_info['uppercats']));
  $storage_cat_nav = get_cat_display_name($storage_cat_info['upper_names']);

  // determine how many items (not sub-albums) are in the current album
  $query = 'SELECT
                COUNT(image_id)
            FROM '.IMAGES_TABLE.'
            JOIN '.IMAGE_CATEGORY_TABLE.' ON image_id = id
            WHERE category_id = '.$item_id.';';
  list($image_count) = pwg_db_fetch_row(pwg_query($query));

  if ($image_count != 0)
  {
    $item_thumb .= '<img src="';
    $item_thumb .= DerivativeImage::thumb_url(get_image_infos($storage_cat_info['representative_picture_id']));
    $item_thumb .= '" alt={\'THUMBNAIL\'|@translate}" class=Thumbnail">';
  }
  else
  {
    // the current album has no items (and thus no thumbnail to show) so use a placeholder
    $item_thumb .= '<i class="icon-picture"></i> ';
  }

  // set remaining template items
  $item_path = $storage_cat_path[$item_id];
  $header_text = 'EDIT_ALBUM';
  $legend_text = 'MOVE_ALBUM';
  $dir_text = 'CURR_DIR_LOC';
  $help_text = 'DEST_ALBUM_HELP_2';

  // populate the selection scroll with physical albums
  // except the current album and its sub-albums
  ppm_list_physical_albums_no_subcats($item_id);
}
else
{
  array_push(
    $page['messages'],
    l10n('MSG_NO_TYPE')
    );
}

$template->assign(
  array(
    'TITLE' => render_element_name($image_info),
    'TN_SRC' => $item_thumb,
    'current_path' => $item_path,
    'storage_category' => $storage_cat_nav,
    'header_text' => $header_text,
    'legend_text' => $legend_text,
    'dir_text' => $dir_text,
    'help_text' => $help_text,
    'root_help' => 'MSG_ROOT_HELP',
    'item_type' => $_GET['ppm_type'],
    )
  );


// +-----------------------------------------------------------------------+
// | Send HTML code                                                        |
// +-----------------------------------------------------------------------+

$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
?>
