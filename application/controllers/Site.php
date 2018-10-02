<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('site_model');
  }

  public function fiche_site($id) {
    $this->load->model('photo_model');
    $this->load->library('image_lib');
    $data = array();
    $site = $this->site_model->get($id);

    $site->photos = $this->photo_model->getBySite($id, TRUE);
    $data['site'] = $site;
    $data['editable'] = $this->site_model->is_editable($id);
    $data['entites_geol'] = $this->site_model->getEntitesGeol($id);

    $this->load->view('default/header', ['scripts' => ['js/fiche_projet.js'], 'title' => $site->nom,
      'path'=>$this->site_model->getPath($id)]);
    $this->load->view('fiche_site/fiche_site', $data);
    $this->load->view('default/footer');
  }

  public function rubrique_content($id, $rubrique, $type = 'Site') {
    // chargement asynchrone du contenu du panel

    // gestion des exceptions (ne se comportent pas comme les rubriques standard)
    switch ($rubrique) {
      case 'points_de_vue':
      case 'photos_eg':
        $this->rubrique_points_de_vue($id, $type);
        return;
      case 'elements_remarquables':
        $this->rubrique_elements_remarquables($id);
        return;
      case 'contexte_sismique':
        $this->rubrique_contexte_sismique($id);
        return;
    }

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
      $data['site']->feuilles_cartes = $this->site_model->getFeuillesCartes($id);
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
        $this->output->set_content_type('application/json')
          ->set_output(json_encode(array('success' => TRUE)));
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

  // passe le site à l'état publié (ajax)
  public function publication($id) {
    $rep = ['success'=>TRUE];
    $res = $this->site_model->change_status($id, 'validé');
    if (!$res) {
      $rep['success'] = FALSE;
      $rep['message'] = 'Vous ne disposez pas de droits pour effectuer cette opération';
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($rep));
  }

  public function rubrique_points_de_vue($id, $type) {
    $this->load->model('photo_model');
    $this->load->library('image_lib');

    $data['photos'] = $type == 'Site' ? $this->photo_model->getBySite($id) : $this->photo_model->getByEG($id);
    $data['editable'] = $this->photo_model->is_editable($id, $type);
    $view = $type == 'Site' ? 'points_de_vue' : 'photos_eg';
    $this->output->set_output($this->load->view('fiche_site/rubriques/' . $view, $data, TRUE));
  }

  public function rubrique_contexte_sismique($id) {
    $data = $this->site_model->getSeismes($id);

    $this->output->set_output($this->load->view('fiche_site/rubriques/contexte_sismique', $data, TRUE));
  }

  public function rubrique_elements_remarquables($id) {
    $data['caracts'] = $this->site_model->getAllElementsRemarquables($id);
    $data['site_id'] = $id;
    $this->output->set_output($this->load->view('fiche_site/rubriques/elements_remarquables', $data, TRUE));
  }

  // ajout d'une photo au site ou EG (ajax)
  public function ajout_photo($id, $type='Site') {
    $this->load->helper('form_helper');

    if ($this->input->post()) {
      $config = [
        'upload_path' => './photos',
        'allowed_types' => 'jpg|png|pdf',
        'file_name' => uniqid("img_{$type}_{$id}_")
      ];
      $this->load->library('upload', $config);

      if ($this->upload->do_upload('photo')) {
        $this->load->model('photo_model');
        $data = $this->input->post();
        $fname = $this->upload->data('file_name');
        $data['url'] = $fname;
        $data['mimetype'] = $this->upload->data('file_type');
        $this->photo_model->add_photo($data, $type);
      } else {
        log_message('error', $this->upload->display_errors());
        $this->output->set_output($this->upload->display_errors());
        return;
      }
    }

    $this->output->set_content_type('application/json')
      ->set_output(json_encode(array('success' => TRUE)));
  }


  // suppression de photo (ajax)
  public function suppr_photo($id_photo) {
    $this->load->model('photo_model');
    $photo = $this->photo_model->get($id_photo);
    if ($this->site_model->is_editable($photo->site_id)) {
      $success = $this->photo_model->delete($id_photo);
    } else {
      $this->output->set_output('{"error": "Opération interdite", "success": false}');
    }
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
        $this->session->set_flashdata('message', "Entité correctement enregistrée");
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
    $this->load->model('affleurement_model');
    $data = array();
    $eg = $this->entite_geol_model->get($id_eg);
    $eg->affleurements = $this->affleurement_model->getByEG($id_eg);
    $data['eg'] = $eg;
    $data['site'] = $this->site_model->get($eg->site_id);
	  $data['editable'] = $this->site_model->is_editable($eg->site_id);

    $this->load->view('default/header', ['scripts' => ['js/fiche_projet.js', 'js/fiche_eg.js'],
      'title' => 'Entité géologique "' . $eg->intitule . '"',
      'path' => $this->entite_geol_model->getPath($id_eg)]);
    $this->load->view('fiche_eg/fiche_eg', $data);
    $this->load->view('default/footer');
  }

  public function soumission_validation($id_ep) {
    $this->espace_protege_model->change_status($id_ep, 'validation');

  }

// pas de vue pour le moment (peut-être pas nécessaire)
  public function fiche_affleurement($id_affl) {
    $this->load->model('affleurement_model');
    $data = array();
    $affl = $this->affleurement_model->get($id_affl);
    $data['affl'] = $affl;
    $data['eg'] = $this->entite_geol_model->get($affl->eg_id);
    $data['editable'] = TRUE; //$this->espace_protege_model->is_editable($eg->espace_protege_id);

    $this->load->view('default/header', ['scripts' => ['js/fiche_affl.js'],
      'title' => 'Affleurement "' . $affl->nom . '"']);
    $this->load->view('affleurement/fiche_affleurement', $data);
    $this->load->view('default/footer');
  }


  // ajout / modif d'affleurement
  public function ajout_affleurement($id_eg, $id_affl=NULL) {
    $this->load->model('affleurement_model');
    $this->load->helper('form_helper');
    $this->load->library('form_validation');

    // enregistrement de l'EG
    if ($this->input->post()) {
      $this->form_validation->set_rules('nom', 'nom', 'required');
      if ($this->form_validation->run()) {
        $data = $this->input->post();
        $data['eg_id'] = $id_eg;
        if (is_null($id_affl)) { // insert
          $id_affl = $this->affleurement_model->add($data);
        } else { // update
          $this->affleurement_model->update($id_affl, $data);
        }

        $this->load->library('session');
        $this->session->set_flashdata('message', "Affleurement enregistré");
        $this->session->set_flashdata('message-class', 'success');
        redirect('site/fiche_entite_geol/'.$id_eg);
      } else {
        log_message('ERROR', validation_errors());
      }
    }

    $this->load->model('entite_geol_model');
    $data = array(
      'eg' => $this->entite_geol_model->get($id_eg),
      'affl' => $this->affleurement_model->get($id_affl)
    );
    $data['site'] = $this->site_model->get($data['eg']->site_id);

    $this->load->view('default/header', ['scripts' => ['js/form_affl.js']]);
    $this->load->view('affleurement/affleurement_form', $data);
    $this->load->view('default/footer');
  }

  public function modification_affleurement($id_affl, $id_eg) {
    return $this->ajout_affleurement($id_eg, $id_affl);
  }


  // Fiche de synthèse d'un EP
  public function resume($id) {
    $this->load->model('photo_model');
    $this->load->library('image_lib');
    $this->load->helper('caracteristiques');
    $site = $this->site_model->get($id);

    $data = new stdClass();
    $siteElements = $this->site_model->getAllSubelements($id);
    $data->site = $site;
    $data->elements = $siteElements;
    //$data->caract = $this->site_model->getCaracteristiques($id);
    //$data->photos = $this->photo_model->getBySite($id);

    $this->load->view('default/header', ['title' => 'Synthèse ' . $site->nom,
      'scripts'=>['js/synthese_site.js']]);
    $this->load->view('fiche_site/synthese_site', $data);
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
