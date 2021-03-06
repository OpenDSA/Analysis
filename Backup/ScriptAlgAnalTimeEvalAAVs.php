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
$module_name[96] = "Mergesort";
$module_name[69] = "Quicksort";
$module_name[70] = "Heapsort";
$module_name[99] = "Radixsort";
$module_name[77] = "Summations";
$module_name[65] = "CostExchangeSort";
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


$module_AAVs = array("77"=>"589-590",
                     "250"=>"539-675",
                     "78"=>"540",
                     "67"=>"275",
                     "50"=>"543",
                     "52"=>"702-703",
                     "54"=>"545",
                     "81"=>"546",
                     "55"=>"704",
                     "82"=>"594",
                     "83"=>"595",
                     "101"=>"446",
                     "4"=>"439-438-440",
                     "3"=>"435",
                     "6"=>"447",
                     "65"=>"689",
                     "96"=>"445",
                     "69"=>"444-443-441-442",
                     "70"=>"436",
                     "99"=>"437");

$total_time = array(); //Holds the total time spent by each student in all AAVs 
                       // of a specific module.

//print_r($module_AAVs);

$AAVs = explode("-", $module_AAVs["$module_id"]);

// if($book_id != "VT") {
//   //Calculate the time spent in each AAV within the module (NOT VT)
//   for ($i = 0; $i < count($AAVs); $i++){
//     calculate_time_AAV((int)$AAVs[$i]);
//   }  
// }
// else {
   
//    //To distinguish VT from other universities
//    //Code for VT (Relying on emails to distiguish users and books)
//    //Calculate the time spent in each AAV within the module (VT Special Case)
//    for ($i = 0; $i < count($AAVs); $i++){
//     calculate_time_AAV_VT((int)$AAVs[$i]);
//    }    
// }


for ($i = 0; $i < count($AAVs); $i++){
  calculate_time_AAV((int)$AAVs[$i]);
}

//Creating a file holding the total amount of time spent
dumpTotal($total_time);

//////////////////////////////////////////////////////////////////////////////////////
// function calculate_time_AAV($AAV_ID){
//   global $module_id;
//   global $book_id; 
//   global $start_date;
//   global $end_date;
//   global $db_server;
//   global $db_username;
//   global $db_pass;
//   global $db_name;


//   $query = "SELECT * 
//                   FROM  `opendsa_userbutton` 
//                   WHERE user_id IN 
//                  (Select user_id from `opendsa_userbook` where grade = 1 AND book_id = $book_id) 
//                   AND user_id NOT IN (Select user_id from `opendsa_userbook` where grade = 0)
//                   AND book_id = $book_id
//                   AND module_id =$module_id
//                   AND action_time BETWEEN '$start_date' AND '$end_date'
//                   ORDER BY user_id, action_time, id";
  
//   $data = array();
//   if(@mysql_connect($db_server, $db_username, $db_pass)){
//   	  if(@mysql_select_db($db_name)){
//   	    if($query_run = mysql_query($query)){
//   		  while($query_result = mysql_fetch_assoc($query_run)){
//   			array_push($data, $query_result); 
//   		  } 
//   		}
//   	}
//   }
  
//   $result = array();

//   $first_record = null;

//   for($i = 0; $i < count($data); $i++){
//     if($data[$i]['exercise_id'] == $AAV_ID && !isset($first_record)) {
//       $first_record = $data[$i];
//       $start_session = true;      
//     }
//     //The first Interaction is captured
//     if(isset($first_record)){
//       if($data[$i]["user_id"] != $first_record["user_id"]){
        
//         //Return back one record and add it
//         array_push($result, $first_record, $data[$i - 1]);
  
//         //Check if the new user's Interaction is AAV
//         if($data[$i]["exercise_id"] == $AAV_ID){
//           $first_record = $data[$i];
//         }
//         else{
//           $first_record = null;
//         }
//       }
//       else if($data[$i]["user_id"] == $first_record["user_id"] && 
//         $data[$i]["exercise_id"] != $first_record["exercise_id"]){
//         //Return back one record
//         array_push($result, $first_record, $data[$i]);
//         $first_record = null;
//       }
//       //If the last intearction is an AAV interction
//       if($i == count($data) - 1){
//         array_push($result, $first_record, $data[$i]);    
//       }
//     }    
//   }
//   //print_r($result);

//   calculate_baseline($result, $AAV_ID);
// }

 
// //////////////////////////////////////////////////////////////////////////////////////////////

// function calculate_baseline($result, $AAV_ID){
//   //Calculating the baseline data points
//   global $module_name;
//   global $module_id;
//   global $book_id; 
//   global $start_date;
//   global $end_date;
//   global $db_server;
//   global $db_username;
//   global $db_pass;
//   global $db_name;
//   global $total_time;

//   $maxDiff = 0;
//   $seconds = array();
//   for($i = 0;$i < count($result); $i += 2){
//     $dateDiff = strtotime($result[$i+1]['action_time']) - strtotime($result[$i]['action_time']);
	  
// 	  if($dateDiff > $maxDiff && $dateDiff <= 600){
// 	    $maxDiff = $dateDiff;
// 	  }
	  
//     if($i < count($result)-2){
//       if($result[$i+2]['user_id'] != $result[$i]['user_id']){
//         //Add maxDiff to the list
//         array_push($seconds, array($result[$i]['user_id'], $AAV_ID, $maxDiff, $module_name[$module_id], $book_id));
//         $maxDiff = 0;
//       }
//     }
//     else{
//       //Adding the last one
//       array_push($seconds, array($result[$i]['user_id'], $AAV_ID, $maxDiff, $module_name[$module_id], $book_id));
// 	   }
//   }

//   //print_r($seconds);
  
//   //Fill in csv file
//   $file = fopen("Output//AAV $AAV_ID IN $module_name[$module_id] In Book $book_id AAV Analysis.csv",'w');
//   //Adding headers
//   fputcsv($file, array("User_ID", "AAV_ID", "Difference In Seconds" ,"Module Name","Book_ID"));
//   foreach($seconds as $line){
//     fputcsv($file, $line);
//   }

//   //Add the seconds array to the total array
//   for($i = 0; $i < count($seconds); $i++){
//     array_push($total_time, $seconds[$i]);
//   }

//   echo "Data Written to file Successfuly\n";
// }
////////////////////////////////////////////////////////////////////////////////////////
function calculate_time_AAV($AAV_ID){
  global $module_id;
  global $start_date;
  global $end_date;
  global $db_server;
  global $db_username;
  global $db_pass;
  global $db_name;
  global $book_id;

  $email_table = "";
  $email_column = "";

  if($book_id == "VT"){
    $email_table = "CS3114VTRoster";
    $email_column = "user_email";
  }
  else if ($book_id == "CNP"){
    $email_table = "CNPRoster"; 
    $email_column = "email";
  }


  $query = "SELECT * 
                  FROM  `opendsa_userbutton` A INNER JOIN `auth_user` B ON
                  A.user_id = B.id 
                  WHERE email IN 
                 (Select $email_column from $email_table) 
                  AND module_id = $module_id
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
    if($data[$i]['exercise_id'] == $AAV_ID && !isset($first_record)) {
      $first_record = $data[$i];
      $start_session = true;      
    }
    //The first Interaction is captured
    if(isset($first_record)){
      if($data[$i]["email"] != $first_record["email"]){
        
        //Return back one record and add it
        array_push($result, $first_record, $data[$i - 1]);
  
        //Check if the new user's Interaction is AAV
        if($data[$i]["exercise_id"] == $AAV_ID){
          $first_record = $data[$i];
        }
        else{
          $first_record = null;
        }
      }
      else if($data[$i]["email"] == $first_record["email"] && 
        $data[$i]["exercise_id"] != $first_record["exercise_id"]){
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

  calculate_baseline($result, $AAV_ID);
}

 
//////////////////////////////////////////////////////////////////////////////////////////////

function calculate_baseline($result, $AAV_ID){
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
  global $total_time;

  $sumDiff = 0;
  $number_of_visits = 0;
  $seconds = array();
  for($i = 0;$i < count($result); $i += 2){
    $dateDiff = strtotime($result[$i+1]['action_time']) - strtotime($result[$i]['action_time']);
    $number_of_visits++;
    $sumDiff += $dateDiff;
    
    if($i < count($result)-2){
      if($result[$i+2]['email'] != $result[$i]['email']){
        //Add maxDiff to the list
        array_push($seconds, array($result[$i]['email'], $AAV_ID, $sumDiff, $number_of_visits, $module_name[$module_id]));
        $sumDiff = 0;
        $number_of_visits = 0;
      }
    }
    else{
      //Adding the last one
      array_push($seconds, array($result[$i]['email'], $AAV_ID, $sumDiff, $number_of_visits, $module_name[$module_id]));
     }
  }

  //print_r($seconds);

  //Add the seconds array to the total array
  for($i = 0; $i < count($seconds); $i++){
    array_push($total_time, $seconds[$i]);
  }
  
  //Fill in csv file
  $file = fopen("Output//AAV $AAV_ID IN $module_name[$module_id] In $book_id AAV Analysis.csv",'w');
  //Adding headers
  fputcsv($file, array("User_Email", "AAV_ID", "Total Time" , "Number of Visits", "Module Name"));
  foreach($seconds as $line){
    fputcsv($file, $line);
  }

  echo "Data Written to file Successfuly\n";
}
////////////////////////////////////////////////////////////////////////////////////////
function dumpTotal($total_time){
    global $module_name;
    global $module_id;
    global $book_id; 

    $average_time = 0;
    $average_visits = 0;
    $total_by_user = array();
    for ($i = 0; $i < count($total_time); $i++) { 
      $index = search($total_by_user, $total_time[$i][0]);
      if($index == -1){
        array_push($total_by_user, array($total_time[$i][0], $total_time[$i][2], $total_time[$i][3], $total_time[$i][4]));
      }
      else{
        $total_by_user[$index][1] += $total_time[$i][2];
        $total_by_user[$index][2] += $total_time[$i][3];
      }
    }

    //Calculate the average time and average number of visits
    $sum_time = 0;
    $sum_visits = 0;
    for ($i = 0; $i < count($total_by_user); $i++) { 
      $sum_time += $total_by_user[$i][1];
      $sum_visits += $total_by_user[$i][2];
    } 

    $average_time = $sum_time / count($total_by_user);   
    $average_visits = $sum_visits / count($total_by_user);   

  //Fill in csv file
  $file = fopen("Output//$module_name[$module_id] In Book $book_id AAV Analysis.csv",'w');
  //Adding headers
  fputcsv($file, array("User_Email", "Total Time" , "Number of Visits","Module Name"));
  foreach($total_by_user as $line){
    fputcsv($file, $line);
  }

  //Add Averages
  fputcsv($file, array("Average", $average_time, $average_visits));

  echo "Data Written to file Successfuly\n";
}
/////////////////////////////////////////////////////////////////////////////////////
function search($array, $target){
  for($i = 0; $i < count($array); $i++){
    if($array[$i][0] == $target){
      return $i;
    }
  }
  return -1;
}
