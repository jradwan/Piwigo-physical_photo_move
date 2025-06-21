<!-- physical photo move -->
<table style="width:525px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <div class="album-typeahead-wrapper">
        <input
          type="text"
          id="album-typeahead"
          placeholder="{'DEST_ALBUM_SELECT_BATCH'|@translate}"
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
  </tr>
  <tr>
    <td>
      <div class="album-typeahead-wrapper">
        <div class="album-select">
          {$ppm_album_select}
        </div>
      <label><input type="checkbox" name="test_mode" value="1" checked /> {'TEST_MODE_DESCR'|@translate}</label>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan=2 style="width:100%">
    </td>
  </tr>
</table>

<!-- Include CSS -->
<link rel="stylesheet" href="{$PPM_PATH}template/style.css">

<!-- Include Javascript -->
{combine_script id="album_pilot_main" load="footer" path={PPM_PATH}|cat:"template/script.js"}
