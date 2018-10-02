<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Gestion des Utilisateurs
// utilise la librairie flexi-auth


class Utilisateurs extends CI_Controller {

  public function __construct() {
    parent::__construct();

  }

  private function check_admin() {
    if (!$this->auth->is_admin())	{
			$this->session->set_flashdata('message', 'Vous devez être administrateur pour voir cette page');
      $this->session->set_flashdata('message-class', 'danger');
			redirect('accueil/index');
		}
  }

  // liste des utilisateurs
  public function gestion() {
    $this->check_admin();

    $data = array();
    $data['users'] = $this->auth->users()->result();

    $this->load->view('default/header', ['scripts' => ['js/gestion_utilisateurs.js']]);
    $this->load->view('utilisateurs/liste_utilisateurs', $data);
    $this->load->view('default/footer');
  }

  // formulaire de souscription et traitement
  public function subscribe() {
    $this->load->helper('form_helper');

    $data = array();

    if ($this->input->post()) {
      $this->load->library('form_validation');
      $this->form_validation->set_rules('nom', 'nom', 'required');
      $this->form_validation->set_rules('email', 'email', 'required|valid_email');
      $this->form_validation->set_rules('password', 'mot de passe', 'required|min_length[6]');
      $this->form_validation->set_rules('pwd_confirm', 'confirmation du mot de passe', 'required|matches[password]');

      if ($this->form_validation->run()) {
        $moredata = ['last_name'=>$this->input->post('nom')];
        $res = $this->auth->register($this->input->post('email'), $this->input->post('password'), $this->input->post('email'), $moredata);
        if ($res) {
          // envoi de mail
          $this->load->library('email');
          $this->email->from($this->config->item('admin_email'));
          $this->email->to($this->config->item('admin_email'));
          $this->email->subject('Base SOCLE : demande d\'inscription');
          $message = $this->load->view('utilisateurs/mail_subscription_new_user', $this->input->post(), TRUE);
          $this->email->message($message);
          $this->email->send();
          $this->session->set_flashdata(['message'=>'Votre compte a été créé. Un administrateur validera votre demande et activera votre compte.',
            'message_class' => 'success']);
          redirect('accueil/index');
        } else {
          $data['message'] = 'Echec de la création d\'utilisateur : ' . $this->auth->messages();
          $data['message_class'] = 'danger';
        }
      }
    }

    $this->load->view('default/header');
    $this->load->view('utilisateurs/subscription_form', $data);
    $this->load->view('default/footer');
  }

  public function test_mail() {
    //phpinfo();
    $this->load->library('email');
    $this->email->from($this->config->item('admin_email'));
    $this->email->to($this->config->item('admin_email'));
    $this->email->subject('Base SOCLE : test');
    $message = 'Ceci est un test.';
    $this->email->message($message);
    $this->email->send();
  }

  // formulaire de login et traitement (ajax)
  public function login() {
    if ($this->input->post()) {
      // traitement
      $this->load->library('form_validation');
      $this->form_validation->set_rules('email', 'email', 'required|valid_email');
      $this->form_validation->set_rules('password', 'mot de passe', 'required');
      if ($this->form_validation->run()) {
        $success = $this->auth->login($this->input->post('email'), $this->input->post('password'), TRUE);
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
    $this->check_admin();

    $this->load->library('form_validation');
    $data_head = array();
    if ($this->input->post()) {

      $this->form_validation->set_rules('username', 'nom d\'utilisateur', 'required');
      $this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[users.email]');
      $this->form_validation->set_rules('password', 'mot de passe', 'required|min_length[6]');
      $this->form_validation->set_rules('password_valid', 'validation du mot de passe', 'required|matches[password]');

      if ($this->form_validation->run()) {
        $input = $this->input->post();
        $groups = array($input['privilege']);
        if (isset($input['groups']))
          $groups = array_merge($groups, $input['groups']);
        $res = $this->auth->register(
          $input['email'],
          $input['password'],
          $input['email'],
          array('company' => $input['company']),
          $groups
        );
        if ($res) {
          $this->session->set_flashdata('message', 'utilisateur créé avec succès');
          $this->session->set_flashdata('message-class', 'success');
          redirect('utilisateurs/gestion');
        } else {
          $data_head['message'] = $this->auth->errors();
          $data_head['message_class'] = 'danger';
        }
      }
    }

    $groups = $this->auth->groups()->result();
    $data['groups'] = array();
    foreach ($groups as $grp) {
      if (! in_array($grp->name, ['admin', 'members', 'superusers', 'validators']))
        $data['groups'][$grp->id] = $grp->name;
    }

    $this->load->view('default/header', $data_head);
    $this->load->view('utilisateurs/creation', $data);
    $this->load->view('default/footer');
  }

  // active / désactive un utilisateur (pour ajax)
  public function toggle_activate($id) {
    if (!$this->auth->is_admin())	{
      $data = array('success' => FALSE, 'message' => 'seul un administrateur peut effectuer cette action');
		} else {
      $user = $this->auth->user($id)->row();
      if ($user->active) {
        $res = $this->auth->deactivate($id);
        $data['action'] = 'deactivated';
      } else {
        $res = $this->auth->activate($id);
        $data['action'] = 'activated';
      }
      $data['success'] = $res;
      $data['message'] = $this->auth->errors();
    }

    $this->output->set_content_type('application/json');
    $this->output->set_output(json_encode($data));
  }


  public function creation_groupe() {
    $this->check_admin();

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

  // suppression user pour ajax
  public function user_delete($id) {
    $this->check_admin();
    $res = $this->auth->delete_user($id);

    $this->output->set_content_type('application/json');
    $this->output->set_output(json_encode(['success'=>$res]));
  }

  // pour ajax : liste des groupes
  public function user_groups($userid) {
    $data['user_groups'] = $this->auth->get_users_groups($userid)->result();
    $data['groups'] = $this->auth->groups()->result();
    $data['userid'] = $userid;

    $this->output->set_output($this->load->view('auth/user_groups', $data, TRUE));
  }

  public function user_add_group($userid, $groupid) {
    if (! $this->auth->in_group($groupid, $userid)) {
      $res = $this->auth->add_to_group($groupid, $userid);
    } else {
      $res = FALSE;
    }
    $this->output->set_output($res ? 'true' : 'false');
  }

  public function user_remove_group($userid, $groupid) {
    $res = $this->auth->remove_from_group($groupid, $userid);
    $this->output->set_output($res ? 'true' : 'false');
  }

}
