<h2>{$TITLE} &#8250; {{$header_text}|@translate} {$TABSHEET_TITLE}</h2>

<fieldset>
  <legend>{{$legend_text}|@translate}</legend>
  <table>
    <tr>
      <td id="albumThumbnail" style="vertical-align:top">
        <img src="{$TN_SRC}" alt="{'THUMBNAIL'|@translate}" class="Thumbnail">
      </td>
      <td style="vertical-align:center">
          <p style="text-align:left; margin-top:0;">
          <strong>{'CURR_PHYS_ALBUM'|@translate}</strong> {$storage_category}<br>
          <strong>{{$dir_text}|@translate}</strong> {$current_path}<br>
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
    <label><input type="checkbox" name="test_mode" value="1" checked /> {'TEST_MODE_DESCR'|@translate}</label>
    <p style="text-align:left"><input class="submit" type="submit" value="{'MOVE_BUTTON'|@translate}" name="move_item"></p>
  </fieldset>
</form>
