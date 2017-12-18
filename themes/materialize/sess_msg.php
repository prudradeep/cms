<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Theme:		Materialize
*View:		Session Messenger
*/

if (! defined('PHINDART')) { die('Access denied'); }
?>
<?php if(isset($_SESSION[SESSION_MSG])){ ?>
<script>
$(document).ready(function(){
	Materialize.toast("<?=$_SESSION[SESSION_MSG]?>", 3000, 'rounded airtel-red');
});
</script>
<?php  unset($_SESSION[SESSION_MSG]); } ?>