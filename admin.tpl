<h2>{$TITLE} &#8250; {'Edit photo'|@translate} {$TABSHEET_TITLE}</h2>

<fieldset>
  <legend>{'Move Photo'|@translate}</legend>
  <table>
    <tr>
      <td id="albumThumbnail" style="vertical-align:top">
        <img src="{$TN_SRC}" alt="{'Thumbnail'|@translate}" class="Thumbnail">
      </td>
      <td style="vertical-align:center">
          <p style="text-align:left; margin-top:0;">
          <strong>{'Current physical album:'|@translate}</strong> {$storage_category}<br>
          <strong>{'Current file location:'|@translate}</strong> {$current_path}<br>
      </td>
    </tr>
  </table>
</fieldset>

<form id="ppmove" method="post" action="" enctype="multipart/form-data">
  <fieldset id="test_mode">
    <legend>{'Simulation'|@translate}</legend>
      <label><input type="checkbox" name="test_mode" value="1" {if $ppm_test_mode}checked="checked"{/if} /> {'only perform a simulation (nothing will be changed in the file system or database)'|@translate}</label>
  </fieldset>

  <fieldset>
    <legend>
      Destination Album
      <a class="icon-help-circled" title="{'Only other physical albums are listed. Use the \'Linked Albums\' field on the Properties tab to \'move\' this photo into a virtual album.'|@translate}"></a>
    </legend>
    <select class="categoryList" name="cat_id" size="10">
      {html_options options=$categories selected=$categories_selected}
    </select>
    <p style="text-align:left"><input class="submit" type="submit" value="{'Move'|@translate}" name="move_photo"></p>
  </fieldset>
</form>
