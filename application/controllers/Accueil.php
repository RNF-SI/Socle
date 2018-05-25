<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accueil extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->library('image_lib');
		$data = array();
		$this->load->model(['espace_model', 'photo_model']);
		$data['espaces'] = $this->espace_model->getEspacesWithPhoto(6);
		$data['photo'] = $this->photo_model->getRandomPhoto();
		$this->load->view('default/header', ['scripts'=>['lib/leaflet/L.Deflate.js', 'js/accueil.js']]);
		$this->load->view('accueil', $data);
		$this->load->view('default/footer');
	}
}
