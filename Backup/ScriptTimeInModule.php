<?php

//
// Database `odsa_S14`
//

// `odsa_S14`.`opendsa_userbutton`
$db_server = 'localhost';
$db_username = $argv[5];
$db_pass = $argv[6];
$db_name = 'odsa_S14';
$module_id = $argv[1];
$book_id = $argv[2];
$start_date = $argv[3];
$end_date = $argv[4];

$module_name = array();

//Initialize module names
for($i=0;$i<280;$i++){
  array_push($module_name, " ");
}

$module_name[4] = "Insertionsort";
$module_name[3] = "Bubblesort";
$module_name[6] = "Selectionsort";
$module_name[65] = "CostExchangeSorting";
$module_name[96] = "Mergesort";
$module_name[69] = "Quicksort";
$module_name[70] = "Heapsort";
$module_name[99] = "Radixsort";
$module_name[77] = "Summations";
$module_name[78] = "Proofs";
$module_name[50] = "AnalPrelim";
$module_name[52] = "AnalCases";
$module_name[54] = "AnalAsymptotic";
$module_name[81] = "AnalLower";
$module_name[55] = "AnalProgram";
$module_name[82] = "AnalProblem";
$module_name[83] = "AnalMisunderstanding";
$module_name[101] = "SortingLowerBound";
$module_name[67] = "Heaps";
$module_name[250] = "RecurrenceIntro";




$query = "SELECT * 
                FROM  `opendsa_userbutton` 
                WHERE user_id IN 
               (Select user_id from `opendsa_userbook` where grade = 1 AND book_id = $book_id) 
                AND user_id NOT IN (Select user_id from `opendsa_userbook` where grade = 0)
                AND book_id = $book_id
                AND module_id = $module_id
                AND action_time BETWEEN '$start_date' AND '$end_date'
                ORDER BY user_id, action_time, id";
//}
$result = array();
if(@mysql_connect($db_server, $db_username, $db_pass)){
	  if(@mysql_select_db($db_name)){
	    if($query_run = mysql_query($query)){
		  while($query_result = mysql_fetch_assoc($query_run)){
			array_push($result, $query_result); 
		  } 
		}
	}
}

//print_r($result);


//Finding module sessions for each user and then pass this to calculate_time to get the max
$sessions = array();
$session_start = false;
$current_user = null;
$module_load = null;
for($i = 0;$i < count($result); $i++){
  if(($result[$i]['name'] == 'document-ready' || $result[$i]['name'] == 'window-focus')
   && $session_start == false){
    //Start of a session
    $current_user = $result[$i]["user_id"];
    $module_load = $result[$i];
    $session_start = true;
  }
  else if ($result[$i]['name'] == 'document-ready' && $session_start == true){
    //subsequent document-readys without matching window-unload
    if($result[$i]["user_id"] != $current_user){
      $current_user = $result[$i]["user_id"];
      $module_load = $result[$i];
    }
  }
  else if (($result[$i]['name'] == 'window-unload' || $result[$i]['name'] == 'window-blur') 
    && $session_start == true){
    //window-unload matching a document ready
    if($result[$i]['user_id'] == $current_user){
      array_push($sessions, $module_load);  
      array_push($sessions, $result[$i]);  
    }
    $session_start = false; 
  }
}

//print_r($sessions);

calculate_time($sessions, $module_id, $book_id);

 
//////////////////////////////////////////////////////////////////////////////////////////////

function calculate_time($result, $module_id, $book_id){
  global $module_name;
  global $time_out;

  $number_of_visits = 0;

  $average_time = 0;
  $average_visits = 0;

  //Calculating the baseline data points
  $sumDiff = 0;
  $seconds = array();
  for($i = 0;$i < count($result); $i += 2){
 
    $dateDiff = strtotime($result[$i+1]['action_time']) - strtotime($result[$i]['action_time']);
    $number_of_visits++;
	  $sumDiff += $dateDiff;
	  
    if($i < count($result)-2){
      if($result[$i+2]['user_id'] != $result[$i]['user_id']){
        //Add maxDiff to the list
        array_push($seconds, array($result[$i]['user_id'],$sumDiff, $number_of_visits, $module_id, $book_id));
        $sumDiff = 0;
        $number_of_visits = 0;
      }
    }
    else{
      //Adding the last one
      array_push($seconds, array($result[$i]['user_id'],$sumDiff, $number_of_visits, $module_id, $book_id));
    
	   }
    
  }
  //print_r($seconds);


  //Calculate the average time and average number of visits
    $sum_time = 0;
    $sum_visits = 0;
    for ($i = 0; $i < count($seconds); $i++) { 
      $sum_time += $seconds[$i][1];
      $sum_visits += $seconds[$i][2];
    } 

    $average_time = $sum_time / count($seconds);   
    $average_visits = $sum_visits / count($seconds);
  
  //Fill in csv file
  $file = fopen("Output//Module $module_name[$module_id] In Book $book_id Analysis.csv",'w');
  //Adding headers
  fputcsv($file, array("User_ID","Total Time" , "Number of Visits", "Module Name","Book_ID"));
  foreach($seconds as $line){
    fputcsv($file, $line);
  }

  //Add Averages
  fputcsv($file, array("Average", $average_time, $average_visits));

  echo "Data Written to file Successfuly\n";
}
