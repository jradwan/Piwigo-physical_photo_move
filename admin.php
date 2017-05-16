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

//define('COMMUNITY_BASE_URL', get_root_url().'admin.php?page=plugin-community');

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

if (isset($_FILES['ppm']))
{

  $image_id = $_GET['image_id'];

  // retrieve the current physical path of the photo 
  $query = '
SELECT
    path
  FROM '.IMAGES_TABLE.'
  WHERE id = '.$image_id.'
;';

  $result = pwg_query($query);
  $row = pwg_db_fetch_assoc($result);
  $current_photo_path = $row['path'];

  // retrieve the current physical album of the photo
  // (a photo should only have ONE physical album)
  $query = '
SELECT
    a.category_id, b.dir
  FROM '.IMAGE_CATGEGORIES_TABLE.' a, '.CATEGORY_TABLE.' b
  WHERE a.image_id = '.$image_id.'
  and   a.category_id = b.id
  and   b.dir is not null
;';

  $result = pwg_query($query);
  $row = pwg_db_fetch_assoc($result);
  $current_category = $row['a.category_id'];
  $current_dir      = $row['b.dir'];

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
      
//  array_push(
//    $page['infos'],
//    l10n('The photo has been moved.')
//    );

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

// retrieving direct information about picture
$query = '
SELECT *
  FROM '.IMAGES_TABLE.'
  WHERE id = '.$_GET['image_id'].'
;';
$row = pwg_db_fetch_assoc(pwg_query($query));

if (!in_array(get_extension($row['path']), $conf['picture_ext']) or !empty($row['representative_ext']))
{
  $template->assign('show_file_to_update', true);
}

$template->assign(
  array(
    'TN_SRC' => DerivativeImage::thumb_url($row),
    'original_filename' => $row['file'],
    'TITLE' => render_element_name($row),
    )
  );

// +-----------------------------------------------------------------------+
// | sending html code                                                     |
// +-----------------------------------------------------------------------+

$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
?>
