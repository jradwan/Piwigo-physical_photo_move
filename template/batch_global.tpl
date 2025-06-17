<!-- physical photo move -->
<table style="width:525px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <select size="20" style="width:500px" name="cat_id">
        {html_options options=$ppm_categories selected=$categories_selected}
      </select>
    </td>
    <td>
      <a class="icon-help-circled" title="{'DEST_ALBUM_HELP_BATCH'|@translate}" style="cursor:help"></a>
    </td>
  </tr>
  <tr>
    <td colspan=2 style="width:100%">
      <label><input type="checkbox" name="test_mode" value="1" checked /> {'TEST_MODE_DESCR'|@translate}</label>
    </td>
  </tr>
</table>
