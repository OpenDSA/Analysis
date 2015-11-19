<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ODSA Student Activity Analysis</title>
	<link rel="stylesheet" href="style.css"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="disable_radio.js"></script>
</head>

<body>
	<div class="container">
	<div class="main">

		<script>
				jQuery(function(){
		    var max = 2;
		    var checkboxes = $('input.single-checkbox');

		    checkboxes.change(function(){
		        var current = checkboxes.filter(':checked').length;
		        checkboxes.filter(':not(:checked)').prop('disabled', current >= max);
		    });
		});

		</script>

<FORM name ="form1" method ="post" action ="analysis.php" id="form">
	<p>
		Choose your exercise<br/>
		<!-- <input type="checkbox" name="attempts_pro" value="A" />Attempts until Proficiency Earned<br /> -->
		<input type="radio" name="exercise" value="ka" />Khan Academy Exercise<br />
		<input type="radio" name="exercise" value="pe" />Proficiency Exercise<br />
	</p>

			<label>Specific KA exercise types: </label>
			<input type="radio" name="first" value="Enable" id="enable">
			<span>Yes</span>
			<input type="radio" name="first" value="Disable" id="disable" checked>
			<span>No</span>

			<br>
			<input type="radio" name="second" class="second" value="summ">
			<span class="wrap">Summary Exercise</span>
			<br><input type="radio" name="second" class="second" value="pro">
			<span class="wrap">Proficiency Exercise</span>
			<br><input type="radio" name="second" class="second" value="prog">
			<span class="wrap">Programming Exercise</span>


<br>

	<p>
		Choose your parameters. Select two parameters<br/>
		<!-- <input type="checkbox" name="attempts_pro" value="A" />Attempts until Proficiency Earned<br /> -->
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="attempts_pro" />Attempts until Proficiency Earned<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="time_pro" />Time until Proficiency Earned<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="hints_pro" />Hints until Proficiency Earned<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="attempts_aft" />Attempts after Proficiency Earned<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="time_aft" />Time after Proficiency Earned<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="hints_aft" />Hints after Proficiency Earned<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="stest" />Student Exam Grade<br />
	</p>

	<p>
		Student performance analysis by quartile<br />
	<input type="checkbox" name="option" value="Enable">
	<span>Yes</span>
	<br>
		<input type="radio" name="quartile" value="attempts_pro" />Attempts until Proficiency Earned<br />
		<input type="radio" name="quartile" value="time_pro" />Time until Proficiency Earned<br />
		<input type="radio" name="quartile" value="hints_pro" />Hints until Proficiency Earned<br />
		<input type="radio" name="quartile" value="attempts_aft" />Attempts after Proficiency Earned<br />
		<input type="radio" name="quartile" value="time_aft" />Time after Proficiency Earned<br />
		<input type="radio" name="quartile" value="hints_aft" />Hints after Proficiency Earned<br />
		<input type="radio" name="quartile" value="stest" />Student Exam Grade<br />
	</p>
	<input type="submit" name="formSubmit" value="Submit" />
</FORM>

</div>
</div>



</body>
</html>
