<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// produit un cadre contenant la description
function help_tooltip($choice) {
  if (is_array($choice))
    $choice = (object)$choice;
  if (empty($choice->description)) {
    return '';
  }
  $s = '<span class="description-tooltip">?<div class="description-tooltip-content">';
  $s .= '<p>'.$choice->description.'</p>';
  $s .= '</div></span>';
  return $s;
}

// permet de faire une liste de caractéristiques
function liste_caracteristiques($list, $question) {
  if (! isset($list[$question]))
    return '<i>&lt;Aucun élément&gt;</i>';
  $txt = '<ul>';
  $interets = [
    'interet_scientifique' => 'scientifique',
    'interet_esthetique' => 'esthétique',
    'interet_pedagogique' => 'pédagogique',
    'interet_historique' => 'historique/culturel'
  ];
  foreach ($list[$question] as $car) {
    $txt .= '<li>' . $car->label . help_tooltip($car);

    if ($car->remarquable == 't') {
      $txt .= '<span class="coche-remarquable active"> &starf;</span>';
      $item_interets = array();
      foreach ($interets as $key => $value) {
        if (isset($car->$key) && $car->$key == 't')
          $item_interets[] = $value;
      }
      if (count($item_interets) > 0 || $car->remarquable_info) {
        $txt .= ' (intérêts identifiés : ' . implode(', ', $item_interets);
        if ($car->remarquable_info)
          $txt .= ' ; ' . $car->remarquable_info;
       $txt .= ')';
      }
    }
    $txt .=  ($car->info_complement ? ' (' . $car->intitule_complement . '&nbsp;: ' . $car->info_complement . ')' : '')
      . '</li>';
  }
  $txt .= '</ul>';
  return $txt;
}

// construit une série de checkbox avec le choix QCM
function qcm_caracteristiques($choices) {
  $hidden_fields = [
    'remarquable' => 'b',
    'interet_scientifique' => 'b',
    'interet_pedagogique' => 'b',
    'interet_esthetique' => 'b',
    'interet_historique' => 'b',
    'remarquable_info' => 't'
  ];

  $txt = '<div class="qcm form-group">';
  foreach ($choices as $choice) {
    $li = '<div class="choix-container col-sm-4">
      <div class="checkbox"><label>
        <input type="checkbox" name="caracteristiques[]" value="'. $choice->id . '"';
    if ($choice->checked)
      $li .= ' checked';
    $li .= '>' . $choice->label . help_tooltip($choice) . '</label> <span class="remarquable-control';
    if ($choice->checked)
      $li .= ' checked';
    if ($choice->remarquable)
      $li .= ' remarquable';
    $li .= '"><a href="#" class="coche-remarquable" title="Signaler cet élément comme remarquable">&starf;</a>
      <a href="#" class="remarquable-edit" title="Critères de remarquabilité"><span class="glyphicon glyphicon-edit">
      </span></a></span></div>';
      
    foreach ($hidden_fields as $hf => $type) {
      $input = '<input type="hidden" id="'. $hf . '-' . $choice->id . '" name="' . $hf . '[]" ';
      if ($choice->remarquable && $choice->$hf) {
        $input .= 'value ="' . $choice->id . '"';
      } elseif ($type == 't') {
        //print_r($choice);
        $input .= 'value ="' . $choice->$hf . '"';
      }
      $input .= ' />';
      $li .=  $input;
    }
    //$li .= '<input type="hidden" name="info_remarquable[]" value="' . ($choice->remarquable == 't' ? $choice->id : '') . '" />';

    if (! is_null($choice->intitule_complement))
      $li .= '<div class="choice-complement"><label for="info_complement[]">' . $choice->intitule_complement
        . '</label><input type="text" name="info_complement[]" value="' . $choice->info_complement . '" />
        <input type="hidden" name="info_complement_id[]" value="' . $choice->id . '" /></div>';
    $li .= '</div>';
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
function structReponsesForm($key, $val, $level, $caracteristiques, $complements) {
  if (is_array($val)) {
    $txt = '';
    foreach ($val as $k => $v) {
      $txt .= structReponsesForm($k, $v, $level+1, $caracteristiques, $complements);
    }
    return $txt;
  } else {
    $txt = '<h' . $level . '>' . $key . '</h' . $level . '>';
    //$choices = $caracteristiques[$val];
    $choices = element($val, $caracteristiques);
    $txt .= qcm_caracteristiques($choices);

    $txt .= liste_complement($val, isset($complements[$val]) ? $complements[$val]->elements : '');
    return $txt;
  }

}
