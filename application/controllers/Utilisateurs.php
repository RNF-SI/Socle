<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Gestion des Utilisateurs
// utilise la librairie flexi-auth


class Utilisateurs extends CI_Controller {

  public function __construct() {
    parent::__construct();

  }

  // liste des utilisateurs
  public function gestion() {

    if (!$this->ion_auth->is_admin())	{
			$this->session->set_flashdata('messages', 'Vous devez être administrateur pour voir cette page');
			redirect('accueil/index');
		}

    $data = array();
    $data['users'] = $this->ion_auth->users()->result();

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
    $this->output->set_output(json_encode(array('logout' => 'true')));
  }


}
