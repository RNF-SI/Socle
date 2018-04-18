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
          'properties' => isset($ft['properties']) ? $ft['properties'] : array()
        ];
        array_push($json['features'], $ftJson);
    }
    return json_encode($json);
  }

  //renvoie la géometrie de l'EP
  public function espace_protege_geom($id) {
    $this->load->model('espace_model');

    $data = $this->espace_model->getGeometry($id);

    $this->output->set_output($this->_create_geoJson(array(array('geom'=>$data))));
  }

  public function site_geom($id) {
    $this->load->model('site_model');
    $data = $this->site_model->getGeometry($id);

    $this->output->set_output($this->_create_geoJson(array(array('geom'=>$data))));
  }


  private function getServiceXML($base_url, $params) {
    $url = $base_url . '?' . http_build_query($params);
    $cont = file_get_contents($url);

    return new SimpleXMLElement($cont);
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
    $xml = $this->getServiceXML($base_url, $params);
    $struct = $xml->SCAN_GEOL50_layer->SCAN_GEOL50_feature;

    $this->output->set_output(json_encode($struct));
  }

  // proxy pour le WFS INPN
  public function getINPNWFSLayers() {
    $params = [
      'service'=> 'WFS',
      'version'=> '1.1.0',
      'request'=> 'GETCAPABILITIES'
    ];
    $xml = $this->getServiceXML('http://ws.carmencarto.fr/WFS/119/fxx_inpn', $params);
    $res = array();
    foreach($xml->FeatureTypeList->FeatureType as $ft) {
      $res[] = ['name' => (string)$ft->Name, 'title' => (string)$ft->Title];
    }
    $this->output->set_output(json_encode($res));
  }

  // transforme une feature GML en GeoJson
  private function gml2json($ftm) {
    $properties = array();
    $geom = array();
    foreach ($ftm->children('ms', True) as $prop) {
      if ($prop->getName() == 'msGeometry') {
        $xgeom = $prop->children('gml')[0];
        if ($xgeom->getName() == 'MultiSurface') { // multipolygone
          $geom['type'] = 'MultiPolygon';
          $geom['coordinates'] = array();

          foreach ($xgeom as $surfmember) {
            $extcoords = $surfmember->Polygon->exterior->LinearRing->posList;
            $coords = array_chunk(explode(' ', (string)$extcoords), 2);
            $geom[coordinates][] = $coords;
          }
        }
      } else {
        $properties[$prop->getName()] = (string)$prop;
      }
    }
  }

  public function getINPNWFSFeature($code) {
    $filter = "<Filter><PropertyIsEqualTo><PropertyName>id_mnhn</PropertyName><Literal>$code</Literal></PropertyIsEqualTo></Filter>";
    $params = [
      'service'=> 'WFS',
      'version'=> '1.1.0',
      'request'=> 'GETFEATURE',
      'srsName'=> 'EPSG:4326',
      'filter' => $filter
    ];
    $code2layer = [
      36 => 'Reserves_naturelles_nationales',
      93 => 'Reserves_naturelles_regionales'
      // à compléter
    ];
    $layerCode = substr($code, 2, 2);
    $params['TypeName'] = $code2layer[$layerCode];
    $xml = $this->getServiceXML('http://ws.carmencarto.fr/WFS/119/fxx_inpn', $params);
    $data = [

    ];
  }
}
