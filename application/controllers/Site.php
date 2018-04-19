<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('site_model');
  }


  public function fiche_site($id) {
    $data = array();
    $site = $this->site_model->get($id);

    // validation des droits d'affichage selon statut
    /*if ($site->statut_validation == 'attente') {
      $groups = ['admin', $ep->group_id];
    } elseif ( $site->statut_validation == 'validation') {
      $groups = ['admin', 'validators', $site->group_id];
    }*/

    /* if ($ep->statut_validation != 'publié' && !$this->auth->in_group($groups)) {
      $this->session->set_flashdata('message', 'Vous n\'avez pas les droits pour voir cette page.<br />Veuillez vous identifier.');
      $this->session->set_flashdata('message-class', 'danger');
      redirect('accueil/index');
    } */

    $data['site'] = $site;
    $data['editable'] = $this->site_model->is_editable($id);
    $data['entites_geol'] = $this->site_model->getEntitesGeol($id);

    $this->load->view('default/header', ['scripts' => ['js/fiche_projet.js'], 'title' => $site->nom]);
    $this->load->view('fiche_site/fiche_site', $data);
    $this->load->view('default/footer');
  }

  public function rubrique_content($id, $rubrique, $type = 'Site') {
    // chargement asynchrone du contenu du panel
    $this->load->helper('caracteristiques_helper');

    if ($type == 'Site') {
      $model = $this->site_model;
      $data = array('site' => $model->get($id));
      // hack pas beau pour éviter d'avoir à tout remplacer
      $data['ep'] = $data['site'];
    } elseif ($type == 'EG') {
      $this->load->model('entite_geol_model');
      $model = $this->entite_geol_model;
    }

    $data['caracteristiques'] = $model->getCaracteristiques($id, $rubrique);
    $data['complements'] = $model->getComplementsRubrique($id, $rubrique);
    $comment = $model->getCommentaire($id, $rubrique);
    $data['commentaire'] = empty($comment) ? (object)array('commentaire' => '') : $comment;

    if ($rubrique == 'infos_preliminaires') {
      $data['site']->feuilles_cartes = $this->espace_protege_model->getFeuillesCartes($id);
    }


    $this->output->set_output($this->load->view('fiche_site/rubriques/' . $rubrique . '.php', $data, TRUE));
  }


  public function rubrique_form($id, $rubrique, $type = 'Site') {
    $this->load->helper('caracteristiques_helper');
    $this->load->helper('form_helper');
    $this->load->model('qcm_model');
    $this->load->library('form_validation');

    if ($type == 'Site') {
      $model = $this->site_model;
    } elseif ($type == 'EG') {
      $this->load->model('entite_geol_model');
      $model = $this->entite_geol_model;
    }

    // traitement du formulaire
    if ($this->input->post()) {
      // règles par formulaire
      $config = array();

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
    $data['site'] = $model->get($id); // TODO : modifier le nom de variable

    $qcms = $model->getCaracteristiquesForm($id, $rubrique);

    $data['site']->caracteristiques = $qcms;
    $data['site']->complements = $model->getComplementsRubrique($id, $rubrique);
    $comment = $model->getCommentaire($id, $rubrique);
    $data['site']->commentaire = empty($comment) ? (object)array('commentaire' => '') : $comment;

    // hack pas beau pour éviter d'avoir à tout remplacer
    $data['ep'] = $data['site'];

    $this->output->set_output($this->load->view('fiche_site/rubriques/' . $rubrique . '_form.php', $data, TRUE));
  }


  // enregistrement d'un nouveau site
  public function creation($id_ep, $id=NULL) {
    if (! $this->auth->logged_in()) {
      $this->session->set_flashdata('message', 'Connectez-vous pour pouvoir accéder à cette page.');
      $this->session->set_flashdata('message-class', 'warning');
      redirect('accueil/index');
    }

    $this->load->model('espace_model');
    $this->load->helper('form_helper');
    $this->load->library('form_validation');

    // enregistrement du site
    if ($this->input->post()) {
      $this->form_validation->set_rules('nom', 'nom', 'required');
      $this->form_validation->set_rules('geom', 'périmètre', 'required');
      if ($this->form_validation->run()) {
        $data = $this->input->post();
        $data['statut_validation'] = 'attente';
        if ($id) {
          $this->site_model->update($id, $data);
        } else {
          $id = $this->site_model->add($data);
        }
        $this->load->library('session');
        $this->session->set_flashdata('message', "Site correctement enregistré");
        $this->session->set_flashdata('message-class', 'success');
        redirect('site/fiche_site/'.$id);
      } else {
        log_message('ERROR', validation_errors());
      }
    }

    $data = array();
    if ($id) {
      $data['site'] = $this->site_model->get($id);
    }
    $data['ep'] = $this->espace_model->get($id_ep);

    $this->load->view('default/header', [
      'scripts' => ['lib/leaflet/pm/leaflet.pm.min.js', 'js/ajout_site.js'],
      'styles' => ['lib/leaflet/pm/leaflet.pm.css']
    ]);
    $this->load->view('ajout_site', $data);
    $this->load->view('default/footer');
  }

  public function modification($id, $id_ep) {
    return $this->creation($id_ep, $id);
  }

  // ajout / modif d'entité géol
  public function ajout_eg($id_site, $id_eg=NULL) {
    $this->load->model('entite_geol_model');
    $this->load->helper('form_helper');
    $this->load->library('form_validation');

    // enregistrement de l'EG
    if ($this->input->post()) {
      $this->form_validation->set_rules('intitule', 'nom', 'required');
      if ($this->form_validation->run()) {
        $data = $this->input->post();
        $data['site_id'] = $id_site;
        if (is_null($id_eg)) { // insert
          $id_eg = $this->entite_geol_model->add($data);
        } else { // update
          $this->entite_geol_model->update($id_eg, $data);
        }

        $this->load->library('session');
        $this->session->set_flashdata('message', "Espace correctement entegistré");
        $this->session->set_flashdata('message-class', 'success');
        redirect('site/fiche_entite_geol/'.$id_eg);
      } else {
        log_message('ERROR', validation_errors());
      }
    }

    $data = array(
      'site' => $this->site_model->get($id_site),
      'id_eg' => $id_eg,
      'eg' => $this->entite_geol_model->get($id_eg)
    );
    $data['echelle_geol'] = $this->entite_geol_model->echelle_geol();

    $this->load->view('default/header', ['scripts' => ['lib/jquery.bonsai/jquery.bonsai.js', 'js/form_eg.js'],
      'styles' => ['lib/jquery.bonsai/jquery.bonsai.css']]);
    $this->load->view('fiche_eg/eg_form', $data);
    $this->load->view('default/footer');
  }

  public function fiche_entite_geol($id_eg) {
    $this->load->model('entite_geol_model');
    $data = array();
    $eg = $this->entite_geol_model->get($id_eg);
    $data['eg'] = $eg;
    $data['site'] = $this->site_model->get($eg->site_id);
	  $data['editable'] = TRUE; //$this->espace_protege_model->is_editable($eg->espace_protege_id);

    $this->load->view('default/header', ['scripts' => ['js/fiche_projet.js', 'js/fiche_eg.js'],
      'title' => 'Entité géologique "' . $eg->intitule . '"']);
    $this->load->view('fiche_eg/fiche_eg', $data);
    $this->load->view('default/footer');
  }

  public function soumission_validation($id_ep) {
    $this->espace_protege_model->change_status($id_ep, 'validation');

  }

  // Fiche de synthèse d'un EP
  public function resume($id_ep) {
    $ep = $this->espace_protege_model->get($id_ep);

    $data = new stdClass();
    $data->ep = $ep;
    $data->caract = $this->espace_protege_model->getCaracteristiques($id_ep);

    $this->load->view('default/header', ['title' => 'Synthèse ' . $ep->nom_ep]);
    $this->load->view('fiche_ep/synthese_ep', $data);
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
