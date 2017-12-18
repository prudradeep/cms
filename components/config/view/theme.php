<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Componenet:Cofig
*View:		Theme
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
            <input id="id" type="text" name="id" placeholder="Auto Generate" readonly>
            <label for="id">ID</label>
         </div>
         <div class="input-field col m6">
            <select name="type" id="type" required>
              <option value="">Select Type</option>
              <option value="global">Global Variable</option>
              <option value="site">Site Info</option>
              <option value="js_plugs">JS Plugin</option>
              <option value="font_plugs">Fonts</option>
              <option value="socket">Socket</option>
            </select>
            <label for="type">Type</label>
         </div>
      </div>
      <div class="row">
        <div class="input-field col m4">
            <input id="field" type="text" name="field" required>
            <label for="field">Field</label>
         </div>
         <div class="input-field col m4">
            <input id="value" type="text" name="value" required>
            <label for="value">Value</label>
         </div>
         <div class="input-field col m4">
            <select name="status" id="status" required>
              <option value="">Select Status</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
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
      $('#head_menu').html('New Settings');
      $('#<?=$data['id']?>').attr('readonly',false);
      $("#addEditForm").attr('action', "<?=$data['action']?>/insert/<?=$data['page']?>");
    });
    $(".update").click(function(){
      $('#head_menu').html('Edit Settings');
      $("#addEditForm").attr('action', "<?=$data['action']?>/update/<?=$data['page']?>");
      $('#<?=$data['id']?>').attr('readonly',true);
      $('#<?=$data['id']?>').val($(this).data('id'));
      that = $(this);
      $.each(data, function(i,v){
        if(v.<?=$data['id']?>==$(that).data('id')){
          $('#type').val(v.type);
          $('#field').val(v.field);
          $('#value').val(v.value);
          $('#status').val($('#status').find("option:contains('"+v.status+"')").val());
          $('select').material_select();
        }
      });
      Materialize.updateTextFields();
    });
  });
</script>