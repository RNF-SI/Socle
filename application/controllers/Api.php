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

  //récupere le nom et la geom de l'espace de reference
  public function get_espace_ref($code) {
    $this->load->model('espace_ref_model');
    $data = $this->espace_ref_model->getEspaceWkt($code);
    $this->send($data);
  }

  public function info_entite_geol($id_eg) {
    $this->load->model('entite_geol_model');

  }

  public function get_child_nodes($id_parent) {
    $this->load->model('qcm_model');
    $data = $this->qcm_model->getChildNodes($id_parent);
    $this->send($data);
  }

  // récupère toutes les association qcm - réponse pour un site
  public function get_responses_site($id) {
    $this->load->model('site_model');
    $data = $this->site_model->getReponses($id);
    $this->send($data);
  }

  public function create_qcm_item($id_parent) {
    $response = ['success' => FALSE];
    $label = $this->input->post_get('label');
    if ($label) {
      $this->load->model('qcm_model');
      $this->qcm_model->createItem($id_parent, $label);
      $response['success'] = TRUE;
    }
    $this->send($response);
  }


}
