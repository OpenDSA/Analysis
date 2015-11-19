<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>ODSA Student Activity Analysis</title>
	<link rel="stylesheet" href="style.css"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script> -->
	<!-- <script src="disable_radio2.js"></script> -->
</head>

<body>
	<div class="container">
	<div class="main">

		<script>
				jQuery(function(){
		    var max = 3;
		    var checkboxes = $('input.multi-checkbox');

		    checkboxes.change(function(){
		        var current = checkboxes.filter(':checked').length;
		        checkboxes.filter(':not(:checked)').prop('disabled', current >= max);
		    });
		});

		jQuery(function(){
		var max = 4;
		var checkboxes = $('input.multi2-checkbox');

		checkboxes.change(function(){
				var current = checkboxes.filter(':checked').length;
				checkboxes.filter(':not(:checked)').prop('disabled', current >= max);
		});
		});

		jQuery(function(){
		var max = 1;
		var checkboxes = $('input.msingle-checkbox');

		checkboxes.change(function(){
				var current = checkboxes.filter(':checked').length;
				checkboxes.filter(':not(:checked)').prop('disabled', current >= max);
		});
});

		jQuery(function(){
		var max = 1;
		var checkboxes = $('input.single-checkbox');

		checkboxes.change(function(){
				var current = checkboxes.filter(':checked').length;
				checkboxes.filter(':not(:checked)').prop('disabled', current >= max);
		});
});

$(document).ready(function() {
	 $('input.single-choice').click(function() {
			 if($(this).attr('id') == 'gtype-one') {
						$('#show-one').show();
			 }

			 else {
						$('#show-one').hide();
			 }

			 if($(this).attr('id') == 'gtype-two') {
						$('#show-two').show();
			 }

			 else {
						$('#show-two').hide();
			 }

			 if($(this).attr('id') == 'gtype-three') {
						$('#show-three').show();
			 }

			 else {
						$('#show-three').hide();
			 }

			 if($(this).attr('id') == 'gtype-four') {
						$('#show-four').show();
			 }

			 else {
						$('#show-four').hide();
			 }

		 });


		 			 $('#type-three').change(function () {

		 					$('#show-three').fadeToggle();
		 			});

});




		</script>

<FORM name ="form1" method ="post" action ="modum.php" id="form">
	<p>
		Choose your exercise<br/>
		<input class="single-choice" id='gtype-one' type="radio" name="gtype" value="se" />Single Exercise Analysis Graph<br />
		<input class="single-choice" id='gtype-two' type="radio" name="gtype" value="mp" />Multiple Parameter Analysis Graph<br />
		<input class="single-choice" id='gtype-four' type="radio" name="gtype" value="sp" />Single Parameter Analysis Graph<br />
		<input class="single-choice" id='gtype-three' type="radio" name="gtype" value="sc" />Scatter Graph<br />
	</p>

<div id='show-one' style='display:none'>
	<p>
		Choose your parameters. Select FOUR parameters<br/>
		<input class="multi2-checkbox" type="checkbox" name="parameters[]" value="attempts" />Total Attempts<br />
		<input class="multi2-checkbox" type="checkbox" name="parameters[]" value="time2" />Total Time<br />
		<input class="multi2-checkbox" type="checkbox" name="parameters[]" value="hints" />Total Hints<br />
		<input class="multi2-checkbox" type="checkbox" name="parameters[]" value="attempts_aft" />Attempts after Proficiency Earned<br />
		<input class="multi2-checkbox" type="checkbox" name="parameters[]" value="time_aft2" />Time after Proficiency Earned<br />
		<input class="multi2-checkbox" type="checkbox" name="parameters[]" value="hints_aft" />Hints after Proficiency Earned<br />
Excercise ID to analyze: <input type="text" name="ex" value="" maxlength="4" size="3"><br>
	</p>

</div>

<div id='show-two' style='display:none'>
	<p>
		Choose your parameters. Select THREE parameters<br/>
		<input class="multi-checkbox" type="checkbox" name="parameters[]" value="attempts" />Total Attempts<br />
		<input class="multi-checkbox" type="checkbox" name="parameters[]" value="time2" />Total Time<br />
		<input class="multi-checkbox" type="checkbox" name="parameters[]" value="hints" />Total Hints<br />
		<input class="multi-checkbox" type="checkbox" name="parameters[]" value="attempts_aft" />Attempts after Proficiency Earned<br />
		<input class="multi-checkbox" type="checkbox" name="parameters[]" value="time_aft2" />Time after Proficiency Earned<br />
		<input class="multi-checkbox" type="checkbox" name="parameters[]" value="hints_aft" />Hints after Proficiency Earned<br />
	</p>
	Excercise ID to compare: #1 <input type="text" name="exercise[]" maxlength="4" size="3"><br>
	Excercise ID to compare: #2 <input type="text" name="exercise[]" maxlength="4" size="3"><br>
	Excercise ID to compare: #3 <input type="text" name="exercise[]" maxlength="4" size="3"><br>
	Excercise ID to compare: #4 <input type="text" name="exercise[]" maxlength="4" size="3"><br>
	Excercise ID to compare: #5 <input type="text" name="exercise[]" maxlength="4" size="3"><br>
	<!-- Excercise ID to compare: #6 <input type="text" name="exercise[]" maxlength="4" size="3"><br> -->
	<!-- Excercise ID to compare: #7 <input type="text" name="exercise[]" maxlength="4" size="3"><br> -->

</div>

<div id='show-four' style='display:none'>
	<p>
		Choose your parameters. Select ONE parameters<br/>
		<input class="msingle-checkbox" type="checkbox" name="parameters" value="attempts" />Total Attempts<br />
		<input class="msingle-checkbox" type="checkbox" name="parameters" value="time2" />Total Time<br />
		<input class="msingle-checkbox" type="checkbox" name="parameters" value="hints" />Total Hints<br />
		<input class="msingle-checkbox" type="checkbox" name="parameters" value="correct_ratio" />Corrects Ratio<br />
		<input class="msingle-checkbox" type="checkbox" name="parameters" value="attempts_aft" />Attempts after Proficiency Earned<br />
		<input class="msingle-checkbox" type="checkbox" name="parameters" value="time_aft2" />Time after Proficiency Earned<br />
		<input class="msingle-checkbox" type="checkbox" name="parameters" value="hints_aft" />Hints after Proficiency Earned<br />
	</p>
	Excercise ID to compare: #1 <input type="text" name="exercise2[]" maxlength="4" size="3"><br>
	Excercise ID to compare: #2 <input type="text" name="exercise2[]" maxlength="4" size="3"><br>
	Excercise ID to compare: #3 <input type="text" name="exercise2[]" maxlength="4" size="3"><br>
	Excercise ID to compare: #4 <input type="text" name="exercise2[]" maxlength="4" size="3"><br>
	Excercise ID to compare: #5 <input type="text" name="exercise2[]" maxlength="4" size="3"><br>
	<!-- Excercise ID to compare: #6 <input type="text" name="exercise[]" maxlength="4" size="3"><br> -->
	<!-- Excercise ID to compare: #7 <input type="text" name="exercise[]" maxlength="4" size="3"><br> -->

</div>

<div id='show-three' style='display:none'>
	<p>
		Choose your parameters. Select ONE parameters<br/>
		<input class="single-checkbox" type="checkbox" name="parameters" value="attempts_pro" />Attempts until Proficiency Earned<br />
		<input class="single-checkbox" type="checkbox" name="parameters" value="time_pro" />Time until Proficiency Earned<br />
		<input class="single-checkbox" type="checkbox" name="parameters" value="hints_pro" />Hints until Proficiency Earned<br />
		<input class="single-checkbox" type="checkbox" name="parameters" value="attempts_aft" />Attempts after Proficiency Earned<br />
		<input class="single-checkbox" type="checkbox" name="parameters" value="time_aft" />Time after Proficiency Earned<br />
		<input class="single-checkbox" type="checkbox" name="parameters" value="hints_aft" />Hints after Proficiency Earned<br />
	</p>
	Excercise ID to compare: #1 <input type="text" name="firstex" maxlength="4" size="3"><br>
	Excercise ID to compare: #2 <input type="text" name="secondex" maxlength="4" size="3"><br>
</div>



<!-- <p>
	Student performance analysis by quartile<br />
<input id='type-three'type="checkbox" name="option" value="Enable">
<span>Yes</span>
<div id='show-three' class= 'show-three' style='display:none'>
	<input type="radio" name="quartile" value="attempts_pro" />Attempts until Proficiency Earned<br />
	<input type="radio" name="quartile" value="time_pro" />Time until Proficiency Earned<br />
	<input type="radio" name="quartile" value="hints_pro" />Hints until Proficiency Earned<br />
	<input type="radio" name="quartile" value="attempts_aft" />Attempts after Proficiency Earned<br />
	<input type="radio" name="quartile" value="time_aft" />Time after Proficiency Earned<br />
	<input type="radio" name="quartile" value="hints_aft" />Hints after Proficiency Earned<br />
	<input type="radio" name="quartile" value="stest" />Student Exam Grade<br />
</div>
</p> -->
	<input type="submit" name="formSubmit" value="Submit" />
</FORM>

</div>
</div>



</body>
</html>
