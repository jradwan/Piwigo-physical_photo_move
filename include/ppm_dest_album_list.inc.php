<?php   
  // return list of categories that are actual physical albums
  $query = '
  SELECT
      id,
      name,
      uppercats,
      global_rank
    FROM '.CATEGORIES_TABLE. '
    WHERE dir IS NOT NULL
  ;';
  $cat_selected = 0;
  display_select_cat_wrapper($query, $cat_selected, 'categories', false);
?>
