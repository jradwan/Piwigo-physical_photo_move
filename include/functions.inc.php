<?php

// was simulation (test mode) checked?
function ppm_check_test_mode()
{
  global $page;

  if (isset($_POST['test_mode']) and $_POST['test_mode'] == 1)
  {
    // debugging messages (for simulation)
    array_push(
      $page['warnings'],
      l10n('MSG_TEST_MODE_ON')
      );
    return true;
  }
  else
  {
    return false;
  }
}


// return list of categories that are actual physical albums
function ppm_list_physical_albums()
{
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
  display_select_cat_wrapper($query, $cat_selected, 'categories', true);
}


// return list of categories that are actual physical albums,
// excluding the current album and its sub-categories
function ppm_list_physical_albums_no_subcats($id)
{
  $query = '
  SELECT
      id,
      name,
      uppercats,
      global_rank
    FROM '.CATEGORIES_TABLE. '
    WHERE dir IS NOT NULL
    and id not in ('.$id.','.implode(',',get_subcat_ids(array($id))).')
  ;';
  $cat_selected = 0;
  display_select_cat_wrapper($query, $cat_selected, 'categories', true);
}


// move an item by calling the proper procedure for the item type
function ppm_move_item($target_cat, $id, $ppm_test_mode, $item_type)
{
  global $page;

  // build debugging messages (for test mode)
  if ($ppm_test_mode)
  {
    $debug_line_1  = l10n('DBG_PROCESSING').' '.$item_type;

    array_push(
      $page['messages'],
      sprintf($debug_line_1)
      );
  }

  // call the corresponding move function for the item type
  switch ($item_type) {
    case 'photo':
      ppm_move_photo($target_cat, $id, $ppm_test_mode);
      break;
    case 'album':
      ppm_move_album($target_cat, $id, $ppm_test_mode);
      break;
    default:
      array_push(
        $page['messages'],
        l10n('MSG_NO_TYPE')
        );
  }
}


// process move of photo to destination category
function ppm_move_photo($target_cat, $id, $ppm_test_mode)
{
  global $page;

  // retrieve information about current photo
  $image_info = get_image_infos($id);
  $storage_cat_id = $image_info['storage_category_id'];
  $source_file_path = $image_info['path'];
  $source_cat_name = get_cat_info($storage_cat_id)['name'];
  $source_dir = pathinfo($source_file_path)['dirname'];
  $source_file_name = pathinfo($source_file_path)['filename'];
  $source_file_ext = pathinfo($source_file_path)['extension'];

  // retrieve representative info, if applicable
  $source_rep_ext = $image_info['representative_ext'];
  if (!is_null($source_rep_ext))
  {
    $source_rep_path = original_to_representative($source_file_path, $source_rep_ext);
  }
  else
  {
    $source_rep_path = '';
  }

  // no move necessary (same category selected)
  if ($target_cat == $storage_cat_id)
  {
    // build no work message
    $no_work_msg = $source_file_name.'.'.$source_file_ext.': '.l10n('MSG_NO_WORK_1').' ('.
      $source_cat_name.') - '.l10n('MSG_NO_WORK_2');

    array_push(
      $page['messages'],
      sprintf($no_work_msg)
      );
  }
  else 
  {
    // get destination category information
    $dest_cat_info = get_cat_info($target_cat);
    $dest_cat_name = $dest_cat_info['name'];
    $dest_uppercats = explode(',',  $dest_cat_info['uppercats']);
    $dest_cat_path = get_fulldirs($dest_uppercats);
    $dest_cat_path = $dest_cat_path[$target_cat];
    $dest_file_exists = false;
    $dest_rep_ext = '';
    $dest_rep_path = '';
     
    // check to see if filename already exists in destination
    if (file_exists($dest_cat_path.'/'.$source_file_name.'.'.$source_file_ext))
    {
      $dest_file_exists = true;
      // append date-time to filename to avoid overwriting existing file
      list($dbnow) = pwg_db_fetch_row(pwg_query('SELECT NOW();'));
      $date_string = preg_replace('/[^\d]/', '', $dbnow);
      $dest_file_name = $source_file_name.'-'.$date_string.'.'.$source_file_ext;

       // build rename message
      $rename_msg = l10n('MSG_RENAME_1').$source_file_name.'.'.$source_file_ext.
        l10n('MSG_RENAME_2').$dest_file_name.l10n('MSG_RENAME_3');

      array_push(
        $page['warnings'],
        sprintf($rename_msg)
        );
    }
    else
    {
      $dest_file_name = $source_file_name.'.'.$source_file_ext;
    }

    // build the new destination path and filename
    $dest_file_path = $dest_cat_path.'/'.$dest_file_name;
    
    // build debugging messages (for test mode)
    if ($ppm_test_mode)
    {
      // build debug strings
      $debug_line_1  = l10n('DBG_SRC').' '.$source_file_path.' ('.$source_cat_name.')';
      $debug_line_2  = l10n('DBG_DEST').' '.$dest_file_path.' ('.$dest_cat_name.')';

      array_push(
        $page['messages'],
        sprintf($debug_line_1),
        sprintf($debug_line_2),
        sprintf('-----------------')
        );
    }

    // move the file
    $move_status_ok = true;
    if (!$ppm_test_mode)
    {
      $move_status_ok = ppm_move_file_or_folder($source_file_path, $dest_file_path);
      @ppm_chmod_path($dest_file_path);
    }

    if ($move_status_ok)
    {
      // check for an existing row for the target category (the image is virtually linked
      // to the physical destination album already)
      $query = '
      SELECT
        COUNT(*) as count
        FROM '.IMAGE_CATEGORY_TABLE.'
        WHERE image_id = '.$id.'
        AND category_id = '.$target_cat.'
        ;';
      $result = pwg_query($query);
      $row = pwg_db_fetch_assoc($result);
      $count = $row['count'];

      // show a message indicating a virtual link will be replaced
      if ($count == 1)
      {
        // build unlink message
        $unlink_msg = $source_file_name.'.'.$source_file_ext.' '.l10n('MSG_LINK_REMOVED');

        array_push(
          $page['warnings'],
          sprintf($unlink_msg)
          );
      }

      // make the database changes associated with the move 
      if (!$ppm_test_mode)
      {
        // update file, path and storage category on the image table
        single_update(
          IMAGES_TABLE,
          array(
            'file' => $dest_file_name,
            'path' => $dest_file_path,
            'storage_category_id' => $target_cat,
            ),
          array('id' => $id)
          );

        // delete an existing virtual link
        $query = '
        DELETE
          FROM '.IMAGE_CATEGORY_TABLE.'
          WHERE image_id = '. $id.'
          AND category_id = '.$target_cat.'
          ;';
        pwg_query($query);

        // update category on the image category table
        single_update(
          IMAGE_CATEGORY_TABLE,
          array(
            'category_id' => $target_cat,
            ),
          array(
            'image_id' => $id,
            'category_id' => $storage_cat_id,
            )
          );

        $move_status_ok = true;
        $dest_rep_ext = $image_info['representative_ext'];

        // move representative (and representative derivatives)
        if (!is_null($dest_rep_ext))
        {
          $dest_rep_path = original_to_representative($dest_file_path, $dest_rep_ext);

          // create the pwg_representative folder if it doesn't exist in the destination
          if (!is_dir($dest_cat_path.'/pwg_representative'))
          {
            mkdir($dest_cat_path.'/pwg_representative');
          }

          // move representative
          $move_status_ok = ppm_move_file_or_folder($source_rep_path, $dest_rep_path);
          @ppm_chmod_path($dest_rep_path);

          // remove the source pwg_representative directory if it's empty
          @rmdir($source_dir.'/pwg_representative');

          // move derivatives (thumbnails, resizes, etc.) of the representative
          $source_derivatives = './'.PWG_DERIVATIVE_DIR.$source_dir.'/pwg_representative/'.$source_file_name.'-*.'.$dest_rep_ext;
          $dest_derivatives = './'.PWG_DERIVATIVE_DIR.$dest_cat_path.'/pwg_representative/';

          // create the pwg_representative folder if it doesn't exist in the destination
          if (!is_dir($dest_derivatives))
          {
            mkdir($dest_derivatives);
            // also copy the index.htm file to the new directory
            copy('./'.PWG_DERIVATIVE_DIR.$source_dir.'/pwg_representative/index.htm', $dest_derivatives.'/index.htm');
          }

          // loop through the list of derivatives and move them to the destination
          foreach (glob($source_derivatives) as $source_derivative_filename)
          {
            $dest_derivative_filename = pathinfo($source_derivative_filename)['basename'];
            $move_status_ok = ppm_move_file_or_folder($source_derivative_filename, $dest_derivatives.'/'.$dest_derivative_filename);
            @ppm_chmod_path($dest_derivatives.'/'.$dest_derivative_filename);
          }

          // count the files left in the source derivatives folder
          $remaining_file_count = count(scandir(PWG_DERIVATIVE_DIR.$source_dir.'/pwg_representative/')) - 2;
          if ($remaining_file_count == 1) 
          {
            // if there's only one file left in the directory, it should be index.htm; remove it.
            @unlink(PWG_DERIVATIVE_DIR.$source_dir.'/pwg_representative/index.htm');
          }

          // now remove the source pwg_representative directory if it's empty
          @rmdir(PWG_DERIVATIVE_DIR.$source_dir.'/pwg_representative/');
        }
        else
        {
          // move derivatives (thumbnails, resizes, etc.)
          $source_derivatives = './'.PWG_DERIVATIVE_DIR.$source_dir.'/'.$source_file_name.'-*.'.$source_file_ext;
          $dest_derivatives = './'.PWG_DERIVATIVE_DIR.$dest_cat_path;

          // create the pwg_representative folder if it doesn't exist in the destination
          if (!is_dir($dest_derivatives))
          {
            mkdir($dest_derivatives);
          }

          // loop through the list of derivatives and move them to the destination
          foreach (glob($source_derivatives) as $source_derivative_filename)
          {
            $dest_derivative_filename = pathinfo($source_derivative_filename)['basename'];
            $move_status_ok = ppm_move_file_or_folder($source_derivative_filename, $dest_derivatives.'/'.$dest_derivative_filename);
            @ppm_chmod_path($dest_derivatives.'/'.$dest_derivative_filename);
          }

          // count the files left in the source derivatives folder
          $remaining_file_count = count(scandir(PWG_DERIVATIVE_DIR.$source_dir)) - 2;
          if ($remaining_file_count == 1)
          {
            // if there's only one file left in the directory, it should be index.htm; remove it.
            @unlink(PWG_DERIVATIVE_DIR.$source_dir.'/index.htm');
          }

          // now remove the source derivatives directory if it's empty
          @rmdir(PWG_DERIVATIVE_DIR.$source_dir);
        }

        if ($move_status_ok)
        {
          // build success message
          $success_msg = $dest_file_name.': '.l10n('MSG_FILE_MOVE_SUCCESS').$dest_cat_name.'.';

          array_push(
            $page['infos'],
            sprintf($success_msg)
            );
        }
        else
        {
          // build representative move error message
          $error_msg = $dest_file_name.': '.l10n('MSG_REP_MOVE_ERR');

          array_push(
            $page['errors'],
            sprintf($error_msg)
            );
        }

      } // end check for test mode
    }
    else // an error occurred during the file move
    {
      // build file move error message
      $error_msg = $dest_file_name.': '.l10n('MSG_FILE_MOVE_ERR').' '.l10n('MSG_CHECK_LOG');

      array_push(
        $page['errors'],
        sprintf($error_msg)
        );
    }
  } // move file
}


// process move of album to destination
function ppm_move_album($target_cat, $id, $ppm_test_mode)
{
  global $page;

  // retrieve information about current category
  $source_cat_info = get_cat_info($id);
  $source_cat_name = $source_cat_info['name'];
  $source_dir = get_fulldirs(explode(',', $source_cat_info['uppercats']))[$id];

  // no move necessary (same category selected)
  // (this SHOULD never happen since the current category is excluded from the list)
  if ($target_cat == $id)
  {
    // build no work message
    $no_work_msg = l10n('MSG_NO_WORK_1').' - '.l10n('MSG_NO_WORK_2');

    array_push(
      $page['messages'],
      sprintf($no_work_msg)
      );
  }
  else
  {
    if ($target_cat == 'ROOT')
    {
      // get root URL for selected category's site
      $query = '
      SELECT galleries_url
        FROM '.SITES_TABLE.','.CATEGORIES_TABLE.'
        WHERE '.CATEGORIES_TABLE.'.site_id = '.SITES_TABLE.'.id
        AND '.CATEGORIES_TABLE.'.id = '.$id.'
        ;';
      list($root_url) = pwg_db_fetch_row(pwg_query($query));

      $dest_cat_path = $root_url;
      $dest_cat_path_final = $dest_cat_path.$source_cat_info['dir'];

      // override ROOT in target_cat with NULL (for database update below)
      $target_cat = NULL;
    }
    else
    {
      // get destination category information
      $dest_cat_info = get_cat_info($target_cat);
      $dest_cat_path = get_fulldirs(explode(',',  $dest_cat_info['uppercats']))[$target_cat];
      $dest_cat_path_final = $dest_cat_path.'/'.$source_cat_info['dir'];
    }

    // check to see if directory already exists in destination 
    // (this can happen if the parent directory of the current category is selected)
    if (file_exists($dest_cat_path_final))
    {
      $error_msg = $dest_cat_path_final.': '.l10n('MSG_ALBUM_EXISTS_ERR');

      array_push(
        $page['errors'],
        sprintf($error_msg)
        );
    }
    else
    {
      // build debugging messages (for test mode)
      if ($ppm_test_mode)
      {
        // build debug strings
        $debug_line_1  = l10n('DBG_SRC').' '.$source_dir;
        $debug_line_2  = l10n('DBG_DEST').' '.$dest_cat_path_final;

        array_push(
          $page['messages'],
          sprintf($debug_line_1),
          sprintf($debug_line_2)
          );
      }  
      else
      {
        $move_status_ok = true;
       
        // move the directory
        $move_status_ok = ppm_move_file_or_folder($source_dir, $dest_cat_path_final);
        @ppm_chmod_r($dest_cat_path_final);

        if ($move_status_ok) 
        {
          //move the derivatives folder (thumbnails, resizes, etc.) if it exists
          $source_derivatives = './'.PWG_DERIVATIVE_DIR.$source_dir;
          $dest_derivatives = './'.PWG_DERIVATIVE_DIR.$dest_cat_path_final;
          $dest_derivatives_parent = './'.PWG_DERIVATIVE_DIR.$dest_cat_path;
          if (is_dir($source_derivatives))
          {
            // make sure the parent target folder structure exists
            if (!is_dir($dest_derivatives_parent))
            {
              // create the missing parent folder structure
              mkdir($dest_derivatives_parent, 0777, true);

              // build informational message
              $parent_msg = l10n('MSG_DIR_CREATED').$dest_derivatives_parent;

              array_push(
                $page['warnings'],
                sprintf($parent_msg)
              );
            }

            $move_status_ok = ppm_move_file_or_folder($source_derivatives, $dest_derivatives);
            @ppm_chmod_r($dest_derivatives);
          }
        }

        // make the database changes associated with the move
        if ($move_status_ok)
        {
          // update parent album (id_uppercat) on the categories table
          single_update(
            CATEGORIES_TABLE,
            array(
              'id_uppercat' => $target_cat
              ),
            array('id' => $id)
          );

          // update uppercats (album path) for the album move
          update_uppercats();

          // update global ranks (categories menu) to reflect album move
          update_global_rank();

          // update the image paths for items in the moved album (and sub-albums).
          // this is based on the update_path() function in admin/include/functions.php
          // except instead of updating ALL the paths on the images table, it only
          // updates images in the affected categories
          $query = '
            SELECT DISTINCT(storage_category_id)
            FROM '.IMAGES_TABLE.'
            WHERE storage_category_id IS NOT NULL
            AND storage_category_id in ('.$id.','.implode(',',get_subcat_ids(array($id))).')
            ;';
          $cat_ids = query2array($query, null, 'storage_category_id');
          $fulldirs = get_fulldirs($cat_ids);

          foreach ($cat_ids as $cat_id)
          {
            $query = '
              UPDATE '.IMAGES_TABLE.'
              SET path = '.pwg_db_concat(array("'".$fulldirs[$cat_id]."/'",'file')).'
              WHERE storage_category_id = '.$cat_id.'
              ;';
            pwg_query($query);
          }

          // invalidate user cache (album/photo counts, etc.) to reflect the album move
          invalidate_user_cache();

          // build success message
          $success_msg = $source_dir.': '.l10n('MSG_DIR_MOVE_SUCCESS').$dest_cat_path_final.'.';

          array_push(
            $page['infos'],
            sprintf($success_msg)
            );
        } // end database changes
        else // an error occurred during the file system moves
        {
          // build directory move error message
          $error_msg = $dest_cat_path_final.': '.l10n('MSG_DIR_MOVE_ERR').' '.l10n('MSG_CHECK_LOG');

          array_push(
            $page['errors'],
            sprintf($error_msg)
            );
        }

      }  // move album
    } 
  } // end check for no work
} 


// set permissions on a specified path
function ppm_chmod_path($path)
{
  if (is_dir($path))
  {
    @chmod($path, 0755);
  }
  else
  {
    @chmod($path, 0644);
  }
}


// set permissions on a specified item
function ppm_chmod_item($item)
{
  if ($item->isDir())
  {
    @chmod($item->getPathname(), 0755);
  }
  else
  {
    @chmod($item->getPathname(), 0644);
  }
}


// recursively set reasonable permissions on a path
function ppm_chmod_r($path) 
{
  $dir = new DirectoryIterator($path);

  foreach ($dir as $item)
  {
    @ppm_chmod_item($item);
    if ($item->isDir() && !$item->isDot()) 
    {
      @ppm_chmod_r($item->getPathname());
    }
  }
}

// custom function for moving files or folders (recursively)
// using copy/unlink/rmdir instead of rename to avoid cross-device rename issues
// see https://bugs.php.net/bug.php?id=54097 and https://www.php.net/manual/en/function.rename.php#113943
function ppm_move_file_or_folder($source, $target)
{
  if (is_dir($source))
  {
    $move_item_status_ok = true;
    @mkdir($target);
    $d = dir($source);
    while (FALSE !== ($entry = $d->read()))
    {
      if ($entry == '.' || $entry == '..')
      {
        continue;
      }
      $Entry = $source . '/' . $entry;
      if (is_dir($Entry))
      {
        $move_item_status_ok = ppm_move_file_or_folder($Entry, $target . '/' . $entry);
        // delete sub-folder (which should be empty at this point) if copy/unlink was successful
        if ($move_item_status_ok)
        {
          rmdir($Entry);
        }
        continue;
      }
      $move_item_status_ok = copy($Entry, $target . '/' . $entry);
      // delete source file if copy was successful
      if ($move_item_status_ok)
      {
        unlink($Entry);
      }
    }
    $d->close();
    // delete source folder (which should be empty at this point) if copy/unlink was successful
    if ($move_item_status_ok)
    {
      rmdir($source);
    }
  }
  else
  {
    $move_item_status_ok = copy($source, $target);
    // delete source file if copy was successful
    if ($move_item_status_ok)
    {
      unlink($source);
    }
  }
  return $move_item_status_ok;
}

?>
