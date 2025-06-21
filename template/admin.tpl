<h2>{$TITLE} &#8250; {{$header_text}|@translate} {$TABSHEET_TITLE}</h2>

<fieldset>
  <legend>{{$legend_text}|@translate}</legend>
  <table align="left">
    <tr>
      <td id="albumThumbnail" style="vertical-align:top">
        {$TN_SRC}
      </td>
      <td style="vertical-align:center">
        <p style="text-align:left; margin-top:0;">
        <strong>{'CURR_PHYS_ALBUM'|@translate}</strong> {$storage_category}<br>
        <strong>{{$dir_text}|@translate}</strong> {$current_path}<br>
      </td>
    </tr>
  </table>
</fieldset>

<fieldset>
  <legend>{'DEST_ALBUM'|@translate}</legend>
  <form id="ppmove" method="post" action="" enctype="multipart/form-data">
    <table align="left">
      <tr>
        <td>
          <div class="album-typeahead-wrapper">
            <input
             type="text"
             id="album-typeahead"
             placeholder="{'Album_search_placeholder'|@translate}"
             class="album-typeahead-input"
             autocomplete="off"
             style="width:480px;"
            >
            <ul
             id="album-typeahead-results"
             class="album-typeahead-results"
             style="display:none;"
            ></ul>
            <input type="hidden" id="album-select" value="">
          </div>
        </td>
        <td>
          <legend><a class="icon-help-circled" title="{'DEST_ALBUM_HELP_BATCH'|@translate}" style="cursor:help"></a></legend>
        </td>
      <tr>
        <td>
          <div class="album-typeahead-wrapper">
            <div class="album-select">
              {$ppm_album_select}
            </div>
          </div>
        </td>
      </tr> 
      <tr>
        <td>
          <label><input type="checkbox" name="test_mode" value="1" checked /> {'TEST_MODE_DESCR'|@translate}</label>
        </td>
      </tr>
      <tr>
        <td>
          <p style="text-align:left"><input class="submit" type="submit" value="{'MOVE_BUTTON'|@translate}" name="move_item"></p>
        </td>
      </tr>
    </table>
  </form>
</fieldset>

<!-- Include CSS -->
<link rel="stylesheet" href="{$PPM_PATH}template/style.css">

<!-- Include Javascript -->
{combine_script id="album_pilot_main" load="footer" path={PPM_PATH}|cat:"template/script.js"}
