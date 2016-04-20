<?php
/**
 * Export to PHP Array plugin for PHPMyAdmin
 * @version 0.2b
 */

//
// Database `odsa_S14`
//

// `odsa_S14`.`opendsa_userbutton`
$db_server = 'localhost';
$db_username = 'mfseddik';
$db_pass = 'MFS_a1s2d3f4g5h6';
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


if($book_id != "VT") {
  //Calculate the time spent in the module (Not VT)
  calculate_time_module();
}
else {
   
   //To distinguish VT from other universities
   //Code for VT (Relying on emails to distiguish users and books)
   //Calculate the time spent in the module (VT Special Case)
    calculate_time_module_VT();
}

//////////////////////////////////////////////////////////////////////////////////////
function calculate_time_module(){
  global $module_id;
  global $book_id; 
  global $start_date;
  global $end_date;
  global $db_server;
  global $db_username;
  global $db_pass;
  global $db_name;


  $query = "SELECT * 
                  FROM  `opendsa_userbutton` 
                  WHERE user_id IN 
                 (Select user_id from `opendsa_userbook` where grade = 1 AND book_id = $book_id) 
                  AND user_id NOT IN (Select user_id from `opendsa_userbook` where grade = 0)
                  AND book_id = $book_id
                  AND action_time BETWEEN '$start_date' AND '$end_date'
                  ORDER BY user_id, action_time, id";
  
  $data = array();
  if(@mysql_connect($db_server, $db_username, $db_pass)){
  	  if(@mysql_select_db($db_name)){
  	    if($query_run = mysql_query($query)){
  		  while($query_result = mysql_fetch_assoc($query_run)){
  			array_push($data, $query_result); 
  		  } 
  		}
  	}
  }
  
  $result = array();

  $first_record = null;

  for($i = 0; $i < count($data); $i++){
    if($data[$i]['module_id'] == $module_id && !isset($first_record)) {
      $first_record = $data[$i];     
    }
    //The first Interaction is captured
    if(isset($first_record)){
      if($data[$i]["user_id"] != $first_record["user_id"]){
        
        //Return back one record and add it
        array_push($result, $first_record, $data[$i - 1]);
  
        //Check if the new user's Interaction is with the module
        if($data[$i]["module_id"] == $module_id){
          $first_record = $data[$i];
        }
        else{
          $first_record = null;
        }
      }
      else if($data[$i]["user_id"] == $first_record["user_id"] && 
        $data[$i]["module_id"] != $first_record["module_id"]){
        //Save it (This is the first action outside the module)
        array_push($result, $first_record, $data[$i]);
        $first_record = null;
      }
      //If the last intearction is with the module
      if($i == count($data) - 1){
        array_push($result, $first_record, $data[$i]);    
      }
    }    
  }
  print_r($result);

  calculate_baseline($result);
}

 
//////////////////////////////////////////////////////////////////////////////////////////////

function calculate_baseline($result){
  //Calculating the baseline data points
  global $module_name;
  global $module_id;
  global $book_id; 
  global $start_date;
  global $end_date;
  global $db_server;
  global $db_username;
  global $db_pass;
  global $db_name;

  $maxDiff = 0;
  $seconds = array();
  for($i = 0;$i < count($result); $i += 2){
    $dateDiff = strtotime($result[$i+1]['action_time']) - strtotime($result[$i]['action_time']);	  
	  if($dateDiff > $maxDiff){
	    $maxDiff = $dateDiff;
	  }
	  
    if($i < count($result)-2){
      if($result[$i+2]['user_id'] != $result[$i]['user_id']){
        //Add maxDiff to the list
        array_push($seconds, array($result[$i]['user_id'], $maxDiff, $module_name[$module_id], $book_id));
        $maxDiff = 0;
      }
    }
    else{
      //Adding the last one
      array_push($seconds, array($result[$i]['user_id'], $maxDiff, $module_name[$module_id], $book_id));
	   }
  }

  print_r($seconds);
  
  //Fill in csv file
  $file = fopen("Output//Time IN $module_name[$module_id] In Book $book_id Analysis.csv",'w');
  //Adding headers
  fputcsv($file, array("User_ID", "Difference In Seconds" ,"Module Name","Book_ID"));
  foreach($seconds as $line){
    fputcsv($file, $line);
  }

  echo "Data Written to file Successfuly\n";
}
////////////////////////////////////////////////////////////////////////////////////////
function calculate_time_module_VT($AAV_ID){
  global $module_id;
  global $start_date;
  global $end_date;
  global $db_server;
  global $db_username;
  global $db_pass;
  global $db_name;


  $query = "SELECT * 
                  FROM  `opendsa_userbutton` A INNER JOIN `auth_user` B ON
                  A.user_id = B.id 
                  WHERE email IN 
                 (Select user_email from `CS3114VTRoster`) 
                  AND action_time BETWEEN '$start_date' AND '$end_date'
                  ORDER BY email, action_time, A.id";
  
  $data = array();
  if(@mysql_connect($db_server, $db_username, $db_pass)){
      if(@mysql_select_db($db_name)){
        if($query_run = mysql_query($query)){
        while($query_result = mysql_fetch_assoc($query_run)){
        array_push($data, $query_result); 
        } 
      }
    }
  }
  
  $result = array();

  $first_record = null;

  for($i = 0; $i < count($data); $i++){
    if($data[$i]['module_id'] == $module_id && !isset($first_record)) {
      $first_record = $data[$i];
      $start_session = true;      
    }
    //The first Interaction is captured
    if(isset($first_record)){
      if($data[$i]["email"] != $first_record["email"]){
        
        //Return back one record and add it
        array_push($result, $first_record, $data[$i - 1]);
  
        //Check if the new user's Interaction is with the module
        if($data[$i]["module_id"] == $module_id){
          $first_record = $data[$i];
        }
        else{
          $first_record = null;
        }
      }
      else if($data[$i]["email"] == $first_record["email"] && 
        $data[$i]["module_id"] != $first_record["module_id"]){
        //Return back one record
        array_push($result, $first_record, $data[$i]);
        $first_record = null;
      }
      //If the last intearction is an AAV interction
      if($i == count($data) - 1){
        array_push($result, $first_record, $data[$i]);    
      }
    }    
  }
  //print_r($result);

  calculate_baseline_VT($result);
}

 
//////////////////////////////////////////////////////////////////////////////////////////////

function calculate_baseline_VT($result){
  //Calculating the baseline data points
  global $module_name;
  global $module_id;
  global $book_id; 
  global $start_date;
  global $end_date;
  global $db_server;
  global $db_username;
  global $db_pass;
  global $db_name;

  $maxDiff = 0;
  $seconds = array();
  for($i = 0;$i < count($result); $i += 2){
    $dateDiff = strtotime($result[$i+1]['action_time']) - strtotime($result[$i]['action_time']);
    
    if($dateDiff > $maxDiff){
      $maxDiff = $dateDiff;
    }
    
    if($i < count($result)-2){
      if($result[$i+2]['email'] != $result[$i]['email']){
        //Add maxDiff to the list
        array_push($seconds, array($result[$i]['email'], $maxDiff, $module_name[$module_id]));
        $maxDiff = 0;
      }
    }
    else{
      //Adding the last one
      array_push($seconds, array($result[$i]['email'], $maxDiff, $module_name[$module_id]));
     }
  }

  print_r($seconds);
  
  //Fill in csv file
  $file = fopen("Output//Time IN $module_name[$module_id] In VTS16 Analysis.csv",'w');
  //Adding headers
  fputcsv($file, array("User_Email", "Difference In Seconds" ,"Module Name"));
  foreach($seconds as $line){
    fputcsv($file, $line);
  }

  echo "Data Written to file Successfuly\n";
}
////////////////////////////////////////////////////////////////////////////////////////