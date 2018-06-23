<?php

/*
 * @author @SpEcHIDe
 */

function fetch_website($required_url) {
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $required_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  $result = curl_exec($curl);
  curl_close($curl);
  return $result;
}

function fetch_json($raw_html, $script_id) {
  // hide DOM parsing errors
  libxml_use_internal_errors(true);
  libxml_clear_errors();

  $dom = new DOMDocument();
  $dom->loadHTML($raw_html);

  $raw_json = $dom->getElementById($script_id)->textContent;
  $final_json = str_replace("&q;", "\"", $raw_json);
  return $final_json;
}

function GetImgUrl($search_query) {
  $BASE_API_URL = "https://www.zedge.net/find";
  // $search_query = "morning";
  $required_url = $BASE_API_URL . "/" . $search_query;
  $raw_html = fetch_website($required_url);
  $raw_json = fetch_json($raw_html, "web-state");
  $json_obj = json_decode($raw_json, true);
  // var_dump($json_obj);
  $keys = array_keys($json_obj);
  // https://stackoverflow.com/a/29737627/4723940
  $before_item_key = $keys[1];
  $return_array = array();
  $items_obj = $json_obj[$before_item_key]["items"];
  foreach ($items_obj as $key => $value) {
    $current_item = $value["layout_params"];
    $high_res_canonical_url = "https://www.zedge.net/wallpaper/" . $value["click_action"]["action"]["item_details"]["reference"]["uuid"] . "";
    if($current_item["detailed_audio_player"] != null) {
      // a ringtone has been returned
      $ring_tone_item = $current_item["detailed_audio_player"];
      $r = array(
        "type" => "audio",
        "id" => $key,
        "audio_url" => $ring_tone_item["audio_url"],
        "title" => $ring_tone_item["title"],
        "caption" => "Subscribe @MalayalamTrollVoice",
        "parse_mode" => "Markdown",
        "performer" => $ring_tone_item["author_name"],
        "reply_markup" => array(
          "inline_keyboard" => array(
            array(
              array(
                "text" => "High Resolution Download",
                "url" => $high_res_canonical_url
              )
            )
          )
        )
      );
      $return_array[] = $r;
    }
    else if($current_item["detailed_thumb"] != null) {
      // a wallpaper has been returned
      $wall_paper_item = $current_item["detailed_thumb"];
      $r = array(
        "type" => "photo",
        "id" => $key,
        "photo_url" => $wall_paper_item["thumb_url"],
        "thumb_url" => $wall_paper_item["thumb_url"],
        "title" => $wall_paper_item["title"],
        "description" => $wall_paper_item["subtitle"],
        "caption" => "Subscribe @MalayalamTrollVoice",
        "parse_mode" => "Markdown",
        "reply_markup" => array(
          "inline_keyboard" => array(
            array(
              array(
                "text" => "High Resolution Download",
                "url" => $high_res_canonical_url
              )
            )
          )
        )
      );
      $return_array[] = $r;
    }
  }
  return $return_array;
}

