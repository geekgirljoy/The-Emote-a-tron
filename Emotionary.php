<?php

$training_csv_file = 'LoremIpsumTrainingData.csv';

$bot = 'LoremIpsum';         // Bot name
$load_bot = true;           // Load Bot from json Yes/No (set to true if adding training)
$save_bot = false;            // Save Bot to json Yes/No
$csv_report = true;          // Generate a CSV report you can edit in Excel or OO/LO Calc?
$emotionary_report = true;   // Generate a TXT report with information about the emotionary
$polarization_threshold = 8; // Minimum positive emotional polarization threshold

////////////////////////////////
// !!! Don't Change Below !!! //
// !!!   ~~~~~~~~~~       !!! //
// !!!   HERE BE DRAGONS  !!! //
////////////////////////////////

if($csv_report == true){ // Yes
  $csv = array();
  $csv[] = 'Word,Anger,Anticipation,Disgust,Fear,Joy,Sadness,Surprise,Trust,Aggressiveness_Vengeance,Anxiety_Dread,Awe_Alarm,Contempt_Scorn,Curiosity,Cynicism,Delight_Doom,Despair,Disapproval_Disappointment,Dominance,Envy_Sullenness,Guilt_Excitement,Hope_Fatalism,Love_Friendliness,Morbidness_Derisiveness,Optimism_Courage,Outrage_Hate,Pessimism,Pride_Victory,Remorse_Misery,Sentimentality_Resignation,Shame_Prudishness,Submission_Modesty,Unbelief_Shock';
}

// Dictionary of emotigrams word+emotions sets
$emotionary = array();

// Load bot emotigrams from previously created json file
if($load_bot == true){
  if(file_exists("$bot.json")){
    $emotionary = json_decode(file_get_contents("$bot.json"), 1);
  }
  else{
    echo "Unable to load bot file - '$bot.json' does not exist." . PHP_EOL;
  }
}

/*
// Load Training Data from CSV
if(file_exists($training_csv_file) && ){
  // Load 'word string','emotion tags' from csv
  $dataset = array_map('str_getcsv', file($training_csv_file));
}
else{
  die("Unable to load traing CSV file - '$training_csv_file' does not exist." . PHP_EOL);
}

// Train on all training data sentences
foreach($dataset as &$datagram){

  // $datagram = array()
  //
  // Example:
  // array(3) {
  //  [0]=> string(54) "Lorem ipsum dolor sit amet consectetur adipiscing elit"
  //  [1]=> string(8) "00000010"
  //  [2]=> string(0) ""
  // }
  
  // Split the current sentence into words using spaces
  // requres dataset preprecessing to remove punctuation
  $words = explode(' ', $datagram[0]);

  // Gather emotional data for the sentence.
  // maintaining correct order is very important
  // so I arranged it in assending alphabetical order
  // AADFJSST
  $Anger = $datagram[1][0];
  $Anticipation = $datagram[1][1];
  $Disgust = $datagram[1][2];
  $Fear = $datagram[1][3];
  $Joy = $datagram[1][4];
  $Sadness = $datagram[1][5];
  $Surprise = $datagram[1][6];
  $Trust = $datagram[1][7];
    
  // Learn/Update the emotigram word+emotions sets
  foreach($words as &$word){
    $word = strtolower($word); 
  
    // Count is how many times this word 
    // has been seen during training
    @$emotionary[$word]['count'] += 1;
  
    // _positive : if 1, return 1 else 0
    // _negative : if 1, return 0 else -1
  
    // Increment Anger
    @$emotionary[$word]['anger_positive'] += ($Anger == 1 ? 1 : 0);
    @$emotionary[$word]['anger_negative'] += ($Anger == 1 ? 0 : -1);
  
    // Increment Anticipation
    @$emotionary[$word]['anticipation_positive'] += ($Anticipation == 1 ? 1 : 0);
    @$emotionary[$word]['anticipation_negative'] += ($Anticipation == 1 ? 0 : -1);
  
    // Increment Disgust
    @$emotionary[$word]['disgust_positive'] += ($Disgust == 1 ? 1 : 0);
    @$emotionary[$word]['disgust_negative'] += ($Disgust == 1 ? 0 : -1);
  
    // Increment Fear
    @$emotionary[$word]['fear_positive'] += ($Fear == 1 ? 1 : 0);
    @$emotionary[$word]['fear_negative'] += ($Fear == 1 ? 0 : -1);
  
    // Increment Joy
    @$emotionary[$word]['joy_positive'] += ($Joy == 1 ? 1 : 0);
    @$emotionary[$word]['joy_negative'] += ($Joy == 1 ? 0 : -1);
    
    // Increment Sadness
    @$emotionary[$word]['sadness_positive'] += ($Sadness == 1 ? 1 : 0);
    @$emotionary[$word]['sadness_negative'] += ($Sadness == 1 ? 0 : -1);
  
    // Increment Surprise
    @$emotionary[$word]['surprise_positive'] += ($Surprise == 1 ? 1 : 0);
    @$emotionary[$word]['surprise_negative'] += ($Surprise == 1 ? 0 : -1);
  
    // Increment Trust
    @$emotionary[$word]['trust_positive'] += ($Trust == 1 ? 1 : 0);
    @$emotionary[$word]['trust_negative'] += ($Trust == 1 ? 0 : -1);  
  }
}

*/

// Now that the primary emotion counts are stable 
// find emotigram polarization and compute 
// emotional dyads from primary emotions 
foreach($emotionary as $emotigram_word_key=>$emotigram_emotional_data){

  /////////////////////////////////////////////
  // Primary Emotions /////////////////////////
  /////////////////////////////////////////////
  
  // Anger
  $Anger = intval($emotigram_emotional_data['anger_positive']) + intval(['anger_negative']);
  if($emotionary_report == true &&
     $Anger >= $polarization_threshold){ // Word is anger polarized
    @$Angery_Words[$emotigram_word_key] = $Anger;
  }
  
  // Anticipation
  $Anticipation = intval($emotigram_emotional_data['anticipation_positive']) + intval(['anticipation_negative']);
  if($emotionary_report == true &&
     $Anticipation >= $polarization_threshold){// Word is anticipation polarized
    @$Anticipatatory_Words[$emotigram_word_key] = $Anticipation;
  }
  
  // Disgust
  $Disgust = intval($emotigram_emotional_data['disgust_positive']) + intval(['disgust_negative']);
  if($emotionary_report == true &&
     $Disgust >= $polarization_threshold){// Word is disgust polarized
    @$Disgusting_Words[$emotigram_word_key] = $Disgust;
  }
  
  // Fear
  $Fear = intval($emotigram_emotional_data['fear_positive']) + intval(['fear_negative']);
  if($emotionary_report == true &&
     $Fear >= $polarization_threshold){// Word is fear polarized
    @$Fearful_Words[$emotigram_word_key] = $Fear;
  }

  // Joy
  $Joy = intval($emotigram_emotional_data['joy_positive']) + intval(['joy_negative']);
  if($emotionary_report == true &&
     $Joy >= $polarization_threshold){// Word is joy polarized
    @$Joyful_Words[$emotigram_word_key] = $Joy;
  }

  // Sadness
  $Sadness = intval($emotigram_emotional_data['sadness_positive']) + intval(['sadness_negative']);
  if($emotionary_report == true &&
     $Sadness >= $polarization_threshold){// Word is saddness polarized
    @$Saddening_Words[$emotigram_word_key] = $Sadness;
  }

  // Surprise
  $Surprise = intval($emotigram_emotional_data['surprise_positive']) + intval(['surprise_negative']);
  if($emotionary_report == true &&
     $Surprise >= $polarization_threshold){// Word is surprise polarized
    @$Surprising_Words[$emotigram_word_key] = $Surprise;
  }

  // Trust
  $Trust = intval($emotigram_emotional_data['trust_positive']) + intval(['trust_negative']);
  if($emotionary_report == true &&
     $Trust >= $polarization_threshold){// Word is trust polarized
    @$Trusting_Words[$emotigram_word_key] = $Trust;
  }


  /////////////////////////////////////////////
  // Emotional Diads //////////////////////////
  /////////////////////////////////////////////
  
  // Compute Aggressiveness_Vengeance word score
  $Aggressiveness_Vengeance = $Anger + $Anticipation;
  @$emotionary[$emotigram_word_key]['aggressiveness_vengeance'] = $Aggressiveness_Vengeance;
  if($emotionary_report == true &&
     $Aggressiveness_Vengeance >= $polarization_threshold){// Word is aggressiveness_vengeance polarized
    @$Aggressiveness_Vengeance_Words[$emotigram_word_key] = $Aggressiveness_Vengeance;
  }

  // Compute Anxiety_Dread word score
  $Anxiety_Dread = $Anticipation + $Fear;
  @$emotionary[$emotigram_word_key]['anxiety_dread'] = $Anxiety_Dread;
  if($emotionary_report == true &&
     $Anxiety_Dread >= $polarization_threshold){// Word is anxiety_dread polarized
    @$Anxiety_Dread_Words[$emotigram_word_key] = $Anxiety_Dread;
  }
  
  // Compute Awe_Alarm word score
  $Awe_Alarm = $Fear + $Surprise;
  @$emotionary[$emotigram_word_key]['awe_alarm'] = $Awe_Alarm;
  if($emotionary_report == true &&
     $Awe_Alarm >= $polarization_threshold){// Word is awe_alarm polarized
    @$Awe_Alarm_Words[$emotigram_word_key] = $Awe_Alarm;
  }
  
  // Compute Contempt_Scorn word score
  $Contempt_Scorn = $Disgust + $Anger;
  @$emotionary[$emotigram_word_key]['contempt_scorn'] = $Contempt_Scorn;
  if($emotionary_report == true &&
     $Contempt_Scorn >= $polarization_threshold){// Word is contempt_scorn polarized
    @$Contempt_Scorn_Words[$emotigram_word_key] = $Contempt_Scorn;
  }
  
  // Compute Curiosity word score
  $Curiosity = $Trust + $Surprise;
  @$emotionary[$emotigram_word_key]['curiosity'] = $Curiosity;
  if($emotionary_report == true &&
     $Curiosity >= $polarization_threshold){// Word is curiosity polarized
    @$Curiosity_Words[$emotigram_word_key] = $Curiosity;
  }

  // Compute Cynicism word score
  $Cynicism = $Disgust + $Anticipation;
  @$emotionary[$emotigram_word_key]['cynicism'] = $Cynicism;
  if($emotionary_report == true &&
     $Cynicism >= $polarization_threshold){// Word is cynicism polarized
    @$Cynicism_Words[$emotigram_word_key] = $Cynicism;
  }

  // Compute Delight_Doom word score
  $Delight_Doom = $Joy + $Surprise;
  @$emotionary[$emotigram_word_key]['delight_doom'] = $Delight_Doom;
  if($emotionary_report == true &&
     $Delight_Doom >= $polarization_threshold){// Word is delight_doom polarized
    @$Despair_Words[$emotigram_word_key] = $Despair;
  }

  // Compute Despair word score
  $Despair = $Fear + $Sadness;
  @$emotionary[$emotigram_word_key]['despair'] = $Despair;
  if($emotionary_report == true &&
     $Despair >= $polarization_threshold){// Word is despair polarized
    @$Despair_Words[$emotigram_word_key] = $Despair;
  }

  // Compute Disapproval_Disappointment word score
  $Disapproval_Disappointment = $Surprise + $Sadness;
  @$emotionary[$emotigram_word_key]['disapproval_disappointment'] = $Disapproval_Disappointment;
  if($emotionary_report == true &&
     $Disapproval_Disappointment >= $polarization_threshold){// Word is disapproval_disappointment polarized
    @$Disapproval_Disappointment_Words[$emotigram_word_key] = $Disapproval_Disappointment;
  }

  // Compute Dominance word score
  $Dominance = $Anger + $Trust;
  @$emotionary[$emotigram_word_key]['dominance'] = $Dominance;
  if($emotionary_report == true &&
     $Dominance >= $polarization_threshold){// Word is dominance polarized
    @$Dominance_Words[$emotigram_word_key] = $Dominance;
  }
  
  // Compute Envy_Sullenness word score
  $Envy_Sullenness = $Sadness + $Anger;
  @$emotionary[$emotigram_word_key]['envy_sullenness'] = $Envy_Sullenness;
  if($emotionary_report == true &&
     $Envy_Sullenness >= $polarization_threshold){// Word is envy_sullenness polarized
    @$Dominance_Words[$emotigram_word_key] = $Envy_Sullenness;
  }

  // Compute Guilt_Excitement word score
  $Guilt_Excitement = $Joy + $Fear;
  @$emotionary[$emotigram_word_key]['guilt_excitement'] = $Guilt_Excitement;
  if($emotionary_report == true &&
     $Guilt_Excitement >= $polarization_threshold){// Word is guilt_excitement polarized
    @$Guilt_Excitement_Words[$emotigram_word_key] = $Guilt_Excitement;
  }

  // Compute Hope_Fatalism word score
  $Hope_Fatalism = $Anticipation + $Trust;
  @$emotionary[$emotigram_word_key]['hope_fatalism'] = $Hope_Fatalism;
  if($emotionary_report == true &&
     $Hope_Fatalism >= $polarization_threshold){// Word is hope_fatalism polarized
    @$Hope_Fatalism_Words[$emotigram_word_key] = $Hope_Fatalism;
  }

  // Compute Love_Friendliness word score
  $Love_Friendliness = $Joy + $Trust;
  @$emotionary[$emotigram_word_key]['love_friendliness'] = $Love_Friendliness;
  if($emotionary_report == true &&
     $Love_Friendliness >= $polarization_threshold){// Word is love_friendliness polarized
    @$Love_Friendliness_Words[$emotigram_word_key] = $Love_Friendliness;
  }

  // Compute Morbidness_Derisiveness word score
  $Morbidness_Derisiveness = $Disgust + $Joy;
  @$emotionary[$emotigram_word_key]['morbidness_derisiveness'] = $Morbidness_Derisiveness;
  if($emotionary_report == true &&
     $Morbidness_Derisiveness >= $polarization_threshold){// Word is morbidness_derisiveness polarized
    @$Morbidness_Derisiveness_Words[$emotigram_word_key] = $Morbidness_Derisiveness;
  }

  // Compute Optimism_Courage word score
  $Optimism_Courage = $Anticipation + $Joy;
  @$emotionary[$emotigram_word_key]['optimism_courage'] = $Optimism_Courage;
  if($emotionary_report == true &&
     $Optimism_Courage >= $polarization_threshold){// Word is optimism_courage polarized
    @$Optimism_Courage_Words[$emotigram_word_key] = $Optimism_Courage;
  }
  
  // Compute Outrage_Hate word score
  $Outrage_Hate = $Surprise + $Anger;
  @$emotionary[$emotigram_word_key]['outrage_hate'] = $Outrage_Hate;
  if($emotionary_report == true &&
     $Outrage_Hate >= $polarization_threshold){// Word is outrage_hate polarized
    @$Outrage_Hate_Words[$emotigram_word_key] = $Outrage_Hate;
  }

  // Compute Pessimism word score
  $Pessimism = $Sadness + $Anticipation;
  @$emotionary[$emotigram_word_key]['pessimism'] = $Pessimism;
  if($emotionary_report == true &&
     $Pessimism >= $polarization_threshold){// Word is pessimism polarized
    @$Pessimism_Words[$emotigram_word_key] = $Pessimism;
  }
  
  // Compute Pride_Victory word score
  $Pride_Victory = $Anger + $Joy;
  @$emotionary[$emotigram_word_key]['pride_victory'] = $Pride_Victory;
  if($emotionary_report == true &&
     $Pride_Victory >= $polarization_threshold){// Word is pride_victory polarized
    @$Pride_Victory_Words[$emotigram_word_key] = $Pride_Victory;
  }

  // Compute Remorse_Misery word score
  $Remorse_Misery = $Sadness + $Disgust;
  @$emotionary[$emotigram_word_key]['remorse_misery'] = $Remorse_Misery;
  if($emotionary_report == true &&
     $Remorse_Misery >= $polarization_threshold){// Word is remorse_misery polarized
    @$Remorse_Misery_Words[$emotigram_word_key] = $Remorse_Misery;
  }

  // Compute Sentimentality_Resignation word score
  $Sentimentality_Resignation = $Trust + $Sadness;
  @$emotionary[$emotigram_word_key]['sentimentality_resignation'] = $Sentimentality_Resignation;
  if($emotionary_report == true &&
     $Sentimentality_Resignation >= $polarization_threshold){// Word is sentimentality_resignation polarized
    @$Sentimentality_Resignation_Words[$emotigram_word_key] = $Sentimentality_Resignation;
  }
  
  // Compute Shame_Prudishness word score
  $Shame_Prudishness = $Fear + $Disgust;
  @$emotionary[$emotigram_word_key]['shame_prudishness'] = $Shame_Prudishness;
  if($emotionary_report == true &&
     $Shame_Prudishness >= $polarization_threshold){// Word is shame_prudishness polarized
    @$Shame_Prudishness_Words[$emotigram_word_key] = $Shame_Prudishness;
  }

  // Compute Submission_Modesty word score
  $Submission_Modesty = $Trust + $Fear;
  @$emotionary[$emotigram_word_key]['submission_modesty'] = $Submission_Modesty;
  if($emotionary_report == true &&
     $Submission_Modesty >= $polarization_threshold){// Word is submission_modesty polarized
    @$Submission_Modesty_Words[$emotigram_word_key] = $Submission_Modesty;
  }
   
  // Compute Unbelief_Shock word score
  $Unbelief_Shock = $Surprise + $Disgust;
  @$emotionary[$emotigram_word_key]['unbelief_shock'] = $Unbelief_Shock;
  if($emotionary_report == true &&
     $Unbelief_Shock >= $polarization_threshold){// Word is unbelief_shock polarized
    @$Unbelief_Shock_Words[$emotigram_word_key] = $Unbelief_Shock;
  }

  // Generate CSV report?
  if($csv_report == true){ // Yes
    // Add data to CSV report
    $csv[] = "$emotigram_word_key,$Anger,$Anticipation,$Disgust,$Fear,$Joy,$Sadness,$Surprise,$Trust,$Aggressiveness_Vengeance,$Anxiety_Dread,$Awe_Alarm,$Contempt_Scorn,$Curiosity,$Cynicism,$Delight_Doom,$Despair,$Disapproval_Disappointment,$Dominance,$Envy_Sullenness,$Guilt_Excitement,$Hope_Fatalism,$Love_Friendliness,$Morbidness_Derisiveness,$Optimism_Courage,$Outrage_Hate,$Pessimism,$Pride_Victory,$Remorse_Misery,$Sentimentality_Resignation,$Shame_Prudishness,$Submission_Modesty,$Unbelief_Shock";
  }
}
  
////////////////////////////////////////////
/// Save Bot ///////////////////////////////
////////////////////////////////////////////
// Save bot as .json file?
if($save_bot == true){ // Yes
  // Save bot
  if(file_put_contents("$bot.json", json_encode($emotionary, 1)))
  {// Something went right
    echo "Bot $bot.json saved." . PHP_EOL;
  }
  else{// Something went wrong
    echo "Bot $bot.json NOT saved." . PHP_EOL;
  }
}



////////////////////////////////////////////
/// Export CSV Report //////////////////////
////////////////////////////////////////////
// Generate CSV report?
if($csv_report == true){ // Yes
  file_put_contents("$bot.Report.csv", implode(PHP_EOL, $csv));
}


////////////////////////////////////////////
/// Emotionary Analysis Report /////////////
////////////////////////////////////////////
//Generate Emotionary Analysis Report?
if($emotionary_report == true){ // Yes
  
  ////////////////////////////////////////////
  /// Build Emotionary Analysis Report ///////
  ////////////////////////////////////////////
  
  $report = '';
  
  $report .= "[Bot '$bot' Emotionary Analysis Report]" . PHP_EOL . PHP_EOL;
  
  $report .= "Primary Emotions:" . PHP_EOL . PHP_EOL;
  
  	if(!empty($Angery_Words)){
	  $report .= PHP_EOL . '[Angery]'. PHP_EOL;
	  $report .= @count($Angery_Words) . ' Angery words.' . PHP_EOL;
	  $report .= 'Most Angery Word: \'' . @array_key_first($Angery_Words) . '\'' . PHP_EOL;
	}
	
	if(!empty($Anticipatatory_Words)){
		$report .= PHP_EOL . '[Anticipatatory Words]'. PHP_EOL;
		$report .= @count($Anticipatatory_Words) . ' Anticipatatory words.' . PHP_EOL;
		$report .= 'Most Anticipatatory Word: \'' . @array_key_first($Anticipatatory_Words) . '\'' . PHP_EOL;
	}
  
    if(!empty($Disgusting_Words)){
	  $report .= PHP_EOL . '[Disgusting Words]'. PHP_EOL;
	  $report .= @count($Disgusting_Words) . ' Disgusting words.' . PHP_EOL;
	  $report .= 'Most Disgusting Word: \'' . @array_key_first($Disgusting_Words) . '\'' . PHP_EOL;
	}
  
  if(!empty($Fearful_Words)){
	  $report .= PHP_EOL . '[Fearful Words]'. PHP_EOL;
	  $report .= @count($Fearful_Words) . ' Fearful words.' . PHP_EOL;
	  $report .= 'Most Fearful Word: \'' . @array_key_first($Fearful_Words) . '\'' . PHP_EOL;
  }
  
    if(!empty($Joyful_Words)){
	  $report .= PHP_EOL . '[Joyful Words]'. PHP_EOL;
	  $report .= @count($Joyful_Words) . ' Joyful words.' . PHP_EOL;
	  $report .= 'Most Joyful Word: \'' . @array_key_first($Joyful_Words) . '\'' . PHP_EOL;
	}
  
	if(!empty($Saddening_Words)){
	  $report .= PHP_EOL . '[Saddening Words]'. PHP_EOL;
	  $report .= @count($Saddening_Words) . ' Saddening words.' . PHP_EOL;
	  $report .= 'Most Saddening Word: \'' . @array_key_first($Saddening_Words) . '\'' . PHP_EOL;
	}
  
    if(!empty($Saddening_Words)){
	  $report .= PHP_EOL . '[Surprising Words]'. PHP_EOL;
	  $report .= @count($Surprising_Words) . ' Surprising words.' . PHP_EOL;
	  $report .= 'Most Surprising Word: \'' . @array_key_first($Surprising_Words) . '\'' . PHP_EOL;
	}
  
    if(!empty($Trusting_Words)){
	  $report .= PHP_EOL . '[Trusting Words]'. PHP_EOL;
	  $report .= @count($Trusting_Words) . ' Trusting words.' . PHP_EOL;
	  $report .= 'Most Trusting Word: \'' . @array_key_first($Trusting_Words) . '\'' . PHP_EOL;
	}
  $report .= PHP_EOL . PHP_EOL;
  
  
  $report .= "Emotional Diads:" . PHP_EOL . PHP_EOL;
  
	if(!empty($Aggressiveness_Vengeance_Words)){
		$report .= PHP_EOL . '[Aggressiveness_Vengeance Words]'. PHP_EOL;
		$report .= @count($Aggressiveness_Vengeance_Words) . ' Aggressiveness_Vengeance words.' . PHP_EOL;
		$report .= 'Most Aggressiveness_Vengeance Word: \'' . @array_key_first($Aggressiveness_Vengeance_Words) . '\'' . PHP_EOL;
	}
	
	if(!empty($Anxiety_Dread_Words)){
		$report .= PHP_EOL . '[Anxiety_Dread Words]'. PHP_EOL;
		$report .= @count($Anxiety_Dread_Words) . ' Anxiety_Dread words.' . PHP_EOL;
		$report .= 'Most Anxiety_Dread Word: \'' . @array_key_first($Anxiety_Dread_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Awe_Alarm_Words)){
		$report .= PHP_EOL . '[Awe_Alarm Words]'. PHP_EOL;
		$report .= @count($Awe_Alarm_Words) . ' Awe_Alarm words.' . PHP_EOL;
		$report .= 'Most Awe_Alarm Word: \'' . @array_key_first($Awe_Alarm_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Contempt_Scorn_Words)){
		$report .= PHP_EOL . '[Contempt_Scorn Words]'. PHP_EOL;
		$report .= @count($Contempt_Scorn_Words) . ' Contempt_Scorn words.' . PHP_EOL;
		$report .= 'Most Contempt_Scorn Word: \'' . @array_key_first($Contempt_Scorn_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Curiosity_Words)){
		$report .= PHP_EOL . '[Curiosity Words]'. PHP_EOL;
		$report .= @count($Curiosity_Words) . ' Curiosity words.' . PHP_EOL;
		$report .= 'Most Curiosity Word: \'' . @array_key_first($Curiosity_Words) . '\'' . PHP_EOL;
	}
	
	if(!empty($Cynicism_Words)){
		$report .= PHP_EOL . '[Cynicism Words]'. PHP_EOL;
		$report .= @count($Cynicism_Words) . ' Cynicism words.' . PHP_EOL;
		$report .= 'Most Cynicism Word: \'' . @array_key_first($Cynicism_Words) . '\'' . PHP_EOL;
	}
	
	if(!empty($Delight_Doom_Words)){
		$report .= PHP_EOL . '[Delight_Doom Words]'. PHP_EOL;
		$report .= @count($Delight_Doom_Words) . ' Delight_Doom words.' . PHP_EOL;
		$report .= 'Most Delight_Doom Word: \'' . @array_key_first($Delight_Doom_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Despair_Words)){
		$report .= PHP_EOL . '[Despair Words]'. PHP_EOL;
		$report .= @count($Despair_Words) . ' Despair words.' . PHP_EOL;
		$report .= 'Most Despair Word: \'' . @array_key_first($Despair_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Disapproval_Disappointment_Words)){
		$report .= PHP_EOL . '[Disapproval_Disappointment Words]'. PHP_EOL;
		$report .= @count($Disapproval_Disappointment_Words) . ' Disapproval_Disappointment words.' . PHP_EOL;
		$report .= 'Most Disapproval_Disappointment Word: \'' . @array_key_first($Disapproval_Disappointment_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Dominance_Words)){
		$report .= PHP_EOL . '[Dominance Words]'. PHP_EOL;
		$report .= @count($Dominance_Words) . ' Dominance words.' . PHP_EOL;
		$report .= 'Most Dominance Word: \'' . @array_key_first($Dominance_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Envy_Sullenness_Words)){
		$report .= PHP_EOL . '[Envy_Sullenness Words]'. PHP_EOL;
		$report .= @count($Envy_Sullenness_Words) . ' Dominance words.' . PHP_EOL;
		$report .= 'Most Envy_Sullenness Word: \'' . @array_key_first($Envy_Sullenness_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Guilt_Excitement_Words)){
		$report .= PHP_EOL . '[Guilt_Excitement Words]'. PHP_EOL;
		$report .= @count($Guilt_Excitement_Words) . ' Guilt_Excitement words.' . PHP_EOL;
		$report .= 'Most Guilt_Excitement Word: \'' . @array_key_first($Guilt_Excitement_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Hope_Fatalism_Words)){
		$report .= PHP_EOL . '[Hope_Fatalism Words]'. PHP_EOL;
		$report .= @count($Hope_Fatalism_Words) . ' Hope_Fatalism words.' . PHP_EOL;
		$report .= 'Most Hope_Fatalism Word: \'' . @array_key_first($Hope_Fatalism_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Love_Friendliness_Words)){
		$report .= PHP_EOL . '[Love_Friendliness Words]'. PHP_EOL;
		$report .= @count($Love_Friendliness_Words) . ' Love_Friendliness words.' . PHP_EOL;
		$report .= 'Most Love_Friendliness Word: \'' . @array_key_first($Love_Friendliness_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Morbidness_Derisiveness_Words)){
		$report .= PHP_EOL . '[Morbidness_Derisiveness Words]'. PHP_EOL;
		$report .= @count($Morbidness_Derisiveness_Words) . ' Morbidness_Derisiveness words.' . PHP_EOL;
		$report .= 'Most Morbidness_Derisiveness Word: \'' . @array_key_first($Morbidness_Derisiveness_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Optimism_Courage_Words)){
		$report .= PHP_EOL . '[Optimism_Courage Words]'. PHP_EOL;
		$report .= @count($Optimism_Courage_Words) . ' Optimism_Courage words.' . PHP_EOL;
		$report .= 'Most Optimism_Courage Word: \'' . @array_key_first($Optimism_Courage_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Pessimism_Words)){
		$report .= PHP_EOL . '[Pessimism Words]'. PHP_EOL;
		$report .= @count($Pessimism_Words) . ' Pessimism words.' . PHP_EOL;
		$report .= 'Most Pessimism Word: \'' . @array_key_first($Pessimism_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Pride_Victory_Words)){
		$report .= PHP_EOL . '[Pride_Victory Words]'. PHP_EOL;
		$report .= @count($Pride_Victory_Words) . ' Pride_Victory words.' . PHP_EOL;
		$report .= 'Most Pride_Victory Word: \'' . @array_key_first($Pride_Victory_Words) . '\'' . PHP_EOL;
	}

	if(!empty($Remorse_Misery_Words)){
		$report .= PHP_EOL . '[Remorse_Misery Words]'. PHP_EOL;
		$report .= @count($Remorse_Misery_Words) . ' Remorse_Misery words.' . PHP_EOL;
		$report .= 'Most Remorse_Misery Word: \'' . @array_key_first($Remorse_Misery_Words) . '\'' . PHP_EOL;
	}
	
	if(!empty($Sentimentality_Resignation_Words)){
		$report .= PHP_EOL . '[Sentimentality_Resignation Words]'. PHP_EOL;
		$report .= @count($Sentimentality_Resignation_Words) . ' Sentimentality_Resignation words.' . PHP_EOL;
		$report .= 'Most Sentimentality_Resignation Word: \'' . @array_key_first($Sentimentality_Resignation_Words) . '\'' . PHP_EOL;
	}

    if(!empty($Shame_Prudishness_Words)){
		$report .= PHP_EOL . '[Shame_Prudishness Words]'. PHP_EOL;
		$report .= @count($Shame_Prudishness_Words) . ' Shame_Prudishness words.' . PHP_EOL;
		$report .= 'Most Shame_Prudishness Word: \'' . @array_key_first($Shame_Prudishness_Words) . '\'' . PHP_EOL;
	}

    if(!empty($Submission_Modesty_Words)){
		$report .= PHP_EOL . '[Submission_Modesty Words]'. PHP_EOL;
		$report .= @count($Submission_Modesty_Words) . ' Submission_Modesty words.' . PHP_EOL;
		$report .= 'Most Submission_Modesty Word: \'' . @array_key_first($Submission_Modesty_Words) . '\'' . PHP_EOL;
    }

    if(!empty($Unbelief_Shock_Words)){
		$report .= PHP_EOL . '[Unbelief_Shock Words]'. PHP_EOL;
		$report .= @count($Unbelief_Shock_Words) . ' Unbelief_Shock words.' . PHP_EOL;
		$report .= 'Most Unbelief_Shock Word: \'' . @array_key_first($Unbelief_Shock_Words) . '\'' . PHP_EOL;
    }
  
  echo $report;
  
  file_put_contents("$bot.Report.txt", $report);
}
