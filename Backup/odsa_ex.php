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

		$(document).ready(function() {
		   $('input.single-radio').click(function() {
		       if($(this).attr('id') == 'type-one') {
		            $('#show-me').show();
		       }

		       else {
		            $('#show-me').hide();
		       }
		   });

			 $('#type-two').change(function () {

					$('#show-two').fadeToggle();
			});

				  $('#type-three').change(function () {

						 $('#show-three').fadeToggle();
		     });

		});

		</script>

<FORM name ="form1" method ="post" action ="analysis.php" id="form">
	<p>
		Choose your exercise<br/>
		<input class="single-radio" id='type-one' type="radio" name="exercise" value="ka" />Khan Academy Exercise<br />
		<input class="single-radio" type="radio" name="exercise" value="pe" />Proficiency Exercise<br />
	</p>

		<div id='show-me' style='display:none'>
			<label>Specific KA exercise types: </label>
			<input class="single-radio2" id='type-two'type="checkbox" name="first" value="Enable">
			<span>Yes</span>

			<div id='show-two' style='display:none'>
						<input type="radio" name="second" value="summ">
						<span class="wrap">Summary Exercise</span>
						<br><input type="radio" name="second" value="pro">
						<span class="wrap">Proficiency Exercise</span>
						<br><input type="radio" name="second" value="prog">
						<span class="wrap">Programming Exercise</span>
			</div>
		</div>

	<p>
		Choose your parameters. Select TWO parameters<br/>
		<!-- <input type="checkbox" name="attempts_pro" value="A" />Attempts until Proficiency Earned<br /> -->
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="total_attempts" />Total Attempts<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="total_time" />Total Time<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="total_hints" />Total Hints<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="time_ex" />Total Exercise Time<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="time_hint" />Total Hints Time<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="incorrect_ratio" />Incorrect Exercise Question Ratio<br />
		<input class="single-checkbox" type="checkbox" name="parameters[]" value="gaming" />Student Gaming<br />
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
	<input class="single-radio3" id='type-three'type="checkbox" name="option" value="Enable">
	<span>Yes</span>
	<div id='show-three' class= 'show-three' style='display:none'>
		<input type="radio" name="quartile" value="total_attempts" />Total Attempts<br />
		<input type="radio" name="quartile" value="total_time" />Total Time<br />
		<input type="radio" name="quartile" value="total_hints" />Total Hints<br />
		<input type="radio" name="quartile" value="time_ex" />Total Exercise Time<br />
		<input type="radio" name="quartile" value="time_hint" />Total Hints Time<br />
		<input type="radio" name="quartile" value="incorrect_ratio" />Incorrect Exercise Question Ratio<br />
		<input type="radio" name="quartile" value="gaming" />Student Gaming<br />
		<input type="radio" name="quartile" value="attempts_pro" />Attempts until Proficiency Earned<br />
		<input type="radio" name="quartile" value="time_pro" />Time until Proficiency Earned<br />
		<input type="radio" name="quartile" value="hints_pro" />Hints until Proficiency Earned<br />
		<input type="radio" name="quartile" value="attempts_aft" />Attempts after Proficiency Earned<br />
		<input type="radio" name="quartile" value="time_aft" />Time after Proficiency Earned<br />
		<input type="radio" name="quartile" value="hints_aft" />Hints after Proficiency Earned<br />
		<input type="radio" name="quartile" value="stest" />Student Exam Grade<br />
	</div>
	</p>
	<input type="submit" name="formSubmit" value="Submit" />
</FORM>

</div>
</div>



</body>
</html>
