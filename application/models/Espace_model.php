<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Représente un espace sur lequel on peut gérer les droits
*/

class Espace_model extends Entite_abstract_model {
  protected $tableName = 'espace_protege';

  public function __construct() {
    $this->load->database();
  }




}
