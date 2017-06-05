<h2>{$TITLE} &#8250; {'Edit photo'|@translate} {$TABSHEET_TITLE}</h2>

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
            <strong>{'Current file location:'|@translate}</strong> {$current_path}<br>
            <strong>{'Current physical album:'|@translate}</strong> {$storage_category}<br>
            <br>
            <strong>{'Select destination:'|@translate}</strong>
            <a class="icon-help-circled" title="{'Only other physical albums are listed. Use the \'Linked Albums\' field on the Properties tab to \'move\' this photo into a virtual album.'|@translate}"></a>
            <br>
            <select class="categoryList" name="cat_id" size="10">
              {html_options options=$categories selected=$categories_selected}
            </select>
            <br>
          </p>
          <p style="text-align:left"><input class="submit" type="submit" value="{'Move'|@translate}" name="move_photo"></p>
        </form>
      </td>
    </tr>
  </table>
</fieldset>
