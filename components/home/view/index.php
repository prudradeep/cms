<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Componenet:Home
*View:		Index
*/

if (! defined('PHINDART')) { die('Access denied'); }
?>
<div class="container">
<div class="row">
 <div class="input-field col s6">
 	<?=$this->component('mobile','mobile','main', ['head'=>'Recommendation', 'action'=>'#recommendation']);?>
 </div>
 <div class="input-field col s6">
 	<?=$this->component('mobile','mobile','main', ['head'=>'Decision Tree', 'action'=>'#dtree']);?>
 </div>
</div>
</div>