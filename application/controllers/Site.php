<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('espace_protege_model');
  }


  public function fiche_site($id_ep) {
    $data = array();
    $data['ep'] = $this->espace_protege_model->get($id_ep);
    $data['entites_geol'] = $this->espace_protege_model->getEntitesGeol($id_ep);

    $this->load->view('default/header', ['scripts' => ['fiche_projet.js']]);
    $this->load->view('fiche_ep/fiche_espace', $data);
    $this->load->view('default/footer');
  }

  public function rubrique_content($id, $rubrique, $type = 'EP') {
    // chargement asynchrone du contenu du panel
    $this->load->helper('caracteristiques_helper');

    if ($type == 'EP') {
      $model = $this->espace_protege_model;
      $data = array('ep' => $model->get($id));
    } elseif ($type == 'EG') {
      $this->load->model('entite_geol_model');
      $model = $this->entite_geol_model;
    }

    $data['caracteristiques'] = $model->getCaracteristiques($id, $rubrique);
    $data['complements'] = $model->getComplementsRubrique($id, $rubrique);
    $comment = $model->getCommentaire($id, $rubrique);
    $data['commentaire'] = empty($comment) ? (object)array('commentaire' => '') : $comment;

    if ($rubrique == 'infos_preliminaires') {
      $data['ep']->feuilles_cartes = $this->espace_protege_model->getFeuillesCartes($id);
    }

    $this->output->set_output($this->load->view('fiche_ep/rubriques/' . $rubrique . '.php', $data, TRUE));
  }


  public function rubrique_form($id, $rubrique, $type = 'EP') {
    $this->load->helper('caracteristiques_helper');
    $this->load->helper('form_helper');
    $this->load->model('qcm_model');
    $this->load->library('form_validation');

    if ($type == 'EP') {
      $model = $this->espace_protege_model;
    } elseif ($type == 'EG') {
      $this->load->model('entite_geol_model');
      $model = $this->entite_geol_model;
    }

    // traitement du formulaire
    if ($this->input->post()) {
      // règles par formulaire
      $config = array(
      );


      $this->form_validation->set_rules(element($rubrique, $config));
      if (!isset($config[$rubrique]) || $this->form_validation->run()) {
        $model->update_rubrique($id, $this->input->post(), $rubrique);

        $this->rubrique_content($id, $rubrique, $type);
        return;
      } else { // non validation du formulaire (renvoie les messages en ajax)
        $this->output->set_content_type('application/json')
          ->set_output(json_encode(array('success' => FALSE, 'message' => validation_errors())));
        return;
      }
    }

    $data = array('rubrique' => $rubrique, 'type_rubrique' => $type);
    $data['ep'] = $model->get($id); // TODO : modifier le nom de variable
    $carIds = array();
    //$questions = array();
    $getId = function($elt) { return $elt->id; };
    foreach($model->getCaracteristiques($id, $rubrique) as $question => $cars) {
      $carIds[$question] = array_map($getId, $cars);
      //array_push($questions, $question);
    }
    $data['ep']->caracteristiques = $carIds;
    $data['ep']->complements = $model->getComplementsRubrique($id, $rubrique);
    $comment = $model->getCommentaire($id, $rubrique);
    $data['ep']->commentaire = empty($comment) ? (object)array('commentaire' => '') : $comment;

    $data['qcms'] = $this->qcm_model->getChoicesByRubrique($rubrique);

    $this->output->set_output($this->load->view('fiche_ep/rubriques/' . $rubrique . '_form.php', $data, TRUE));
  }


  // enregistrement d'un nouvel EP
  public function creation() {
    $this->load->model('espace_ref_model');
    $this->load->helper('form_helper');
    $this->load->library('form_validation');

    // enregistrement de l'EP
    if ($this->input->post()) {
      $this->form_validation->set_rules('surface_ep', 'superficie', 'numeric');
      $this->form_validation->set_rules('nom_ep', 'nom', 'required');
      //$this->form_validation->set_rules('code_national_ep', '' 'required');
      if ($this->form_validation->run()) {
        $id_ep = $this->espace_protege_model->add($this->input->post());
        $this->load->library('session');
        $this->session->set_flashdata('message_success', "Espace correctement entegistré");
        redirect('site/fiche_site/'.$id_ep);
      } else {
        log_message('ERROR', validation_errors());
      }
    }

    $data = array();

    $data['espaces_ref'] = $this->espace_ref_model->getEspacesRefAvailable();

    $this->load->view('default/header');
    $this->load->view('ajout_ep', $data);
    $this->load->view('default/footer');
  }

  // ajout / modif d'entité géol
  public function ajout_eg($id_ep, $id_eg=NULL) {
    $this->load->model('entite_geol_model');
    $this->load->helper('form_helper');
    $this->load->library('form_validation');

    // enregistrement de l'EG
    if ($this->input->post()) {
      $this->form_validation->set_rules('intitule', 'nom', 'required');
      if ($this->form_validation->run()) {
        $data = $this->input->post();
        $data['espace_protege_id'] = $id_ep;
        if (is_null($id_eg)) { // insert
          $this->entite_geol_model->add($data);
        } else { // update
          $this->entite_geol_model->update($id_eg, $data);
        }

        $this->load->library('session');
        $this->session->set_flashdata('message_success', "Espace correctement entegistré");
        redirect('site/fiche_entite_geol/'.$id_ep);
      } else {
        log_message('ERROR', validation_errors());
      }
    }

    $data = array(
      'ep' => $this->espace_protege_model->get($id_ep),
      'id_eg' => $id_eg,
      'eg' => $this->entite_geol_model->get($id_eg)
    );
    $data['echelle_geol'] = $this->entite_geol_model->echelle_geol();

    $this->load->view('default/header', ['scripts' => ['jquery.bonsai.js', 'form_eg.js'], 'styles' => ['jquery.bonsai.css']]);
    $this->load->view('fiche_eg/eg_form', $data);
    $this->load->view('default/footer');
  }

  public function fiche_entite_geol($id_eg) {
    $this->load->model('entite_geol_model');
    $data = array();
    $eg = $this->entite_geol_model->get($id_eg);
    $data['eg'] = $eg;
    $data['ep'] = $this->espace_protege_model->get($eg->espace_protege_id);

    $this->load->view('default/header', ['scripts' => ['fiche_projet.js']]);
    $this->load->view('fiche_eg/fiche_eg', $data);
    $this->load->view('default/footer');
  }


  // ajax : propriétés espace ref (pour form ajout EP)
  public function ajax_info_espace_ref($ref) {
    $this->load->model('espace_ref_model');

    $espace = $this->espace_ref_model->getEspaceRef($ref);

    $this->output->set_content_type('application/json')
      ->set_output(json_encode($espace));
  }

}
