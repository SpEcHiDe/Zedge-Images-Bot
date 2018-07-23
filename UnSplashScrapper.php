<?php
/*
 * @author @SpEcHIDe
 */


function GetUnSplashImages($search_query) {
  $required_url = "https://unsplash.com/search/photos/" . $search_query . "";
  $html = fetch_website($required_url);
  preg_match_all('/<img[^>]+>/i', $html, $result);
  // => https://stackoverflow.com/a/143455/4723940
  $img = array();
  foreach($result[0] as $key => $img_tag) {
    preg_match_all('/(alt|title|src|srcSet)=("[^"]*")/i', $img_tag, $img[$key]);
  }
  // $img will have the img srcSet contents
  // passing that to Telegram required format
  $return_array = array();
  foreach ($img as $key => $value) {
    if ($key > 23) {
      // InlineQueryResultPhoto can have only less than 50 results
    }
    else {
      // initialize variables here
      $alt = "";
      $thumb_url = "";
      $images = array();
      // initialize variables here

      $index_wall_item = $value[1];
      $wall_paper_item = $value[2];
      if (count($index_wall_item) == 3) {
        $alt = $wall_paper_item[0];
        $thumb_url = explode(" ", explode(", ", $wall_paper_item[2])[0])[0];
        $images = explode(", ", $wall_paper_item[1]);
      }
      else if (count($index_wall_item) == 2) {
        $alt = " NO CAPTION AVAILABLE ";
        $thumb_url = explode(" ", explode(", ", $wall_paper_item[0])[0])[0];
        $images = explode(", ", $wall_paper_item[1]);
      }
      else {
      }
      $no_of_images = count($images);
      if ($no_of_images > 0) {
        $best_q_image = explode(" ",$images[$no_of_images - 1])[0];
        $medium_q_image = explode(" ",$images[$no_of_images / 2])[0];
        $r = array(
          "type" => "photo",
          "id" => $key,
          "photo_url" => $medium_q_image,
          "thumb_url" => $thumb_url,
          "title" => $alt,
          "description" => $alt,
          "caption" => "Join @UnSplashImages for the best wallpapers and ringtones!",
          "parse_mode" => "Markdown",
          "reply_markup" => array(
            "inline_keyboard" => array(
              array(
                array(
                  "text" => "High Resolution Download",
                  "url" => $best_q_image
                )
              )
            )
          )
        );
        $return_array[] = $r;
      }
      else {
      }
    }
  }
  return $return_array;
}
