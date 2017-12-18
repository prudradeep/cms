<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Theme:		Materialize
*View:		Footer
*/

if (! defined('PHINDART')) { die('Access denied'); }
?>
	<script>
		$(document).ready(function(){
			$('.modal').modal({dismissible:false});
			$('select').material_select();
			$('.datepicker').pickadate({
			    selectMonths: true, // Creates a dropdown to control month
			    selectYears: 15, // Creates a dropdown of 15 years to control year,
			    today: 'Today',
			    clear: 'Clear',
			    close: 'Ok',
			    closeOnSelect: false, // Close upon selecting a date
			    formatSubmit: 'yyyy-mm-dd',
			    format: 'yyyy-mm-dd',
			    hiddenSuffix: ''
			});			
		});
	</script>
  </body>
</html>