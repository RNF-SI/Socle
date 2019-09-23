<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Espace extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('espace_model');
  }


  public function liste_espaces() {
    $data['espaces'] = $this->espace_model->getAll();

    $this->load->view('default/header');
    $this->load->view('liste_espaces', $data);
    $this->load->view('default/footer');
  }


  public function fiche_espace($id_ep) {
    $this->load->model('site_model');

    $ep = $this->espace_model->get($id_ep);
    $sites = $this->site_model->getByEspace($id_ep);

    $data = array('ep' => $ep, 'sites' => $sites);

    $this->load->view('default/header', ['title' => $ep->nom,'scripts' => ['js/fiche_ep.js']]);
    $this->load->view('fiche_espace', $data);
    $this->load->view('default/footer');
  }


  // enregistrement/modification d'un EP
  public function creation($id=NULL) {
    if (! $this->auth->is_admin()) {
      $this->session->set_flashdata('message', 'Seuls les administrateurs peuvent créer un nouvel espace.');
      $this->session->set_flashdata('message-class', 'warning');
      redirect('accueil/index');
    }

    $this->load->model('espace_ref_model');
    $this->load->helper('form_helper');
    $this->load->library('form_validation');

    // enregistrement du site
    if ($this->input->post()) {
      $this->form_validation->set_rules('nom', 'nom', 'required');
      if ($this->form_validation->run()) {
        $data = $this->input->post();
        if (!isset($data['monosite']) || !$data['monosite']) $data['monosite'] = FALSE;
        if (! $id) {
          $id = $this->espace_model->add($data);
          if ($data['monosite']) { // ajout d'un site par défaut
            $this->load->model('site_model');
            $sdata = [
              'ep_id' => $id,
              'nom' => $data['nom'],
              'geom' => $data['geom']
            ];
            $this->site_model->add($sdata);
          }
        } else { // modification
          $this->espace_model->update($id, $data);
          if ($data['monosite']) {
            $this->espace_model->update_monosite($id, $data);
          }
        }

        $this->load->library('session');
        $this->session->set_flashdata('message', "Espace correctement enregistré");
        $this->session->set_flashdata('message-class', 'success');
        redirect('espace/fiche_espace/'.$id);
      } else {
        log_message('ERROR', validation_errors());
      }
    }

    $data = array();

    $data['espaces_ref'] = $this->espace_ref_model->getEspacesRefAvailable();
    $data['groupes'] = array();
    if ($this->auth->in_group(['admin', 'validators'])) {
      $groups = $this->auth->groups()->result();
    } else {
      $groups = $this->auth->get_users_groups()->result();
    }
    foreach($groups as $g) {
      if ($g->id >= 4) {
        $data['groupes'][$g->id] = $g->name;
      }
    }
    if ($id) {
      $data['ep'] = $this->espace_model->get($id);
    }

    $this->load->view('default/header');
    $this->load->view('ajout_ep', $data);
    $this->load->view('default/footer');
  }

  // juste pour l'url
  public function modification($id) {
    return $this->creation($id);
  }


}
