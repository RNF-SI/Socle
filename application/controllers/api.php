<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// requêtes ajax

class Api extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->output->set_content_type('application/json');
  }

  private function send($data) {
    $this->output->set_output(json_encode($data));
  }

  //récupere le nom est la geom du l'espace de reference
  public function getEspaceRef($code) {
    $this->load->model('espace_ref_model');
    $data = $this->espace_ref_model->getEspaceWkt($code);
    $this->send($data);
  }


}
