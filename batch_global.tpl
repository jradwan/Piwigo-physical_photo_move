<div id="ppm">
  {'DEST_ALBUM'|@translate}
  <a class="icon-help-circled" title="{'DEST_ALBUM_HELP_BATCH'|@translate}"></a>
  <br>
  <select class="categoryList" name="target_cat_id" size="10">
    {html_options options=$categories selected=$categories_selected}
  </select>
  <p>
  <label><input type="checkbox" name="test_mode" value="1" {if $ppm_test_mode}checked="checked"{/if} /> {'TEST_MODE_DESCR'|@translate}</label>
</div>
