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
  include_once(PHPWG_ROOT_PATH.'admin/include/functions_upload.inc.php');

  // check selected target category and act accordingly
  if (isset($_POST['cat_id']))
  {
    $target_cat = $_POST['cat_id'];

    // retrieve information about current photo
    $image_info = get_image_infos($_GET['image_id']);
    $storage_cat_id = $image_info['storage_category_id'];
    $source_file_path = $image_info['path'];
    $source_dir = pathinfo($source_file_path)['dirname'];
    $source_file_name = pathinfo($source_file_path)['filename'];
    $source_file_ext = pathinfo($source_file_path)['extension'];

    // no move necessary (same category selected)
    if ($target_cat == $storage_cat_id)
    {
      array_push(
        $page['messages'],
        l10n('Source and destination are the same. No move attempted.')
        );
    }
    else 
    {
      // get destination category information
      $dest_cat_info = get_cat_info($target_cat);
      $dest_cat_name = $dest_cat_info['name'];
      $dest_uppercats = $dest_cat_info['uppercats'];
      $dest_cat_path = get_fulldirs($dest_uppercats);
      $dest_cat_path = $dest_cat_path[$target_cat];
      $dest_file_exists = false;
     
      // check to see if filename already exists in destination
      if (file_exists($dest_cat_path.'/'.$source_file_name.'.'.$source_file_ext))
      {
        $dest_file_exists = true;
        // append date-time to filename to avoid overwriting existing file
        list($dbnow) = pwg_db_fetch_row(pwg_query('SELECT NOW();'));
        $date_string = preg_replace('/[^\d]/', '', $dbnow);
        $dest_file_path = $dest_cat_path.'/'.$source_file_name.'-'.$date_string.'.'.$source_file_ext;
      }
      else
      {
        $dest_file_path = $dest_cat_path.'/'.$source_file_name.'.'.$source_file_ext;
      }
    
      // move the file
      $move_status_ok = rename($source_file_path, $dest_file_path);
      @chmod($dest_file_path, 0644);

      if ($move_status_ok)
      {
        // file successfully moved to $dest_cat_name
        array_push(
          $page['infos'],
          l10n('File successfully moved to '.$dest_cat_name.'.')
          );

        // warning about file renamed
        if ($dest_file_exists)
        {
        array_push(
          $page['warnings'],
          l10n('A file named '.$source_file_name.'.'.$source_file_ext.' already existed in the destination. '.
            'The file was renamed to '.$source_file_name.'-'.$date_string.'.'.$source_file_ext.'.')
          );
        }
      }
      else // an error occurred during the move
      {
        array_push(
          $page['errors'],
          l10n('An error occured during the file move. Please check the Piwigo and web server logs for details.')
          );
      }
    } // move file
  }
  else // no destination selected
  {
    array_push(
      $page['warnings'],
      l10n('No destination selected.')
      );
  }

      // debugging messages 
      array_push(
        $page['messages'],
        l10n('source filepath: '.$source_file_path),
        l10n('source directory: '.$source_dir),
        l10n('source filename: '.$source_file_name),
        l10n('source ext: '.$source_file_ext),
        l10n('target album: '.$dest_cat_name),
        l10n('fulldirs: '.$dest_cat_path),
        l10n('dest filepath: '.$dest_file_path)
        );


//  single_update(
//    IMAGES_TABLE,
//    array(
//      'representative_ext' => $representative_ext,
//      'width' => $file_infos['width'],
//      'height' => $file_infos['height'],
//      ),
//    array('id' => $image_id)
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

// retrieve the id of the storage category of the current photo
$image_info = get_image_infos($_GET['image_id']);
$storage_cat_id = $image_info['storage_category_id'];

// retrieve the name of the storage category of the photo
$storage_cat_info = get_cat_info($storage_cat_id);

// populate target category scroll with physical albums only
$cat_selected = 0;
$query = '
SELECT 
    id, 
    CONCAT(name, " (", id, ")") as name, 
    uppercats, 
    global_rank 
  FROM '.CATEGORIES_TABLE. ' 
  WHERE dir IS NOT NULL
;';
display_select_cat_wrapper($query, $cat_selected, 'categories', false);

$template->assign(
  array(
    'TITLE' => render_element_name($image_info),
    'TN_SRC' => DerivativeImage::thumb_url($image_info),
    'current_path' => $image_info['path'],
    'storage_category' => $storage_cat_info['name'],
    )
  );

// +-----------------------------------------------------------------------+
// | sending html code                                                     |
// +-----------------------------------------------------------------------+

$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
?>
