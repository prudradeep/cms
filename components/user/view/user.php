<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Componenet:User
*View:		user
*Vars:		id: Edit Row ID, action: Insert/Update action
*/

if (! defined('PHINDART')) { die('Access denied'); }
?>
<div id="add_edit" class="modal">
    <div class="modal-content">
      <h4 id="head_menu"></h4>
      <form action="" method="post" id="addEditForm">
      <div class="row">
         <div class="input-field col m6">
            <input id="username" type="text" name="username" readonly required>
            <label for="username">Username</label>
         </div>
         <div class="input-field col m6">
            <input id="password" type="text" name="password" required>
            <label for="password">Sales Channel ID</label>
         </div>
      </div>
      <div class="row">
        <div class="input-field col m6">
            <select name="usertype" id="usertype" required>
              <option value="">Select Usertype</option>
              <?php
              foreach ($data['usertypes'] as $key => $value) {
                echo "<option value='$value[id]'>$value[name]</option>";
              }
              ?>
            </select>
            <label for="usertype">Usertype</label>
         </div>
         <div class="input-field col m6">
            <select name="status" id="status" required>
              <option value="">Select Status</option>
              <option value="1">Active</option>
              <option value="0">Deleted</option>
            </select>
            <label for="status">Status</label>
         </div>
      </div>
      <div class="row">
         <div class="input-field col s12">
            <button class="btn waves-effect waves-light airtel-red round-btn" type="submit">Submit
            </button>
            <button class="modal-action modal-close waves-effect waves-green btn round-btn airtel-red" type="reset">Close
            </button>
         </div>
      </div>
      </form>
    </div>
</div>
<script>
   $(document).ready(function(){
   	var data = <?=$data['json']?>;
    $(".insert").click(function(){
      $('#head_menu').html('New User');
      $('#<?=$data['id']?>').attr('readonly',false);
      $("#addEditForm").attr('action', "<?=$data['action']?>/insert/<?=$data['page']?>");
    });
    $(".update").click(function(){
      $('#head_menu').html('Edit User');
      $("#addEditForm").attr('action', "<?=$data['action']?>/update/<?=$data['page']?>");
      $('#<?=$data['id']?>').attr('readonly',true);
      $('#<?=$data['id']?>').val($(this).data('id'));
       that = $(this);
      $.each(data, function(i,v){
        if(v.<?=$data['id']?>==$(that).data('id')){
          $('#password').val(v.password);
          $('#usertype').val(v.usertype);
          $('#status').val(v.status);
          $('select').material_select();
        }
      });
      Materialize.updateTextFields();
    });
  });
</script>