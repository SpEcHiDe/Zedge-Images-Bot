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


require_once __DIR__ . "/ZedgeScrapper.php";
require_once __DIR__ . "/UnSplashScrapper.php";


function GetImgUrl($search_query) {
  $zedge_ring_tones_and_images = GetZedgeImages($search_query);
  $un_splash_images = GetUnSplashImages($search_query);

  $combined_image = array_merge($zedge_ring_tones_and_images, $un_splash_images);
  return $combined_image;
}
