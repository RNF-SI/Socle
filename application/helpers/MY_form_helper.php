<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// form_helper adapté à la génération par bootstrap

function form_input($name, $label, $value = '') {
  return '<div class="form-group">
  <label class="control-label col-sm-3" for="' . $name . '">' . $label .'</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="' . $name . '" name="' . $name . '" value="' . $value . '" />
    </div>
  </div>';
}


function form_checkbox($name, $label, $checked = FALSE) {
  return '<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <div class="checkbox">
        <label><input type="checkbox" id="' . $name . '" name="' . $name . '"
          ' . ($checked ? ' checked' : '') . '/> '
          . $label . '</label>
      </div>
    </div>
  </div>';
}

function form_select($name, $label, $options, $value = NULL) {
  $txt = '<div class="form-group">
    <label class="control-label col-sm-3">'. $label .'</label>
    <div class="col-sm-9">
      <select id="' . $name . '" name="' . $name . '" class="form-control">';
  foreach ($options as $key => $val) {
    $txt .= '<option value="' . $key . '"';
    if ($key == $value)
      $txt .= ' checked';
    $txt .= '>' . $val . '</option>';
  }
  $txt .= '<select></div></div>';
  return $txt;
}

function form_text($name, $label='', $value = '') {
  return '<div class="form-group">
  <label class="control-label col-sm-3" for="' . $name . '">' . $label .'</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="' . $name . '" name="' . $name . '">' . $value
      . '</textarea>
    </div>
  </div>';
}

function form_submit($label="Enregistrer") {
  return '<button type="submit"  class="btn btn-primary">' . $label . '</button>';
}

function set_value_obj($label, $obj, $html_escape=TRUE) {
  return set_value($label, isset($obj->$label) ? $obj->$label : NULL, $html_escape);
}
