<h2>{$TITLE} &#8250; {{$header_text}|@translate} {$TABSHEET_TITLE}</h2>
<fieldset>
  <legend>{{$legend_text}|@translate}</legend>
  <table align="left">
    <tr>
      <td id="albumThumbnail" style="vertical-align:top">
        {$TN_SRC}
      </td>
    </tr>
    <tr>
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
    <div class="album-select">
      {$ppm_album_select}
    </div>
    <div class="album-typeahead-wrapper" style="margin-top:8px;">
      <input
        type="text"
        id="album-typeahead"
        placeholder="{'Album_search_placeholder'|@translate}"
        class="album-typeahead-input"
        autocomplete="off"
        style="width:500px; margin-bottom:0.75em;"
      >
      <ul
        id="album-typeahead-results"
        class="album-typeahead-results"
        style="display:none;"
      ></ul>
    </div>
    <input type="hidden" id="album-select" value="">
  </fieldset>
  <fieldset>
    <label><input type="checkbox" name="test_mode" value="1" checked /> {'TEST_MODE_DESCR'|@translate}</label>
    <p style="text-align:left"><input class="submit" type="submit" value="{'MOVE_BUTTON'|@translate}" name="move_item"></p>
  </fieldset>
</form>

<!-- Include main JS -->
{combine_script id="album_pilot_main" load="footer" path={PPM_PATH}|cat:"template/script.js"}
