<div class="rubrique-description">

  </div>
<p>Les fossiles suivants ont été observés&nbsp;:</p>
<?php
  $struct = array(
    'Type de fossile et de fossilisation' => 'Q3.D.1',
    'Protistes fossiles' => 'Q3.D.2',
    'Invertébrés fossiles' => array(
      'Spongiaires' => 'Q3.D.3',
      'Cnidaires' => 'Q3.D.4',
      'Graptolites' => 'Q3.D.5',
      'Pistes d’ helminthoïdes' => 'Q3.D.6',
      'Mollusques' => array(
        'Lamellibranches (bivalves)' => 'Q3.D.7',
        'Gastéropodes' => 'Q3.D.8',
        'Céphalopodes' =>'Q3.D.9'
      ),
      'Echinodermes' => 'Q3.D.10',
      'Arthropodes' => 'Q3.D.11',
    ),
    'Végétaux fossiles' => array(
      'Algues et mousses' => 'Q3.D.12',
      'Ptéridophytes' => 'Q3.D.13',
      'Végétaux supérieurs' => 'Q3.D.14',
    ),
    'Vertébrés (et autres cordés)' => array(
      'Poissons' => 'Q3.D.15',
      'Amphibiens' => 'Q3.D.16',
      'Reptiles' => 'Q3.D.17',
      'Reptiles dinosauriens' => 'Q3.D.18',
      'Oiseaux' => 'Q3.D.19',
      'Mammifères' => 'Q3.D.20'
    ),
  );


  foreach ($struct as $k => $v) {
    echo structReponses($k, $v, 3, $caracteristiques, $complements);
  }

  echo commentaire($commentaire->commentaire);
?>
