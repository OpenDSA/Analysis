
<?php

include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_connect.php");

set_time_limit(0);

// mysql_query("UPDATE user SET attempts_pro=0 WHERE attempts_pro !=0");
// mysql_query("UPDATE user SET hints_pro=0 WHERE hints_pro !=0");
// mysql_query("UPDATE user SET total_incorrect=0 WHERE total_incorrect !=0");

  $start = mysql_query("SELECT user_id FROM user");

  while($row = mysql_fetch_assoc($start)) {

    $attempts_pro=0;
    $hints_pro=0;
    $time_pro =0;

    $uid=$row["user_id"];

    // $exloop = mysql_query("SELECT exercise FROM CS3114_EX");
    $exloop = mysql_query("SELECT exercise FROM CS3114_PE");

    while($row = mysql_fetch_assoc($exloop)) {
      $total_hints =0;
      $total_attempts =0;
      $total_time =0;

    $exercise=$row["exercise"];


    //Calculate attempts and hints until earning proficiency
    // $pattempt = mysql_query("SELECT MIN(id) FROM student_data where user_id= '$uid' and name = '$exercise' and earned_proficiency = 1");
    // $pattempt = mysql_query("SELECT id FROM student_data where user_id= '$uid' and name = '$exercise' and earned_proficiency = 1 order by id ASC limit 1");
    $pattempt = mysql_query("SELECT id FROM student_pe where user_id= '$uid' and name = '$exercise' and earned_proficiency = 1 order by id ASC limit 1");
    while($row = mysql_fetch_assoc($pattempt)) {
      $pid=$row["id"];
      // echo $pid;
      // echo "<br>";

      // $pattempt2 = mysql_query("SELECT * FROM student_data where user_id= '$uid' and name = '$exercise' and id <= '$pid' ORDER BY id");
      $pattempt2 = mysql_query("SELECT * FROM student_pe where user_id= '$uid' and name = '$exercise' and id <= '$pid' ORDER BY id");

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

      // $time = mysql_query("SELECT SUM(time_taken) as ttime FROM student_data where user_id= '$uid' and name = '$exercise' and id <= '$pid' ORDER BY id");
      $time = mysql_query("SELECT SUM(time_taken) as ttime FROM student_pe where user_id= '$uid' and name = '$exercise' and id <= '$pid' ORDER BY id");
      while($row = mysql_fetch_assoc($time)) {
            $total_time=$row["ttime"];
      }

  }

  // echo "T_attempts:";
  $total_attempts;
  // echo "T_hints:";
  $total_hints;
  // echo "T_time:";
  $total_time;
  // echo "<br>";

// mysql_query("INSERT INTO user3 (user_id, exercise, attempts_pro, hints_pro, time_pro) VALUES  ($uid, '$exercise', $total_attempts, $total_hints, $total_time)");
mysql_query("INSERT INTO user_p2 (user_id, exercise, attempts_pro, hints_pro, time_pro) VALUES  ($uid, '$exercise', $total_attempts, $total_hints, $total_time)");
$attempts_pro +=$total_attempts;
$hints_pro +=$total_hints;
$time_pro +=$total_time;
// echo "<br>";
}

// $attempts_pro=$attempts_pro/71;
// $hints_pro=$hints_pro/71;
// $time_pro +=$time_pro/71;

$attempts_pro=$attempts_pro/28;
$hints_pro=$hints_pro/28;
$time_pro +=$time_pro/28;

// mysql_query("UPDATE user SET attempts_pro='$attempts_pro' WHERE user_id = '$uid'");
// mysql_query("UPDATE user SET hints_pro='$hints_pro' WHERE user_id = '$uid'");
// mysql_query("UPDATE user SET time_pro='$time_pro' WHERE user_id = '$uid'");

mysql_query("UPDATE user_p SET attempts_pro='$attempts_pro' WHERE user_id = '$uid'");
mysql_query("UPDATE user_p SET hints_pro='$hints_pro' WHERE user_id = '$uid'");
mysql_query("UPDATE user_p SET time_pro='$time_pro' WHERE user_id = '$uid'");

}


include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_disconnect.php");

echo "It's Done!";

?>
