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

    if (! $site) {
      show_404();
    }

    $site->photos = $this->photo_model->getBySite($id, TRUE);
    $data['site'] = $site;
    $data['editable'] = $this->site_model->is_editable($id);
    $data['entites_geol'] = $this->site_model->getEntitesGeol($id);

    // calcul taux avancement
    /*
    $adv = $this->site_model->getAdvancement($id);
    $n_sites = 0;
    $total_sites = 0;
    $n_eg = 0;
    $total_eg = 0;
    foreach ($adv as $li) {
      if ($li->niveau == 'site' && $li->site_id == NULL) $total_sites++;
      if ($li->niveau == 'site' && $li->done) $n_sites++;
      if ($li->niveau == 'EG' && $li->eg_id == NULL) $total_eg++;
      if ($li->niveau == 'EG' && $li->done) $n_eg++;
    }
    $taux_adv = $n_sites / $total_sites * 0.5 + ($n_eg / $total_eg) * (0.5 / count($data['entites_geol']));
    $data['avancement'] = round($taux_adv * 100);
    */

    $data_header = [
      'scripts' => ['js/fiche_projet.js'],
      'title' => $site->nom,
      'path'=>$this->site_model->getPath($id),
    ];
    if ($data['editable']) {
      $data_header['scripts'] = array_merge(['lib/leaflet/pm/leaflet.pm.min.js'], $data_header['scripts']);
      $data_header['styles'] = ['lib/leaflet/pm/leaflet.pm.css'];
    }
    $this->load->view('default/header', $data_header);
    $this->load->view('fiche_site/fiche_site', $data);
    $this->load->view('default/footer');
  }

  public function tree_site($id) {
    // Affichage des données sous forme arborescente
    $site = $this->site_model->get($id);

    $scripts = [
      'https://unpkg.com/react@16/umd/react.production.min.js',
      'https://unpkg.com/react-dom@16/umd/react-dom.production.min.js',
      'https://unpkg.com/react-leaflet/dist/react-leaflet.min.js',
      'js/React/dist/treeview.js',
      'js/React/dist/treenode.js',
      'js/React/dist/map.js',
      'js/React/dist/fiche_terrain.js'
    ];
    $this->load->view('default/header', ['scripts' => $scripts, 'title' => $site->nom,
      'path'=>$this->site_model->getPath($id)]);
    $this->load->view('fiche_site/tree_site', $site);
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

  // convertit les données encodées vers un tableau semblable aux paramètres POST
  private static function deserialize_js_array($datastr) {
    $data = json_decode($datastr, TRUE);
    $struct = array();
    foreach ($data as $k => $field) {
      $fname = $field['name'];
      if (substr($field['name'], -2) == '[]') {
        $fname = substr($fname, 0, -2);
        if (!isset($struct[$fname])) {
          $struct[$fname] = [];
        }
        $struct[$fname][] = $field['value'];
      } else {
        $struct[$fname] = $field['value'];
      }
    }
    return $struct;
  }

  public function rubrique_form($id, $rubrique, $type = 'Site') {
    if (! $id) {
      show_404();
    }

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

      $data = $this->deserialize_js_array($this->input->post("data"));
      $this->form_validation->set_data($data);

      if (!isset($config[$rubrique]) || $this->form_validation->run()) {
        $model->update_rubrique($id, $data, $rubrique);
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


  // enregistrement / modification d'un site
  public function creation($id_ep, $id=NULL) {
    if (! $this->auth->logged_in()) {
      $this->session->set_flashdata('message', 'Connectez-vous pour pouvoir accéder à cette page.');
      $this->session->set_flashdata('message-class', 'warning');
      redirect('accueil/index');
    }

    if (! $id_ep) {
      show_404();
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

    if (! $data['ep']) {
      show_404();
    }

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

  //suppression d'un site
  public function suppr_site($id) {
    $this->load->model('site_model');
    $this->load->model('espace_model');

    $sit = $this->site_model->get($id);
    $id_ep = $sit->ep_id;
    $ep = $this->espace_model->get($id_ep);

    if (! $this->site_model->is_editable($id)) {
      $this->session->set_flashdata('message', 'Vous ne pouvez pas supprimer ce site.');
      $this->session->set_flashdata('message-class', 'error');
      redirect('espace/fiche_espace/' . $id_ep);
    }

    $this->site_model->delete($id);

    $this->session->set_flashdata('message', 'Site supprimé.');
    $this->session->set_flashdata('message-class', 'success');
    redirect('espace/fiche_espace/' . $id_ep );
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

    if (! $data) {
      show_404();
    }

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

    if (! $id) {
      show_404();
    }

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

    if (! $eg) {
      show_404();
    }

    $eg->affleurements = $this->affleurement_model->getByEG($id_eg);
    $data['eg'] = $eg;
    $data['site'] = $this->site_model->get($eg->site_id);
    $data['editable'] = $this->site_model->is_editable($eg->site_id);

    $data_header = [
      'scripts' => ['js/fiche_projet.js', 'js/fiche_eg.js'],
      'title' => 'Entité géologique "' . $eg->intitule . '"',
      'path' => $this->entite_geol_model->getPath($id_eg),
    ];
    if ($data['editable']) {
      $data_header['scripts'] = array_merge(['lib/leaflet/pm/leaflet.pm.min.js'], $data_header['scripts']);
      $data_header['styles'] = ['lib/leaflet/pm/leaflet.pm.css'];
    }

    $this->load->view('default/header', $data_header);
    $this->load->view('fiche_eg/fiche_eg', $data);
    $this->load->view('default/footer');
  }

  // Suppression EG
  public function suppr_entite_geol($id) {
    $this->load->model('entite_geol_model');

    $eg = $this->entite_geol_model->get($id);
    $id_site = $eg->site_id;

    if (! $this->site_model->is_editable($id_site)) {
      $this->session->set_flashdata('message', 'Vous ne pouvez pas supprimer cette entité.');
      $this->session->set_flashdata('message-class', 'error');
      redirect('site/fiche_entite_geol/' . $id);
    }

    $this->entite_geol_model->delete($id);

    $this->session->set_flashdata('message', 'Entité supprimée.');
    $this->session->set_flashdata('message-class', 'success');
    redirect('site/fiche_site/' . $id_site . '#Q3-1');
  }

  public function soumission_validation($id_ep) {
    $this->espace_protege_model->change_status($id_ep, 'validation');

  }

// pas de vue pour le moment (peut-être pas nécessaire)
  public function fiche_affleurement($id_affl) {
    $this->load->model('affleurement_model');
    $data = array();
    $affl = $this->affleurement_model->get($id_affl);

    if (! $affl) {
      show_404();
    }

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

  // Suppression affleurement
  public function suppr_affleurement($id) {
    $this->load->model('affleurement_model');
    $this->load->model('entite_geol_model');

    $aff = $this->affleurement_model->get($id);
    $id_eg = $aff->eg_id;
    $eg = $this->entite_geol_model->get($id_eg);
    $id_site = $eg->site_id;

    if (! $this->site_model->is_editable($id_site)) {
      $this->session->set_flashdata('message', 'Vous devez être connecté pour supprimer cet affleurement !');
      $this->session->set_flashdata('message-class', 'danger');
      redirect('site/fiche_entite_geol/' . $id_eg);
    }

    $this->affleurement_model->delete($id);

    $this->session->set_flashdata('message', 'Affleurement supprimé.');
    $this->session->set_flashdata('message-class', 'success');
    redirect('site/fiche_entite_geol/' . $id_eg );
  }

  // Fiche de synthèse d'un EP
  public function resume($id) {
    $this->load->model('photo_model');
    $this->load->library('image_lib');
    $this->load->helper('caracteristiques');
    $site = $this->site_model->get($id);

    if (! $site) {
      show_404();
    }

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

    // Export de la synthèse avec PhpWord
  public function export_synthese($id, $format) {
    $this->load->library('word');
    $this->load->helper('caracteristiques');

    $site = $this->site_model->get($id);

    if (! $site) {
      show_404();
    }

    $this->word->addTitle('Site ' . $site->nom);
    $this->word->addTitle('Caractéristiques du site', 2);


    $siteElements = $this->site_model->getAllSubelements($id);

    $structure = load_structure();

    $table = $this->word->addTable();
    $cellStyle = ['borderSize' => 6, 'valign'=>'center'];

    foreach ($structure['site'] as $chapitre) {
        $table->addRow();
        $cell = $table->addCell(3000, $cellStyle + ['vMerge'=>'restart']);
        $cell->addText($chapitre['titre']);
        $i = 0;
        foreach ($chapitre['rubriques'] as $rubrique) {
          if($i++ > 0) {
            $table->addRow();
            $table->addCell(null, $cellStyle + ['vMerge'=>'continue']);
          }
          $cell = $table->addCell(3000, $cellStyle);
          $cell->addText($rubrique['titre']);
          $cell = $table->addCell(4000, $cellStyle );
          foreach ($rubrique['qcms'] as $code => $titre) {
            if (isset($siteElements['qcms'][$code])) {
              $cell->addText($titre);
              foreach ($siteElements['qcms'][$code] as $item) {
                $cell->addListItem($item['label']);
              }
            }
          }
      }
    }
    $this->word->section->addTextBreak();

    // EG
    $this->word->addTitle('Identification des terrains, des roches et des fossiles', 2);

    foreach ($siteElements['egs'] as $eg) {
      $this->word->addTitle($eg['nom'], 3);
      $table = $this->word->addTable();
      foreach ($structure['entite'] as $rubrique) {
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText($rubrique['titre']);
        $cell = $table->addCell(5000, $cellStyle);
        foreach ($rubrique['qcms'] as $code => $titre) {
          if (isset($eg['qcms'][$code])) {
            if ($titre) $cell->addText($titre);
            foreach ($eg['qcms'][$code] as $item) {
              $cell->addListItem($item['label']);
            }
          }
        }
      }
      $this->word->section->addTextBreak();
    }

    // footer
    $footer = $this->word->section->addFooter();
    $txt = $footer->addTextRun('footer');
    $txt->addText('Généré par l\'application ');
    $txt->addLink(site_url(''), 'SOCLE', 'link');
    $txt->addText(' - Réserves Naturelles de France le ' . date('d/m/Y') . '. ');
    $txt->addLink(site_url('site/resume/' . $id), null, 'link');

    $this->word->out($format);
  }


  // ajax : propriétés espace ref (pour form ajout EP)
  public function ajax_info_espace_ref($ref) {
    $this->load->model('espace_ref_model');

    $espace = $this->espace_ref_model->getEspaceRef($ref);

    $this->output->set_content_type('application/json')
      ->set_output(json_encode($espace));
  }


  public function save_qcm($type, $id) {
    $resp = ['success' => false];

    if ($type == 'Site') {
      $model = $this->site_model;
    } elseif ($type == 'EG') {
      $this->load->model('entite_geol_model');
      $model = $this->entite_geol_model;
    }
    $model->set_nouvelle_version(TRUE);

    if ($this->input->post('data')) {
      $data = json_decode($this->input->post('data'));
      $success = $model->saveChanges($id, $data);
      $resp['success'] = $success;
      $resp['new_data'] = $model->getReponses($id);
    }

    $this->output->set_content_type('application/json')
      ->set_output(json_encode($resp));

  }

}
