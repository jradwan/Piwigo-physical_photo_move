<!-- physical photo move -->
<table style="width:525px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <div class="album-select">
        {$ppm_album_select}
    </div>
    </td>
    <td>
      <a class="icon-help-circled" title="{'DEST_ALBUM_HELP_BATCH'|@translate}" style="cursor:help"></a>
    </td>
  </tr>
  <tr>
    <td>
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
    </td>
  </tr>
  <tr>
    <td colspan=2 style="width:100%">
      <label><input type="checkbox" name="test_mode" value="1" checked /> {'TEST_MODE_DESCR'|@translate}</label>
    </td>
  </tr>
</table>

<!-- Include main JS -->
{combine_script id="album_pilot_main" load="footer" path={PPM_PATH}|cat:"template/script.js"}
