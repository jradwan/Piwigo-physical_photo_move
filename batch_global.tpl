<!-- physical photo move -->
<table style="width:525px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <select data-selectize="categories" data-default="" name="cat_id" style="width:500px" placeholder="{'DEST_ALBUM_SELECT_BATCH'|@translate}">
        {html_options options=$categories selected=$categories_selected}
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
