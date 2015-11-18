
<?php

include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_connect.php");

set_time_limit(0);

$total_hints =0;
$total_attempts =0;

$update = mysql_query("SELECT user_id FROM user");

while($row = mysql_fetch_assoc($update)) {

  $uid=$row["user_id"];

  // $start = mysql_query("SELECT * from student_data where user_id= '$uid' ORDER BY id, exercise_id, user_id");
  $start = mysql_query("SELECT * from student_pe where user_id= '$uid' ORDER BY id, exercise_id, user_id");

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
      // $prow = mysql_query("SELECT * FROM student_data where id = (SELECT max(id) FROM student_data where user_id ='$uid' and exercise_id ='$eid' and id < '$id')");
      $prow = mysql_query("SELECT * FROM student_pe where id = (SELECT max(id) FROM student_pe where user_id ='$uid' and exercise_id ='$eid' and id < '$id')");

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

    // mysql_query("UPDATE user SET total_attempts='$total_attempts' WHERE user_id = '$uid'");
    // mysql_query("UPDATE user SET total_hints='$total_hints' WHERE user_id = '$uid'");

    mysql_query("UPDATE user_p SET total_attempts='$total_attempts' WHERE user_id = '$uid'");
    mysql_query("UPDATE user_p SET total_hints='$total_hints' WHERE user_id = '$uid'");

  }

  $total_hints =0;
  $total_attempts =0;

}



include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_disconnect.php");

echo "It's Done!";

?>
