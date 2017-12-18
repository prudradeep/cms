<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Componenet:Auth
*View:		Index
*/

if (! defined('PHINDART')) { die('Access denied'); }
?>
<style>
body{
   background: url(<?=BASE.COMP_PATH.'/'.$this->component?>/assets/images/background.jpg) no-repeat bottom right;
   background-size: auto 100%;
}
.card{
	width: 28em;
	box-shadow: none;
   padding: 1em;
}
.container{height: 100%;width:90%;}
.conts{position: relative;}
.card .card-content {
    padding: 0;
    border-radius: 0;
}
.input-field.col label {
    width: 100%;
}
label.invalid.inact.active{
   top: 60px;
}
.tabs{
   overflow-x: hidden;
}
</style>
<div class="container valign-wrapper">
   <div class="card">
      <div class="card-content">
         <h3 class="center-align"><?=LOGO ?></h3>
      </div>
      <div class="card-tabs">
         <ul class="tabs tabs-fixed-width">
            <li class="tab"><a class="active" href="#login">Login</a></li>
            <li class="tab"><a href="#register">Register</a></li>
         </ul>
      </div>
      <div class="card-content">
         <div id="login">
            <div class="row">
               <form class="col s12" action="#" method="post" id="login_form">
                  <div class="row margintop25">
                     <div class="input-field col s12">
                        <input id="username" type="text" name="username" required>
                        <label for="username">Username</label>
                     </div>
                  </div>
                  <div class="row">
                     <div class="input-field col s12">
                        <input id="password" type="password" name="password" required>
                        <label for="password">Password</label>
                     </div>
                  </div>
                  <div class="row">
                     <div class="input-field col s12">
                        <button class="btn airtel-red round-btn" type="submit">Login
                        </button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
$(document).ready(function() {
   $('#login_form').submit(function(evt){
      evt.preventDefault();
      var that = this;
      $.ajax({
         url:"<?=BASE?>api/auth/login",
         method:'post',
         dataType: 'json',
         data: {password: $('#password').val(), username:$('#username').val()},
         success: function(a){
            console.info(a);
            if(!a){
               Materialize.toast('Invalid username or password!', 3000, 'rounded airtel-red');
               $('#username').focus();
            }else if(a.message != undefined){
               Materialize.toast(a.message, 3000, 'rounded airtel-red');
            }else{
               window.location.href="<?=BASE?>"+a.home;
            }
         }
      });
   });
  });
</script>