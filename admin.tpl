<h2>{$TITLE} &#8250; {'EDIT_PHOTO'|@translate} {$TABSHEET_TITLE}</h2>

<fieldset>
  <legend>{'MOVE_PHOTO'|@translate}</legend>
  <table>
    <tr>
      <td id="albumThumbnail" style="vertical-align:top">
        <img src="{$TN_SRC}" alt="{'THUMBNAIL'|@translate}" class="Thumbnail">
      </td>
      <td style="vertical-align:center">
          <p style="text-align:left; margin-top:0;">
          <strong>{'CURR_PHYS_ALBUM'|@translate}</strong> {$storage_category}<br>
          <strong>{'CURR_FILE_LOC'|@translate}</strong> {$current_path}<br>
      </td>
    </tr>
  </table>
</fieldset>

<form id="ppmove" method="post" action="" enctype="multipart/form-data">
  <fieldset>
    <legend>
      {'DEST_ALBUM'|@translate}
      <a class="icon-help-circled" title="{'DEST_ALBUM_HELP'|@translate}"></a>
    </legend>
    <select class="categoryList" name="cat_id" size="10">
      {html_options options=$categories selected=$categories_selected}
    </select>
    <p>
    <label><input type="checkbox" name="test_mode" value="1" {if $ppm_test_mode}checked="checked"{/if} /> {'TEST_MODE_DESCR'|@translate}</label>
    <p style="text-align:left"><input class="submit" type="submit" value="{'MOVE_BUTTON'|@translate}" name="move_photo"></p>
  </fieldset>
</form>
