<?php
// +-----------------------------------------------------------------------+
// | Piwigo - a PHP based picture gallery                                  |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2008-2011 Piwigo Team                  http://piwigo.org |
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

// +-----------------------------------------------------------------------+
// | Check Access and exit when user status is not ok                      |
// +-----------------------------------------------------------------------+

check_status(ACCESS_ADMINISTRATOR);

// +-----------------------------------------------------------------------+
// | Basic checks                                                          |
// +-----------------------------------------------------------------------+

$_GET['image_id'] = $_GET['tab'];

check_input_parameter('image_id', $_GET, false, PATTERN_ID);

$admin_photo_base_url = get_root_url().'admin.php?page=photo-'.$_GET['image_id'];

// +-----------------------------------------------------------------------+
// | Process form                                                          |
// +-----------------------------------------------------------------------+

if (isset($_POST['move_photo']))
{

//  $image_id = $_GET['image_id'];

//  move_uploaded_file($_FILES['ppm']['tmp_name'], $representative_file_path);

//  $file_infos = pwg_image_infos($representative_file_path);
      
//  single_update(
//    IMAGES_TABLE,
//    array(
//      'representative_ext' => $representative_ext,
//      'width' => $file_infos['width'],
//      'height' => $file_infos['height'],
//      ),
//    array('id' => $image_id)
//    );
      
  array_push(
    $page['infos'],
    l10n('The photo has been successfully moved.')
    );

}

// +-----------------------------------------------------------------------+
// | Tabs                                                                  |
// +-----------------------------------------------------------------------+

include_once(PHPWG_ROOT_PATH.'admin/include/tabsheet.class.php');

$page['tab'] = 'ppm';

$tabsheet = new tabsheet();
$tabsheet->set_id('photo');
$tabsheet->select('ppm');
$tabsheet->assign();

// +-----------------------------------------------------------------------+
// |                             template init                             |
// +-----------------------------------------------------------------------+

$template->set_filenames(
  array(
    'plugin_admin_content' => dirname(__FILE__).'/admin.tpl'
    )
  );

// retrieve information about current photo
$query = '
SELECT *
  FROM '.IMAGES_TABLE.'
  WHERE id = '.$_GET['image_id'].'
;';
$image_row = pwg_db_fetch_assoc(pwg_query($query));
$storage_cat_id = $image_row['storage_category_id'];

// retrieve the name of the storage category of the photo
$query = '
SELECT CONCAT(name, " (", id, ")") as name
  FROM '.CATEGORIES_TABLE.'
  WHERE id = '.$storage_cat_id.'
;';
$category_row = pwg_db_fetch_assoc(pwg_query($query));

// populate target album scroll with physical albums only
$cat_selected = 0;
$query = 'SELECT id, CONCAT(name, " (", id, ")") as name, uppercats, global_rank FROM '.CATEGORIES_TABLE. ' WHERE dir IS NOT NULL';
display_select_cat_wrapper($query,
                           $cat_selected,
                           'categories',
                           false);

$template->assign(
  array(
    'TITLE' => render_element_name($image_row),
    'TN_SRC' => DerivativeImage::thumb_url($image_row),
    'current_path' => $image_row['path'],
    'storage_category' => $category_row['name'],
    )
  );

// +-----------------------------------------------------------------------+
// | sending html code                                                     |
// +-----------------------------------------------------------------------+

$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
?>
