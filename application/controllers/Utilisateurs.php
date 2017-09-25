<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Gestion des Utilisateurs
// utilise la librairie flexi-auth


class Utilisateurs extends CI_Controller {

  public function __construct() {
    parent::__construct();

  }

  // liste des utilisateurs
  public function gestion() {

    if (!$this->auth->is_admin())	{
			$this->session->set_flashdata('messages', 'Vous devez être administrateur pour voir cette page');
			redirect('accueil/index');
		}

    $data = array();
    $data['users'] = $this->auth->users()->result();

    $this->load->view('default/header');
    $this->load->view('utilisateurs/liste_utilisateurs', $data);
    $this->load->view('default/footer');
  }

  // formulaire de souscription et traitement (ajax)
  public function subscribe() {
    $cont = $this->load->view('utilisateurs/subscription_form', NULL, FALSE);

    $this->output->set_output($cont);

  }

  // formulaire de login et traitement (ajax)
  public function login() {
    if ($this->input->post()) {
      // traitement
      $this->load->library('form_validation');
      $this->form_validation->set_rules('email', 'email', 'required|valid_email');
      $this->form_validation->set_rules('password', 'mot de passe', 'required');
      if ($this->form_validation->run()) {
        $success = $this->auth->login($this->input->post('email'), $this->input->post('password'));
        $data = array('success' => $success);
        if (!$success)
          $data["message"] = "Email ou mot de passe incorrect.";
      } else {
        $data = array('success' => FALSE, 'message' => $this->form_validation->error_string());
      }
      $this->output->set_content_type('application/json');
      $this->output->set_output(json_encode($data));
      return;
    }

    $cont = $this->load->view('utilisateurs/login_form', '', TRUE);

    $this->output->set_output($cont);

  }

  public function logout() {
    $this->auth->logout();

    $this->output->set_content_type('application/json');
    $this->output->set_output(json_encode(array('logout' => TRUE)));
  }

  // ajout d'un utilisateur
  public function creation() {
    $this->load->library('form_validation');
    if ($this->input->post()) {

      $this->form_validation->set_rules('username', 'nom d\'utilisateur', 'required');
      $this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[users.email]');
      $this->form_validation->set_rules('password', 'mot de passe', 'required|min_length[6]');
      $this->form_validation->set_rules('password_valid', 'validation du mot de passe', 'required|matches[password]');

      if ($this->form_validation->run()) {
        $input = $this->input->post();
        $groups = array($input['privilege']);
        if (isset($input['groups']))
          $groups += $input['groups'];
        $res = $this->auth->register(
          $input['email'],
          $input['password'],
          $input['email'],
          array('company' => $input['company']),
          $groups
        );
        if ($res) {
          $this->session->set_flashdata('message', 'utilisateur créé avec succès');
          redirect('utilisateurs/gestion');
        } else {
          $data['message'] = $this->auth->errors();
        }
      }
    }


    $groups = $this->auth->groups()->result();
    $data['groups'] = array();
    foreach ($groups as $grp) {
      if (! in_array($grp->name, ['admin', 'members', 'superusers', 'validators']))
        $data['groups'][$grp->id] = $grp->name;
    }

    $this->load->view('default/header');
    $this->load->view('utilisateurs/creation', $data);
    $this->load->view('default/footer');
  }

  // active un utilisateur (pour ajax)
  public function activate($id) {
    if (!$this->auth->is_admin())	{
      $data = array('success' => FALSE, 'message' => 'seul un administrateur peut effectuer cette action');
		} else {
      $this->auth->update($id, array('active' => 1));
      $data = array('success' => TRUE);
    }

    $this->output->set_content_type('application/json');
    $this->output->set_output(json_encode($data));
  }

  public function creation_groupe() {
    $this->load->library('form_validation');

    $this->form_validation->set_rules('name', 'nom du groupe', 'required');
    if ($this->input->post() && $this->form_validation->run()) {
      $this->auth->create_group($this->input->post('name'), $this->input->post('description'));
      redirect('accueil/index');
    }

    $data = array();

    $this->load->view('default/header');
    $this->load->view('utilisateurs/creation_groupe', $data);
    $this->load->view('default/footer');
  }


}
