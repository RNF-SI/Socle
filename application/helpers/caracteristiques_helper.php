<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// permet de faire une liste de caractéristiques
function liste_caracteristiques($list, $question) {
  if (! isset($list[$question]))
    return '<i>&lt;Aucun élément&gt;</i>';
  $txt = '<ul>';
  foreach ($list[$question] as $car) {
    $txt .= '<li>' . $car->label . '</li>';
  }
  $txt .= '</ul>';
  return $txt;
}


// construit une série de checkbox avec le choix QCM
function qcm_caracteristiques($choices, $checked_items = NULL) {
  $txt = '<div class="qcm form-group">';
  foreach ($choices as $choice) {
    //$id  = $choice->rubrique . '-' . $choice->id;
    $li = '<div class="col-sm-4">
      <div class="checkbox"><label>
        <input type="checkbox" name="caracteristiques[]" value="'. $choice->id . '"';
    if (! is_null($checked_items) && in_array($choice->id, $checked_items))
      $li .= ' checked';
    $li .= '>' . $choice->label . '</label></div></div>';
    $txt .= $li;
  }
  $txt .= '</div>';
  return $txt;
}


// crée un champ de type texte pour compléter la liste_caracteristiques
function liste_complement($question, $value = '', $label = 'Autres éléments (nommer et décrire si besoin)') {
  $txt = '<div class="form-group">
    <div class="col-sm-3">'. $label .'</div>
    <div class="col-sm-9">
      <input type="hidden" name="complements_question[]" value="' . $question . '" />
      <textarea name="complements[]" class="form-control">' . $value . '</textarea>
    </div></div>';
  return $txt;
}

// affiche le complement correspondant à la question
function complement($complements, $question) {
  if (isset($complements[$question])) {
    return '<div class="row"><div class="col-sm-3">Autres éléments signalés&nbsp;:</div>
      <div class="col-sm-9">' . $complements[$question]->elements . '</div></div>';
  }
  return '';
}


// crée un champ de type texte pour commenter une rubrique
function field_commentaires($rubrique, $value = '',
  $label = 'Vos remarques et commentaires sur l’un ou l’autre point mentionné ci-dessus&nbsp;:') {
  $txt = '<div class="form-group">
    <div class="col-sm-3">' . $label . '</div>
    <div class="col-sm-9">
      <textarea name="commentaire" class="form-control">' . $value . '</textarea>
    </div></div>';
  return $txt;
}

// affiche le complement correspondant à la question
function commentaire($commentaire) {
  if (!empty($commentaire)) {
    return '<div class="row commentaire"><div class="col-sm-3">Commentaires sur la rubrique&nbsp;:</div>
      <div class="col-sm-9">' . $commentaire . '</div></div>';
  }
  return '';
}

// récursion pour niveaux hiérarchiques dans QCMS
function structReponses($key, $val, $level, $caracteristiques, $complements) {
  if (is_array($val)) {
    $text = '';
    foreach ($val as $k => $v) {
      $text .= structReponses($k, $v, $level+1, $caracteristiques, $complements);
    }
    return $text;
  } else {
    if (empty($caracteristiques[$val]) && empty($complements[$val]))
      return '';

    $txt = '<h' . $level . '>' . $key . '</h' . $level . '>';
    $txt .= liste_caracteristiques($caracteristiques, $val);
    $txt .= complement($complements, $val);
    return $txt;
  }
}


// récursion pour niveaux hiérarchiques dans QCMS
function structReponsesForm($key, $val, $level, $caracteristiques, $complements, $qcms) {
  if (is_array($val)) {
    $txt = '';
    foreach ($val as $k => $v) {
      $txt .= structReponsesForm($k, $v, $level+1, $caracteristiques, $complements, $qcms);
    }
    return $txt;
  } else {
    $txt = '<h' . $level . '>' . $key . '</h' . $level . '>';
    $choices = $qcms[$val];
    $cars = element($val, $caracteristiques);
    $txt .= qcm_caracteristiques($choices, $cars);

    $txt .= liste_complement($val, isset($complements[$val]) ? $complements[$val]->elements : '');
    return $txt;
  }

}
