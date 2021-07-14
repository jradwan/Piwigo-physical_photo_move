<!-- physical photo move -->
<div id="ppm" style="bulkAction">
  <table style="width:525px">
    <tr>
      <td>
        <select data-selectize="categories" data-default="" style="width:500px" name="cat_id" placeholder="{'DEST_ALBUM_SELECT_BATCH'|@translate}">
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
</div>

<style>
#ppm .selectize-input {
  min-width: 500px;
  max-width: 500px;
}
#ppm .selectize-control {
  min-width: 500px;
  max-width: 500px;
}
</style>
