<?php

$bot = 'LoremIpsum'; // Bot name
    

$test_sentences = array(1=>"Quisque posuere lacus in tincidunt laoreet",
                        2=>"Ut purus mauris gravida id accumsan eget mattis in ante",
                        3=>"Fusce et magna vel sapien convallis",
                        4=>"Fusce blandit rhoncus auctor",
                        5=>"Fusce quis faucibus diam",
                        6=>"Nunc vestibulum leo ut tempor viverra",
                        7=>"Praesent dictum lacus volutpat mauris semper et elementum"
                        );

////////////////////////////////
// !!! Don't Change Below !!! //
// !!!   ~~~~~~~~~~       !!! //
// !!!   HERE BE DRAGONS  !!! //
////////////////////////////////

$report_number = 0;

// Load Emotionary
$emotionary = json_decode(file_get_contents("$bot.json"), 1);

foreach($test_sentences as &$words){
        
    $string = strtolower($words);
    
    // Convert test string to arrays of words
    // requires pre-processing to remove punctuation
    $words = explode(' ', strtolower($words));
    
    $emotigrams = array();
    $total_emotional_content = array();
    $emotional_state = array();
    
    // Look up each word in the emotionary
    foreach($words as $pos=>&$word){
        if(in_array($word, array_keys($emotionary))){    
            foreach(array_keys($emotionary[$word]) as $key){
                
                // exclude count key
                if($key != 'count'){
                                    
                    // string contains _positive or _negative?                        
                    $pos = strpos($key, '_positive');
                    $neg = strpos($key, '_negative');
                    
                    // key contains '_positive'
                    if($pos !== false){
                        // remove '_positive' from the key
                        // this merges '_positive' & '_negative' keys
                        $new_key = substr($key, 0, $pos);
                    }
                    // key contains '_negative'
                    elseif($neg !== false){
                        // remove '_negative' from the key
                        // this merges '_positive' & '_negative' keys
                        $new_key = substr($key, 0, $neg);
                    }
                    else{
                        // No merge required so...
                        // Do nothing and let the key pass through unchanged
                        $new_key = $key;
                    }
                    
                    // increment total emotional content for sentence 
                    @$total_emotional_content[$new_key] += $emotionary[$word][$key];                

                    // collect emotigram word + emotional data time series
                    @$emotigrams[$word][$new_key] += $emotionary[$word][$key];
                    
                }// / exclude count key
            }
        }
        else{
            //echo "Unknown Word: $word" . PHP_EOL;
            @$Unknown_Words[] = $word;
        }

    } // / Look up each word in the emotionary
    echo PHP_EOL . PHP_EOL;
    

    // String
    echo "String: '$string'" . PHP_EOL . PHP_EOL;
    
    // Unknown Words
    echo "Unknown Words: [";
    $unknowns = '';
    foreach($Unknown_Words as $word){
        $unknowns .= "'$word', ";
    }
    $unknowns  = rtrim($unknowns , ", ");
    echo "$unknowns]" . PHP_EOL . PHP_EOL;
    $Unknown_Words = NULL;
    unset($Unknown_Words);
    
    // Overall emotion of the string
    echo "[String Analysis]" . PHP_EOL;
    // Analysis
    foreach($total_emotional_content as $key=>$value){
        echo "$key: " . $value . PHP_EOL;
    }
    echo PHP_EOL . PHP_EOL;    


    // Emotion of each word = emotigram at each time step
    echo "[Time Step Emotigram Analysis]" . PHP_EOL;
    foreach($emotigrams as $word=>$emotional_data){
        echo "Word[$word]" . PHP_EOL;
        foreach($emotional_data as $key=>$value){
            echo "$key: " . $value . PHP_EOL;
        }
        echo PHP_EOL . PHP_EOL;
    }
    echo PHP_EOL . PHP_EOL;
    
    
    // Build CSV Header and Compute emotional state change over time for CSV
    $csv = array();
    $row = 0;
    $col = 0;
    
    $firstword = array_key_first($emotigrams);
    $csv_header = array_keys($emotigrams[$firstword]);
    array_unshift($csv_header, 'word');
    
    foreach($emotigrams as $word=>$emotional_data){
        @$csv[$row][$col] = $word;
        $row++;
    }
    $row = 0;
    
    foreach($emotigrams as $word=>$emotional_data){
        $col = 1;
        foreach($emotional_data as $key=>$value){
            if($row == 1){
                @$csv[$row][$col] = $value; // first emotional data so start from 
                                            // the first value as the starting state
                                            // Alternatively create a new state and pass that here
                                            // i.e. $value + $start_state_value_for_$key
            }
            else{
                // The current emotional state is based on the previous emotional state + the new emotigram data
                @$csv[$row][$col] = $value + $csv[$row-1][$col];
            }
            $col++;
        }
        $row++;
    }
    
    
    $fp = fopen(__DIR__ . "/Reports/report_$report_number.csv", 'w'); // write CSV File
    $report_number++;
    fputcsv($fp, $csv_header);      // Put Header Row
    foreach ($csv as $emotional_time_series_data){
        fputcsv($fp, $emotional_time_series_data);  // Put emotional time series data
    }
    fclose($fp);// Close CSV File

}

