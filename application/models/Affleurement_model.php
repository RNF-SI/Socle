<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Affleurement_model extends Entite_abstract_model {
  protected $tableName = 'affleurement';
  protected $geometry_format = 'POINT';

  public function getByEG($id_eg) {
    return $this->getByParent($id_eg, 'eg_id');
  }

}
