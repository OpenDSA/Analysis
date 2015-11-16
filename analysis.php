<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ODSA Student Activity Analysis</title>
	<link rel="stylesheet" href="style.css"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="disable_radio.js"></script>
</head>

<?php

include("../class/pData.class.php");
include("../class/pDraw.class.php");
include("../class/pImage.class.php");
include("../class/pScatter.class.php");
include ("../db_connect.php");


	if(isset($_POST['formSubmit']))
    {


			$exercise = $_POST['exercise'];
			$first = $_POST['first'];
			$parameter = $_POST['parameters'];
			$quartile = $_POST['quartile'];
			$type = $_POST['second'];
			$quartile = trim(htmlspecialchars_decode($quartile));

			$parameter1 = 	$parameter[0];
			$parameter2 = 	$parameter[1];

			if(empty($exercise)||empty($parameter))
			{
				echo("<p>HEY! You did NOT choose all necessary parameters.</p>\n");
			}
			else
			{
				// if(isset($_POST['second']) && $_POST['formWheelchair'] == 'Yes')


					if( $exercise == 'ka')
					{

						if ($exercise == 'ka' && $first == "Enable" && $type == "summ") {
							if(isset($_POST['option']))
							{
								$start1 = mysql_query("SELECT * FROM nuser_summ where stest >157 order by $quartile asc limit 34");
								$start2 = mysql_query("SELECT * FROM nuser_summ where stest >157 order by $quartile desc limit 34");
							}
							else
							{
								$start = mysql_query("SELECT * FROM nuser_summ where stest >157");
							}
								$extype = "KA Summary Exercise";
						}

						elseif ($exercise == 'ka' && $first == "Enable" && $type == "pro") {
							if(isset($_POST['option']))
							{
								$start1 = mysql_query("SELECT * FROM nuser_pro where stest >157 order by $quartile asc limit 34");
								$start2 = mysql_query("SELECT * FROM nuser_pro where stest >157 order by $quartile desc limit 34");
							}
							else
							{
								$start = mysql_query("SELECT * FROM nuser_pro where stest >157");
							}
								$extype = "KA Proficiency Exercise";
						}

						elseif ($exercise == 'ka' && $first == "Enable" && $type == "prog") {
							if(isset($_POST['option']))
							{
								$start1 = mysql_query("SELECT * FROM nuser_prog where stest >157 order by $quartile asc limit 34");
								$start2 = mysql_query("SELECT * FROM nuser_prog where stest >157 order by $quartile desc limit 34");
							}
							else
							{
								$start = mysql_query("SELECT * FROM nuser_prog where stest >157");
							}
								$extype = "KA Programming Exercise";
						}
						else {
							if(isset($_POST['option']))
							{
								$start1 = mysql_query("SELECT * FROM nuser where stest >157 order by $quartile asc limit 34");
								$start2 = mysql_query("SELECT * FROM nuser where stest >157 order by $quartile desc limit 34");
							}
							else
							{
								$start = mysql_query("SELECT * FROM nuser where stest >157");
							}
								$extype = "KA Exercise";
						}
						// // include ("graph.php");
						// // $start = mysql_query("SELECT * FROM nuser where stest >157");
					}

					else {

						if(isset($_POST['option']))
						{
							$start1 = mysql_query("SELECT * FROM nuser_p where stest >157 order by $quartile asc limit 34");
							$start2 = mysql_query("SELECT * FROM nuser_p where stest >157 order by $quartile desc limit 34");
						}
						else
						{
							$start = mysql_query("SELECT * FROM nuser_p where stest >157");
						}
						$extype = "Proficiency Exercise";
						// include ("graph.php");
					}

							// $n = count($parameter);
							//
							// echo("<p>You selected $n parameter(s): ");
							// for($i=0; $i < $n; $i++)
							// {
							// 	echo($parameter[$i]. " ");
							// 	$target = $parameter[$i];
							// }
							// echo("</p>");

							if ($parameter1 == "attempts_pro") {
								$xaxis = "Attempts until Proficiency Earned";
							}
							elseif ($parameter1 == "time_pro") {
									 $xaxis = "Time until Proficiency Earned";
							}
							elseif ($parameter1 == "hints_pro") {
									$xaxis = "Hints until Proficiency Earned";
							}
							elseif ($parameter1 == "attempts_aft") {
								$xaxis = "Attempts after Proficiency Earned";
							}
							elseif ($parameter1 == "time_aft") {
									$xaxis = "Time after Proficiency Earned";
							}
							elseif ($parameter1 == "hints_aft") {
									$xaxis = "Hints after Proficiency Earned";
							}
							elseif ($parameter1 == "stest") {
									$xaxis = "Student Exam Score";
							}

							if ($parameter2 == "attempts_pro") {
								$yaxis = "Attempts until Proficiency Earned";
							}
							elseif ($parameter2 == "time_pro") {
									 $yaxis = "Time until Proficiency Earned";
							}
							elseif ($parameter2 == "hints_pro") {
									$yaxis = "Hints until Proficiency Earned";
							}
							elseif ($parameter2 == "attempts_aft") {
								$yaxis = "Attempts after Proficiency Earned";
							}
							elseif ($parameter2 == "time_aft") {
									$yaxis = "Time after Proficiency Earned";
							}
							elseif ($parameter2 == "hints_aft") {
									$yaxis = "Hints after Proficiency Earned";
							}
							elseif ($parameter2 == "stest") {
									$yaxis = "Student Exam Score";
							}

			}


}

/* Create the pData object */
$myData = new pData();

/* Create the X axis and the binded series */
// for ($i=0;$i<=360;$i=$i+10)
// { $myData->addPoints(rand(1,20)*10+rand(0,$i),"Probe 1"); }

$sumx =0;
$sumxsquare=0;
$sumy =0;
$sumysquare=0;
$sumxy=0;
$atp=0;


// $start = mysql_query("SELECT * FROM nuser");
$num = mysql_num_rows($start);
while($row = mysql_fetch_assoc($start)) {
$uid=$row["user_id"];
// $atp=$row[$target];
$atp = trim(htmlspecialchars_decode($row[$parameter1]));
// round($atp, 3);
// $st=$row["stest"];
$st = trim(htmlspecialchars_decode($row[$parameter2]));
// echo $atp;
// echo "<br>";
$xsquare = pow($atp,2);
$sumx= $sumx+$atp;
$sumxsquare = $sumxsquare+$xsquare;

$myData->addPoints($atp,"Probe 1");
$myData->addPoints($st,"Probe 3");

$ysquare = pow($st,2);
$sumy= $sumy+$st;
$sumysquare = $sumysquare+$ysquare;
$xy = $atp*$st;
$sumxy = $sumxy+$xy;

}

// calculate pearson
$numerator = $num*$sumxy-$sumx*$sumy;
$denominator = sqrt(($num*$sumxsquare-pow($sumx,2))*($num*$sumysquare-pow($sumy,2)));

if ($denominator == 0)
{
   $pearson = 0;
}
else
{
   $pearson = $numerator / $denominator;
}

$myData->setAxisName(0,"$xaxis");
$myData->setAxisXY(0,AXIS_X);
$myData->setAxisPosition(0,AXIS_POSITION_TOP);

/* Create the Y axis and the binded series */
// for ($i=0;$i<=360;$i=$i+10)
// { $myData->addPoints($i,"Probe 3"); }

// //Grades
// $grade = mysql_query("SELECT stest FROM nuser_p where stest >157");
// while($row = mysql_fetch_assoc($grade)) {
// 		$st=$row["stest"];
// $myData->addPoints($st*25,"Probe 3");
// }


$myData->setSerieOnAxis("Probe 3",1);
$myData->setAxisName(1,"$yaxis");
$myData->setAxisXY(1,AXIS_Y);
$myData->setAxisPosition(1,AXIS_POSITION_LEFT);

/* Create the 1st scatter chart binding */
$myData->setScatterSerie("Probe 1","Probe 3",0);
// $myData->setScatterSerieDescription(0,"$extype");
if(isset($_POST['option']))
{
$myData->setScatterSerieDescription(0,"1st Quartile");
}
else
{
$myData->setScatterSerieDescription(0,"$extype");
}

$myData->setScatterSerieColor(0,array("R"=>0,"G"=>0,"B"=>0));

/* Create the 2nd scatter chart binding */
if(isset($_POST['option']))
{
$myData->setScatterSerie("Probe 2","Probe 3",1);
$myData->setScatterSerieDescription(1,"3rd Quartile");
}


/* Create the pChart object */
$myPicture = new pImage(600,600,$myData);

/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0,600,600,$Settings);

/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,600,600,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,600,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

/* Write the picture title */
$myPicture->setFontProperties(array("FontName"=>"../fonts/Silkscreen.ttf","FontSize"=>8));
$myPicture->drawText(10,13,"Correlation Coefficient between Student Acitivity and Exam Grades",array("R"=>255,"G"=>255,"B"=>255));

/* Add a border to the picture */
$myPicture->drawRectangle(0,0,599,599,array("R"=>0,"G"=>0,"B"=>0));

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"../fonts/pf_arma_five.ttf","FontSize"=>9));

/* Set the graph area */
// $myPicture->setGraphArea(50,60,350,360);
$myPicture->setGraphArea(75,90,525,540);

/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture,$myData);

/* Draw the scale */
$myScatter->drawScatterScale();

/* Turn on shadow computing */
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

/* Draw a scatter plot chart */
$myScatter->drawScatterPlotChart();

/* Draw the legend */
$myScatter->drawScatterLegend(420,580,array("Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER));

/* Draw the line of best fit */
$myScatter->drawScatterBestFit();

/* Render the picture (choose the best way) */
// $myPicture->autoOutput("pictures/example.drawScatterBestFit.png");
 $myPicture->Render("attempts_pro.png");
?>

<?php

if(isset($_POST['option']))
{

	/* Create the pData object */
	$myData = new pData();

	/* Create the X axis and the binded series */
	// for ($i=0;$i<=360;$i=$i+10)
	// { $myData->addPoints(rand(1,20)*10+rand(0,$i),"Probe 1"); }

	$sumx =0;
	$sumxsquare=0;
	$sumy =0;
	$sumysquare=0;
	$sumxy=0;
	$atp=0;

	// $start = mysql_query("SELECT * FROM nuser");
	$num = mysql_num_rows($start1);
	while($row = mysql_fetch_assoc($start1)) {
	$uid=$row["user_id"];
	// $atp=$row[$target];
	$atp = trim(htmlspecialchars_decode($row[$parameter1]));
	// round($atp, 3);
	// $st=$row["stest"];
	$st = trim(htmlspecialchars_decode($row[$parameter2]));
	// echo $atp;
	// echo "<br>";
	$xsquare = pow($atp,2);
	$sumx= $sumx+$atp;
	$sumxsquare = $sumxsquare+$xsquare;

	$myData->addPoints($atp,"Probe 1");
	$myData->addPoints($st,"Probe 3");

	$ysquare = pow($st,2);
	$sumy= $sumy+$st;
	$sumysquare = $sumysquare+$ysquare;
	$xy = $atp*$st;
	$sumxy = $sumxy+$xy;

	}

	// calculate pearson
	$numerator = $num*$sumxy-$sumx*$sumy;
	$denominator = sqrt(($num*$sumxsquare-pow($sumx,2))*($num*$sumysquare-pow($sumy,2)));

	if ($denominator == 0)
	{
	   $pearson1 = 0;
	}
	else
	{
	   $pearson1 = $numerator / $denominator;
	}

	$myData->setAxisName(0,"$xaxis");
	$myData->setAxisXY(0,AXIS_X);
	$myData->setAxisPosition(0,AXIS_POSITION_TOP);

	$myData->setSerieOnAxis("Probe 3",1);
	$myData->setAxisName(1,"$yaxis");
	$myData->setAxisXY(1,AXIS_Y);
	$myData->setAxisPosition(1,AXIS_POSITION_LEFT);

	/* Create the 1st scatter chart binding */
	$myData->setScatterSerie("Probe 1","Probe 3",0);
	// $myData->setScatterSerieDescription(0,"$extype");

	$myData->setScatterSerieDescription(0,"1st Quartile");

	$myData->setScatterSerieColor(0,array("R"=>0,"G"=>0,"B"=>0));


	/* Create the pChart object */
	$myPicture = new pImage(600,600,$myData);

	/* Draw the background */
	$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
	$myPicture->drawFilledRectangle(0,0,600,600,$Settings);

	/* Overlay with a gradient */
	$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
	$myPicture->drawGradientArea(0,0,600,600,DIRECTION_VERTICAL,$Settings);
	$myPicture->drawGradientArea(0,0,600,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

	/* Write the picture title */
	$myPicture->setFontProperties(array("FontName"=>"../fonts/Silkscreen.ttf","FontSize"=>8));
	$myPicture->drawText(10,13,"Correlation Coefficient between Student Acitivity and Exam Grades",array("R"=>255,"G"=>255,"B"=>255));

	/* Add a border to the picture */
	$myPicture->drawRectangle(0,0,599,599,array("R"=>0,"G"=>0,"B"=>0));

	/* Set the default font */
	$myPicture->setFontProperties(array("FontName"=>"../fonts/pf_arma_five.ttf","FontSize"=>9));

	/* Set the graph area */
	// $myPicture->setGraphArea(50,60,350,360);
	$myPicture->setGraphArea(75,90,525,540);

	/* Create the Scatter chart object */
	$myScatter = new pScatter($myPicture,$myData);

	/* Draw the scale */
	$myScatter->drawScatterScale();

	/* Turn on shadow computing */
	$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

	/* Draw a scatter plot chart */
	$myScatter->drawScatterPlotChart();

	/* Draw the legend */
	$myScatter->drawScatterLegend(420,580,array("Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER));

	/* Draw the line of best fit */
	$myScatter->drawScatterBestFit();

	/* Render the picture (choose the best way) */
	// $myPicture->autoOutput("pictures/example.drawScatterBestFit.png");
	 $myPicture->Render("attempts_pro1.png");




	 /* Create the pData object */
 	$myData = new pData();

 	/* Create the X axis and the binded series */
 	// for ($i=0;$i<=360;$i=$i+10)
 	// { $myData->addPoints(rand(1,20)*10+rand(0,$i),"Probe 1"); }

 	$sumx =0;
 	$sumxsquare=0;
 	$sumy =0;
 	$sumysquare=0;
 	$sumxy=0;
 	$atp=0;

 	// $start = mysql_query("SELECT * FROM nuser");
 	$num = mysql_num_rows($start2);
 	while($row = mysql_fetch_assoc($start2)) {
 	$uid=$row["user_id"];
 	// $atp=$row[$target];
	$atp = trim(htmlspecialchars_decode($row[$parameter1]));
	// round($atp, 3);
	// $st=$row["stest"];
	$st = trim(htmlspecialchars_decode($row[$parameter2]));
 	// echo $atp;
 	// echo "<br>";
 	$xsquare = pow($atp,2);
 	$sumx= $sumx+$atp;
 	$sumxsquare = $sumxsquare+$xsquare;

 	$myData->addPoints($atp,"Probe 1");
 	$myData->addPoints($st,"Probe 3");

 	$ysquare = pow($st,2);
 	$sumy= $sumy+$st;
 	$sumysquare = $sumysquare+$ysquare;
 	$xy = $atp*$st;
 	$sumxy = $sumxy+$xy;

 	}

 	// calculate pearson
 	$numerator = $num*$sumxy-$sumx*$sumy;
 	$denominator = sqrt(($num*$sumxsquare-pow($sumx,2))*($num*$sumysquare-pow($sumy,2)));

 	if ($denominator == 0)
 	{
 	   $pearson2 = 0;
 	}
 	else
 	{
 	   $pearson2 = $numerator / $denominator;
 	}

 	$myData->setAxisName(0,"$xaxis");
 	$myData->setAxisXY(0,AXIS_X);
 	$myData->setAxisPosition(0,AXIS_POSITION_TOP);

 	$myData->setSerieOnAxis("Probe 3",1);
 	$myData->setAxisName(1,"$yaxis");
 	$myData->setAxisXY(1,AXIS_Y);
 	$myData->setAxisPosition(1,AXIS_POSITION_LEFT);

 	/* Create the 1st scatter chart binding */
 	$myData->setScatterSerie("Probe 1","Probe 3",0);
 	// $myData->setScatterSerieDescription(0,"$extype");

 	$myData->setScatterSerieDescription(0,"3rd Quartile");

 	$myData->setScatterSerieColor(0,array("R"=>0,"G"=>0,"B"=>0));

 	/* Create the pChart object */
 	$myPicture = new pImage(600,600,$myData);

 	/* Draw the background */
 	$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
 	$myPicture->drawFilledRectangle(0,0,600,600,$Settings);

 	/* Overlay with a gradient */
 	$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
 	$myPicture->drawGradientArea(0,0,600,600,DIRECTION_VERTICAL,$Settings);
 	$myPicture->drawGradientArea(0,0,600,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

 	/* Write the picture title */
 	$myPicture->setFontProperties(array("FontName"=>"../fonts/Silkscreen.ttf","FontSize"=>8));
 	$myPicture->drawText(10,13,"Correlation Coefficient between Student Acitivity and Exam Grades",array("R"=>255,"G"=>255,"B"=>255));

 	/* Add a border to the picture */
 	$myPicture->drawRectangle(0,0,599,599,array("R"=>0,"G"=>0,"B"=>0));

 	/* Set the default font */
 	$myPicture->setFontProperties(array("FontName"=>"../fonts/pf_arma_five.ttf","FontSize"=>9));

 	/* Set the graph area */
 	// $myPicture->setGraphArea(50,60,350,360);
 	$myPicture->setGraphArea(75,90,525,540);

 	/* Create the Scatter chart object */
 	$myScatter = new pScatter($myPicture,$myData);

 	/* Draw the scale */
 	$myScatter->drawScatterScale();

 	/* Turn on shadow computing */
 	$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

 	/* Draw a scatter plot chart */
 	$myScatter->drawScatterPlotChart();

 	/* Draw the legend */
 	$myScatter->drawScatterLegend(420,580,array("Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER));

 	/* Draw the line of best fit */
 	$myScatter->drawScatterBestFit();

 	/* Render the picture (choose the best way) */
 	// $myPicture->autoOutput("pictures/example.drawScatterBestFit.png");
 	 $myPicture->Render("attempts_pro2.png");


}
?>
<?php
echo "<body>";
if(isset($_POST['option']))
{
	echo "<div class='bigcontainer2'>";
	echo "<div class='bigmain2'>";

}

else {
	echo "<div class='bigcontainer'>";
	echo "<div class='bigmain'>";

}

echo "<table align=center style='width:50%'>";
  echo "<tr>";
	if(isset($_POST['option']))
	{
		  echo $extype;
			echo "<br><br>";
			// echo "Quartile based on: ".$quartile;
			// echo "<br><br>";
			// echo "Overall Correlation Coeeficient:".round($pearson, 3);
			echo "1st Quartile Correlation Coeeficient:".round($pearson1, 3);
			echo "<br><br>";
			echo "3rd Quartile Correlation Coeeficient:".round($pearson2, 3);
			echo "<br><br>";
			// echo "<td><img src='attempts_pro.png'></td>";
			echo "<td><img src='attempts_pro1.png'></td>";
			echo "<td><img src='attempts_pro2.png'></td>";
	}
	else
	{
		echo "Correlation Coeeficient:".round($pearson, 3);
		echo "<br><br>";
    echo "<td><img src='attempts_pro.png'></td>";
	}


  echo "</tr>";
echo "</table>";
?>


</div>
</div>

</body>
</html>
