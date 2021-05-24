
<?php

include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_connect.php");

set_time_limit(0);

// mysql_query("CREATE VIEW student_log AS SELECT A.id, user_id, exercise_id, name, correct, time_done, time_taken, count_hints, hint_used, earned_proficiency, count_attempts
//            FROM `opendsa_userexerciselog` A INNER JOIN `opendsa_exercise` B ON A.exercise_id = B.id
//            where user_id IN
//            (Select user_id from `opendsa_userbook` where grade = 1 AND book_id = 5)
//            AND exercise_id IN
//            (Select C.id from `opendsa_exercise` C INNER JOIN `opendsa_bookmoduleexercise` D ON C.id = D.exercise_id where ex_type = 'ka' AND book_id = 5)
//            AND time_done BETWEEN '2014-08-26' AND  '2014-12-20'
//            ORDER BY exercise_id, user_id, time_done");

          //  $total_hints =0;
          //  $total_attempts =0;


// $start = mysql_query("SELECT * from student_data ORDER BY exercise_id, user_id, time_done");
$start = mysql_query("SELECT * from student_pe ORDER BY exercise_id, user_id, time_done");

while($row = mysql_fetch_assoc($start)) {

  $uid=$row["user_id"];
  $eid=$row["exercise_id"];
  $ename=$row["name"];


  $time = mysql_query("SELECT SUM(time_taken) as ttime FROM student_pe where exercise_id = '$eid'");
  while($row = mysql_fetch_assoc($time)) {
  $total_time=$row["ttime"];
  }


  $correct = mysql_query("SELECT SUM(correct) as tcorrect FROM student_pe where exercise_id = '$eid' AND correct =1 ");
  while($row = mysql_fetch_assoc($correct)) {
  $total_corrects=$row["tcorrect"];
  }

  // $exist = mysql_query("SELECT exercise from CS3114_EX where exercise = '$ename'");
  $exist = mysql_query("SELECT exercise from CS3114_PE where exercise = '$ename'");

  if ($row = mysql_fetch_assoc($exist)) {
      }
  else {
    // mysql_query("INSERT INTO CS3114_EX (exercise, time, corrects) VALUES  ('$ename','$total_time', '$total_corrects')");
    mysql_query("INSERT INTO CS3114_PE (exercise, time, corrects) VALUES  ('$ename','$total_time', '$total_corrects')");
    // mysql_query("INSERT INTO CS3114_EX (exercise, attempts, time, corrects) VALUES  ('$ename','$total_attempts', '$total_time', '$total_corrects')");
  }


  // $attempts = mysql_query("SELECT * FROM student_data where exercise_id = '$eid' AND user_id ='$uid'");
  // while($row = mysql_fetch_assoc($attempts)) {
  //
  //   $hint=$row["hint_used"];
  //   $attempt=$row["count_attempts"];
  //   $exname=$row["name"];
  //
  //   if ($hint!=0 && $attempt==0)
  //   {
  //     $total_hints +=1;
  //   }
  //
  //   if ($hint==0 && $attempt!=0)
  //   {
  //     $total_attempts +=1;
  //   }
  //
  //
  //
  //   if ($hint!=0 && $attempt!=0)
  //   {
  //
  //     $prow = mysql_query("SELECT count_hints, count_attempts FROM student_log where id = (SELECT max(id) FROM student_log where user_id ='$uid' and exercise_id = '$eid' and id < '$id'");
  //
  //     while($row = mysql_fetch_assoc($prow)) {
  //       $hint_p=$row["count_hints"];
  //       $attempt_p=$row["count_attempts"];
  //
  //       if ($hint>$hint_p)
  //       {
  //         $total_hints +=1;
  //       }
  //
  //       if ($attempt>$attempt_p)
  //       {
  //         $total_attempts +=1;
  //       }
  //
  //     }
  //
  //
  //   }
  //
  //   $update = mysql_query("SELECT attempts, hints FROM CS3114_EX where exercise = '$exname'");
  //   while($row = mysql_fetch_assoc($update)) {
  //     $hint_s=$row["hints"];
  //     $attempt_s=$row["attempts"];
  //
  //       $total_hints +=$hint_s;
  //       $total_attempts +=$attempt_s;
  //
  //        mysql_query("UPDATE CS3114_EX SET attempts='$total_attempts' WHERE exercise = '$exname'");
  //        mysql_query("UPDATE CS3114_EX SET hints='$total_hints' WHERE exercise = '$exname'");
  //
  //
  //   }
  //
  //   $total_hints =0;
  //   $total_attempts =0;
  //
  // }



}


  //
  // // echo $ename;
  // // echo "<br>";
  // // echo $total_time;
  // // echo "<br>";
  // // echo $total_hints;
  // // echo "<br>";
  // // echo $total_attempts;
  // // echo "<br>";
  //


include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_disconnect.php");

echo "It's Done!";

?>
