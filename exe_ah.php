
<?php

include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_connect.php");

set_time_limit(0);



$total_hints =0;
$total_attempts =0;

// $update = mysql_query("SELECT exercise, attempts, hints FROM CS3114_EX");
$update = mysql_query("SELECT exercise, attempts, hints FROM CS3114_PE");

while($row = mysql_fetch_assoc($update)) {

  $ex=$row["exercise"];
  $hint_s=$row["hints"];
  $attempt_s=$row["attempts"];

  // $start = mysql_query("SELECT * from student_data where name= '$ex' ORDER BY id, exercise_id, user_id");
  $start = mysql_query("SELECT * from student_pe where name= '$ex' ORDER BY id, exercise_id, user_id");

  while($row = mysql_fetch_assoc($start)) {

    $id=$row["id"];
    $uid=$row["user_id"];
    $eid=$row["exercise_id"];

    $hint=$row["count_hints"];
    $attempt=$row["count_attempts"];


    if ($hint!=0 && $attempt==0)
    {
      $total_hints +=1;
    }

    if ($hint==0 && $attempt!=0)
    {
      $total_attempts +=1;
    }


    if ($hint!=0 && $attempt!=0)
    {

      // echo $uid;
      // echo "\t";
      // echo $eid;
      // echo "\t";
      // echo $id;
      // echo "<br>";
      // $prow = mysql_query("SELECT * FROM student_data where id = (SELECT max(id) FROM student_data where user_id =$uid and exercise_id =$eid and id < $id)");
      $prow = mysql_query("SELECT * FROM student_pe where id = (SELECT max(id) FROM student_pe where user_id =$uid and exercise_id =$eid and id < $id)");

      while($row = mysql_fetch_assoc($prow)) {

        $hint_p=$row["count_hints"];
        $attempt_p=$row["count_attempts"];

        if ($hint>$hint_p)
        {
          $total_hints +=1;
        }

        if ($attempt>$attempt_p)
        {
          $total_attempts +=1;
        }


      }


    }

    $total_hints +=$hint_s;
    $total_attempts +=$attempt_s;

    // mysql_query("UPDATE CS3114_EX SET attempts='$total_attempts' WHERE exercise = '$ex'");
    // mysql_query("UPDATE CS3114_EX SET hints='$total_hints' WHERE exercise = '$ex'");

    mysql_query("UPDATE CS3114_PE SET attempts='$total_attempts' WHERE exercise = '$ex'");
    mysql_query("UPDATE CS3114_PE SET hints='$total_hints' WHERE exercise = '$ex'");


  }

  $total_hints =0;
  $total_attempts =0;

}



include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_disconnect.php");

echo "It's Done!";

?>
