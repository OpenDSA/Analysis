
<?php

include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_connect.php");

set_time_limit(0);

// mysql_query("UPDATE user SET attempts_pro=0 WHERE attempts_pro !=0");
// mysql_query("UPDATE user SET hints_pro=0 WHERE hints_pro !=0");
// mysql_query("UPDATE user SET total_incorrect=0 WHERE total_incorrect !=0");

  // $start = mysql_query("SELECT * FROM user3");
  $start = mysql_query("SELECT * FROM user_p2");

  while($row = mysql_fetch_assoc($start)) {


    $uid=$row["user_id"];
    $exercise=$row["exercise"];
    $attempts_pro=$row["attempts_pro"];
    $hints_pro=$row["hints_pro"];
    $time_pro=$row["time_pro"];

    $total_hints =0;
    $total_attempts =0;
    $total_time =0;

    // $pattempt2 = mysql_query("SELECT * FROM student_data where user_id= '$uid' and name = '$exercise' ORDER BY id");
    $pattempt2 = mysql_query("SELECT * FROM student_pe where user_id= '$uid' and name = '$exercise' ORDER BY id");

    while($row = mysql_fetch_assoc($pattempt2)) {

      $id=$row["id"];

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

        // $prow = mysql_query("SELECT * FROM student_data where id = (SELECT max(id) FROM student_data where user_id =$uid and name = '$exercise' and id < $id)");
        $prow = mysql_query("SELECT * FROM student_pe where id = (SELECT max(id) FROM student_pe where user_id =$uid and name = '$exercise' and id < $id)");

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


    }

      // $time = mysql_query("SELECT SUM(time_taken) as ttime FROM student_data where user_id= '$uid' and name = '$exercise' ORDER BY id");
      $time = mysql_query("SELECT SUM(time_taken) as ttime FROM student_pe where user_id= '$uid' and name = '$exercise' ORDER BY id");
      while($row = mysql_fetch_assoc($time)) {
      $total_time=$row["ttime"];
      }

      $after_attempts = $total_attempts-$attempts_pro;
      // mysql_query("UPDATE user3 SET attempts_aft='$after_attempts' WHERE user_id = '$uid'and exercise = '$exercise'");
      mysql_query("UPDATE user_p2 SET attempts_aft='$after_attempts' WHERE user_id = '$uid'and exercise = '$exercise'");
      $after_hints = $total_hints-$hints_pro;
      // mysql_query("UPDATE user3 SET hints_aft='$after_hints' WHERE user_id = '$uid'and exercise = '$exercise'");
      mysql_query("UPDATE user_p2 SET hints_aft='$after_hints' WHERE user_id = '$uid'and exercise = '$exercise'");
      $after_time = $total_time-$time_pro;
      // mysql_query("UPDATE user3 SET time_aft='$after_time' WHERE user_id = '$uid'and exercise = '$exercise'");
      mysql_query("UPDATE user_p2 SET time_aft='$after_time' WHERE user_id = '$uid'and exercise = '$exercise'");


      // $attempts_pro +=$total_attempts;
      // $hints_pro +=$total_hints;
      // $time_pro +=$total_time;
      //
      // mysql_query("UPDATE user SET attempts_pro='$attempts_pro' WHERE user_id = '$uid'");
      // mysql_query("UPDATE user SET hints_pro='$hints_pro' WHERE user_id = '$uid'");
      // mysql_query("UPDATE user SET time_pro='$time_pro' WHERE user_id = '$uid'");


  }



include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_disconnect.php");

echo "It's Done!";

?>
