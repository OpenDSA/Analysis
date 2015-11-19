<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ODSA Student Activity Analysis</title>
	<link rel="stylesheet" href="style.css"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="disable_radio.js"></script>
</head>

<body>
	<div class="bigcontainer">
	<div class="bigmain">

<?php

include("../class/pData.class.php");
include("../class/pDraw.class.php");
include("../class/pImage.class.php");
include("../class/pScatter.class.php");
include ("../db_connect.php");


	if(isset($_POST['formSubmit']))
    {


			$exercise = $_POST['exercise'];
			$parameter = $_POST['parameters'];
			$quartile = $_POST['quartile'];
			$type = $_POST['second'];
			$quartile = trim(htmlspecialchars_decode($quartile));

			if(empty($exercise)||empty($parameter))
			{
				echo("<p>HEY! You did NOT choose all necessary parameters.</p>\n");
			}
			else
			{
				// if(isset($_POST['formWheelchair']) && $_POST['formWheelchair'] == 'Yes')

				$n = count($exercise);

				if ($n==1)
				{
					if( $exercise == 'ka')
					{

						if ($exercise == 'ka' && $type == "summ") {
							if(isset($_POST['option']))
							{
								$start = mysql_query("SELECT * FROM nuser_summ where stest >6.3 order by $quartile asc limit 34");
								$start2 = mysql_query("SELECT * FROM nuser_summ where stest >6.3 order by $quartile desc limit 34");
							}
							else
							{
								$start = mysql_query("SELECT * FROM nuser_summ where stest >6.3");
							}
								$extype = "KA Summary Exercise";
						}

						elseif ($exercise == 'ka' && $type == "pro") {
							if(isset($_POST['option']))
							{
								$start = mysql_query("SELECT * FROM nuser_pro where stest >6.3 order by $quartile asc limit 34");
								$start2 = mysql_query("SELECT * FROM nuser_pro where stest >6.3 order by $quartile desc limit 34");
							}
							else
							{
								$start = mysql_query("SELECT * FROM nuser_pro where stest >6.3");
							}
								$extype = "KA Proficiency Exercise";
						}

						elseif ($exercise == 'ka' && $type == "prog") {
							if(isset($_POST['option']))
							{
								$start = mysql_query("SELECT * FROM nuser_prog where stest >6.3 order by $quartile asc limit 34");
								$start2 = mysql_query("SELECT * FROM nuser_prog where stest >6.3 order by $quartile desc limit 34");
							}
							else
							{
								$start = mysql_query("SELECT * FROM nuser_prog where stest >6.3");
							}
								$extype = "KA Programming Exercise";
						}
						else {
							if(isset($_POST['option']))
							{
								$start = mysql_query("SELECT * FROM nuser where stest >6.3 order by $quartile asc limit 34");
								$start2 = mysql_query("SELECT * FROM nuser where stest >6.3 order by $quartile desc limit 34");
							}
							else
							{
								$start = mysql_query("SELECT * FROM nuser where stest >6.3");
							}
								$extype = "KA Exercise";
						}
						// // include ("graph.php");
						// // $start = mysql_query("SELECT * FROM nuser where stest >6.3");
					}

					else {

						if(isset($_POST['option']))
						{
							$start = mysql_query("SELECT * FROM nuser_p where stest >6.3 order by $quartile asc limit 34");
							$start2 = mysql_query("SELECT * FROM nuser_p where stest >6.3 order by $quartile desc limit 34");
						}
						else
						{
							$start = mysql_query("SELECT * FROM nuser_p where stest >6.3");
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

							if ($parameter == "attempts_pro") {
								$xaxis = "Attempts until Proficiency Earned";
							}
							elseif ($parameter == "time_pro") {
									 $xaxis = "Time until Proficiency Earned";
							}
							elseif ($parameter == "hints_pro") {
									$xaxis = "Hints until Proficiency Earned";
							}
							elseif ($parameter == "attempts_aft") {
								$xaxis = "Attempts after Proficiency Earned";
							}
							elseif ($parameter == "time_aft") {
									$xaxis = "Time after Proficiency Earned";
							}
							elseif ($parameter == "hints_aft") {
									$xaxis = "Hints after Proficiency Earned";
							}

					}

				else
				{
					$comparison = '1';
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
$atp = trim(htmlspecialchars_decode($row[$parameter]));
// round($atp, 3);
$st=$row["stest"];
// echo $atp;
// echo "<br>";
$xsquare = pow($atp,2);
$sumx= $sumx+$atp;
$sumxsquare = $sumxsquare+$xsquare;

$myData->addPoints($atp,"Probe 1");

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


// for ($i=0;$i<=360;$i=$i+10)
// { $myData->addPoints(rand(1,2)*10+rand(0,$i),"Probe 2"); }

if(isset($_POST['option']))
{

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
	$atp2 = trim(htmlspecialchars_decode($row[$parameter]));

	$st=$row["stest"];


	$xsquare = pow($atp2,2);
	$sumx= $sumx+$atp2;
	$sumxsquare = $sumxsquare+$xsquare;

	$myData->addPoints($atp2,"Probe 2");

	$ysquare = pow($st,2);
	$sumy= $sumy+$st;
	$sumysquare = $sumysquare+$ysquare;
	$xy = $atp2*$st;
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


}


$myData->setAxisName(0,"$xaxis");
$myData->setAxisXY(0,AXIS_X);
$myData->setAxisPosition(0,AXIS_POSITION_TOP);

/* Create the Y axis and the binded series */
// for ($i=0;$i<=360;$i=$i+10)
// { $myData->addPoints($i,"Probe 3"); }

//Grades
$grade = mysql_query("SELECT stest FROM nuser_p where stest >6.3");
while($row = mysql_fetch_assoc($grade)) {
		$st=$row["stest"];
$myData->addPoints($st*25,"Probe 3");
}


$myData->setSerieOnAxis("Probe 3",1);
$myData->setAxisName(1,"Student Exam Score");
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
echo "<table style='width:50%'>";
  echo "<tr>";
	if(isset($_POST['option']))
	{
			echo "1st Quartile Correlation Coeeficient:".round($pearson, 3);
			echo "<br><br>";
			echo "3rd Quartile Correlation Coeeficient:".round($pearson2, 3);
	}
	else
	{
		echo "Correlation Coeeficient:".round($pearson, 3);
	}

		echo "<br><br>";
    echo "<td><img src='attempts_pro.png'></td>";
  echo "</tr>";
echo "</table>";
?>


	</div>
	</div>

	</body>
	</html>
