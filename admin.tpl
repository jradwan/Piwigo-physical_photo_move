<h2>{$TITLE} &#8250; {'Move photo'|@translate} {$TABSHEET_TITLE}</h2>

<fieldset>
  <legend>{'Move Photo'|@translate}</legend>
  <table>
    <tr>
      <td id="albumThumbnail" style="vertical-align:top">
        <img src="{$TN_SRC}" alt="{'Thumbnail'|@translate}" class="Thumbnail">
      </td>
      <td style="vertical-align:top">
        <form id="ppmove" method="post" action="" enctype="multipart/form-data">
          <p style="text-align:left; margin-top:0;">
            <strong>Current file location:</strong> {$current_path}<br>
            <strong>Current physical album:</strong> {$storage_category}<br>
            <br>
            <strong>{'Select destination (only physical albums are listed):'|@translate}</strong><br>
            <select class="categoryList" name="cat_id" size="10">
              {html_options options=$categories selected=$categories_selected}
            </select>
          </p>
          <p style="text-align:left"><input class="submit" type="submit" value="{'Move'|@translate}" name="target_album"></p>
        </form>
      </td>
    </tr>
  </table>
</fieldset>
