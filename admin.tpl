<h2>{$TITLE} &#8250; {'Move photo'|@translate} {$TABSHEET_TITLE}</h2>

<fieldset>
  <legend>{'Physical Photo Move'|@translate}</legend>
  <table>
    <tr>
      <td id="albumThumbnail" style="vertical-align:top">
        <img src="{$TN_SRC}" alt="{'Thumbnail'|@translate}" class="Thumbnail">
      </td>
      <td style="vertical-align:top">
        <form id="ppmove" method="post" action="" enctype="multipart/form-data">
{if isset($show_file_to_move)}
          <p style="text-align:left; margin-top:0;">
            <label><input type="radio" name="file_to_move" value="main"> {'main file'|@translate} ({$original_filename})</label>
            <label><input type="radio" name="file_to_move" value="representative" checked="checked"> {'representative picture'|@translate}</label>
          </p>
{/if}
          <p style="text-align:left; margin-top:0;">
  <fieldset id="catSubset">
    <legend>{'Select target album:'|@translate}</legend>
    <select class="categoryList" name="cat" size="10">
      {html_options options=$category_options selected=$category_options_selected}
    </select>
  </fieldset>

          </p>
          <p style="text-align:left"><input class="submit" type="submit" value="{'Move'|@translate}" name="target_album"></p>
        </form>
      </td>
    </tr>
  </table>
</fieldset>
