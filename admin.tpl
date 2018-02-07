<h2>{$TITLE} &#8250; {{$header_text}|@translate} {$TABSHEET_TITLE}</h2>

<fieldset>
  <legend>{{$legend_text}|@translate}</legend>
  <table>
    <tr>
      {$TN_SRC}
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
      <a class="icon-help-circled" title="{{$help_text}|@translate}"></a>
    </legend>
    <select class="categoryList" name="cat_id" size="10">
      {html_options options=$categories selected=$categories_selected}
    </select>
    <b>- {'OR'|@translate} - </b>
    <label><input type="checkbox" name="root_album" value="1" /> {'ROOT_ALBUM_CHECKBOX'|@translate}</label>
    <p>
    <label><input type="checkbox" name="test_mode" value="1" checked /> {'TEST_MODE_DESCR'|@translate}</label>
    <p style="text-align:left"><input class="submit" type="submit" value="{'MOVE_BUTTON'|@translate}" name="move_item"></p>
  </fieldset>
</form>
