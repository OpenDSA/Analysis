
<?php

include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_connect.php");

set_time_limit(0);

// mysql_query("UPDATE user SET attempts_pro=0 WHERE attempts_pro !=0");
// mysql_query("UPDATE user SET hints_pro=0 WHERE hints_pro !=0");
// mysql_query("UPDATE user SET total_incorrect=0 WHERE total_incorrect !=0");

  $start = mysql_query("SELECT * FROM user");

  while($row = mysql_fetch_assoc($start)) {


    $uid=$row["user_id"];

    $attempts_aft=$row["attempts_aft"];
    $hints_aft=$row["hints_aft"];
    $time_aft=$row["time_aft"];

    // $attempts = mysql_query("SELECT SUM(attempts_aft) as attempts_a FROM user3 where user_id= '$uid'");
    // while($row = mysql_fetch_assoc($attempts)) {
    // $attempts_aft=$row["attempts_a"];
    // $attempts_aft=$attempts_aft/71;
    // }
    //
    // $hints = mysql_query("SELECT SUM(hints_aft) as hints_a FROM user3 where user_id= '$uid'");
    // while($row = mysql_fetch_assoc($hints)) {
    // $hints_aft=$row["hints_a"];
    // $hints_aft=$hints_aft/71;
    // }
    //
    // $time = mysql_query("SELECT SUM(time_aft) as time_a FROM user3 where user_id= '$uid'");
    // while($row = mysql_fetch_assoc($time)) {
    // $time_aft=$row["time_a"];
    // $time_aft=$time_aft/71;
    // }
    //
    // mysql_query("UPDATE user SET attempts_aft='$attempts_aft' WHERE user_id = '$uid'");
    // mysql_query("UPDATE user SET hints_aft='$hints_aft' WHERE user_id = '$uid'");
    // mysql_query("UPDATE user SET time_aft='$time_aft' WHERE user_id = '$uid'");

    $attempts = mysql_query("SELECT SUM(attempts_aft) as attempts_a FROM user_p2 where user_id= '$uid'");
    while($row = mysql_fetch_assoc($attempts)) {
    $attempts_aft=$row["attempts_a"];
    $attempts_aft=$attempts_aft/28;
    }

    $hints = mysql_query("SELECT SUM(hints_aft) as hints_a FROM user_p2 where user_id= '$uid'");
    while($row = mysql_fetch_assoc($hints)) {
    $hints_aft=$row["hints_a"];
    $hints_aft=$hints_aft/28;
    }

    $time = mysql_query("SELECT SUM(time_aft) as time_a FROM user_p2 where user_id= '$uid'");
    while($row = mysql_fetch_assoc($time)) {
    $time_aft=$row["time_a"];
    $time_aft=$time_aft/28;
    }

    mysql_query("UPDATE user_p SET attempts_aft='$attempts_aft' WHERE user_id = '$uid'");
    mysql_query("UPDATE user_p SET hints_aft='$hints_aft' WHERE user_id = '$uid'");
    mysql_query("UPDATE user_p SET time_aft='$time_aft' WHERE user_id = '$uid'");


  }


include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_disconnect.php");

echo "It's Done!";

?>
