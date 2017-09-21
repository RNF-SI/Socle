<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carto extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->output->set_content_type('application/json');
  }

  // encode en geojson des données
  // structure : [[properties=>[...], geom=><geom>]]
  private function _create_geoJson($features) {
    $json = [
      'type' => "FeatureCollection",
      'features' => []
    ];

    foreach ($features as $ft) {
        $geom = is_array($ft['geom']) ? $ft['geom'] : json_decode($ft['geom']);
        $ftJson = [
          'type' => 'Feature',
          'geometry' =>  $geom,
          'properties' => $ft['properties']
        ];
        array_push($json['features'], $ftJson);
    }
    return json_encode($json);
  }

  //renvoie la géometrie de l'EP
  public function espace_protege_geom($id_ref) {
    $this->load->model('espace_ref_model');

    $data = $this->espace_ref_model->getEspaceRefGeom($id_ref);

    $this->output->set_output($this->_create_geoJson(array($data)));

  }

  // proxy pour contourner le cross-origin avec le BRGM
  // et simplifier le traitement
  public function featureInfoProxy() {
    $params = $this->input->get();
    // params: height, width, x, y, bbox
    $params += [
      'SERVICE' => 'WMS',
      'VERSION'=> '1.1.1',
      'REQUEST' => 'GetFeatureInfo',
      'LAYERS' => 'SCAN_GEOL50',
      'SRS' => 'EPSG:4326',
      'QUERY_LAYERS' => 'SCAN_GEOL50',
      'FEATURE_COUNT' => 100
    ];
    $base_url = 'http://infoterre.brgm.fr/services/gfi';
    $url = $base_url . '?' . http_build_query($params);
    $cont = file_get_contents($url);

    $xml = new SimpleXMLElement($cont);
    $struct = $xml->SCAN_GEOL50_layer->SCAN_GEOL50_feature;

    $this->output->set_output(json_encode($struct));
  }
}
