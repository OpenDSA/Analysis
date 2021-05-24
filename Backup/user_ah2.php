
<?php

include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_connect.php");

set_time_limit(0);

$time_ex =0;
$time_hint =0;

$update = mysql_query("SELECT user_id FROM user");

while($row = mysql_fetch_assoc($update)) {

  $uid=$row["user_id"];

  $start = mysql_query("SELECT * from student_data where user_id= '$uid' and ex_type=2 ORDER BY id, exercise_id, user_id");
  // $start = mysql_query("SELECT * from student_pe where user_id= '$uid' ORDER BY id, exercise_id, user_id");

  while($row = mysql_fetch_assoc($start)) {

    $id=$row["id"];
    $uid=$row["user_id"];
    $eid=$row["exercise_id"];

    $hint=$row["count_hints"];
    $attempt=$row["count_attempts"];
    $time=$row["time_taken"];


    if ($hint!=0 && $attempt==0)
    {
      $time_hint = $time_hint+$time;
    }

    if ($hint==0 && $attempt!=0)
    {
      $time_ex = $time_ex+$time;
    }


    if ($hint!=0 && $attempt!=0)
    {

      // echo $uid;
      // echo "\t";
      // echo $eid;
      // echo "\t";
      // echo $id;
      // echo "<br>";
      $prow = mysql_query("SELECT * FROM student_data where id = (SELECT max(id) FROM student_data where user_id ='$uid' and exercise_id ='$eid' and id < '$id') and and ex_type=2");
      // $prow = mysql_query("SELECT * FROM student_pe where id = (SELECT max(id) FROM student_pe where user_id ='$uid' and exercise_id ='$eid' and id < '$id')");

      while($row = mysql_fetch_assoc($prow)) {

        $hint_p=$row["count_hints"];
        $attempt_p=$row["count_attempts"];

        if ($hint>$hint_p)
        {
          $time_hint = $time_hint+$time;
        }

        if ($attempt>$attempt_p)
        {
          $time_ex = $time_ex+$time;
        }


      }


    }

    mysql_query("UPDATE nuser_summ SET time_ex='$time_ex' WHERE user_id = '$uid'");
    mysql_query("UPDATE nuser_summ SET time_hint='$time_hint' WHERE user_id = '$uid'");

    // mysql_query("UPDATE nuser_p SET time_ex='$time_ex' WHERE user_id = '$uid'");
    // mysql_query("UPDATE nuser_p SET time_hint='$time_hint' WHERE user_id = '$uid'");

  }

  $time_ex =0;
  $time_hint =0;

}



include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_disconnect.php");

echo "It's Done!";

?>
