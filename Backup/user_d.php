
<?php

include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_connect.php");

set_time_limit(0);

// mysql_query("UPDATE user SET attempts_pro=0 WHERE attempts_pro !=0");
// mysql_query("UPDATE user SET hints_pro=0 WHERE hints_pro !=0");
// mysql_query("UPDATE user SET total_incorrect=0 WHERE total_incorrect !=0");

$update = mysql_query("SELECT user_id FROM user");

while($row = mysql_fetch_assoc($update)) {

  $uid=$row["user_id"];

    // Calculate incorrect
    // $correct = mysql_query("SELECT SUM(correct) as tcorrect FROM student_data where user_id= '$uid' AND correct =1 ");
    $correct = mysql_query("SELECT SUM(correct) as tcorrect FROM student_pe where user_id= '$uid' AND correct =1 ");
    while($row = mysql_fetch_assoc($correct)) {
    $total_corrects=$row["tcorrect"];
    // mysql_query("UPDATE user SET total_correct='$total_corrects' WHERE user_id = '$uid'");
    mysql_query("UPDATE user_p SET total_correct='$total_corrects' WHERE user_id = '$uid'");
    }

    // $incorrect = mysql_query("SELECT total_attempts FROM user where user_id= '$uid'");
    $incorrect = mysql_query("SELECT total_attempts FROM user_p where user_id= '$uid'");
    while($row = mysql_fetch_assoc($incorrect)) {
    $total_attempts=$row["total_attempts"];
    $total_incorrect = $total_attempts-$total_corrects;
    // mysql_query("UPDATE user SET total_incorrect='$total_incorrect' WHERE user_id = '$uid'");
    mysql_query("UPDATE user_p SET total_incorrect='$total_incorrect' WHERE user_id = '$uid'");
    }

    // Total Spent Time
    // $time = mysql_query("SELECT SUM(time_taken) as ttime FROM student_data where user_id= '$uid'");
    $time = mysql_query("SELECT SUM(time_taken) as ttime FROM student_pe where user_id= '$uid'");
    while($row = mysql_fetch_assoc($time)) {
    $total_time=$row["ttime"];
    // mysql_query("UPDATE user SET total_time='$total_time' WHERE user_id = '$uid'");
    mysql_query("UPDATE user_p SET total_time='$total_time' WHERE user_id = '$uid'");
    }

}

include ($_SERVER['DOCUMENT_ROOT'] . "/data/db_disconnect.php");

echo "It's Done!";

?>
