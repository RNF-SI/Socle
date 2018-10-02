<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webservices {

  public function getServiceXML($base_url, $params) {
    $url = $base_url . '?' . http_build_query($params);

    log_message('DEBUG', $url);

    $cont = file_get_contents($url);

    return new SimpleXMLElement($cont);
  }
}
