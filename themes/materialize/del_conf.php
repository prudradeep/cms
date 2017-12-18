<?php
/*
*Author:  Pradeep Rajput
*Email:   prithviraj.rudraksh@gmail.com 
*Website: ----------
*Theme:   Materialize
*View:    Search
*Vars:    action: Form Remove Action
*/

if (! defined('PHINDART')) { die('Access denied'); }
?>
<form id="del_conf" class="modal" method="post" action="<?=$data['action']?>/remove/<?=@$data['page']?>">
  <div class="modal-content">
    <h4>Confirm delete?</h4>
    <input type="hidden" value="" name="id" id="del_id"/>
  </div>
  <div class="modal-footer">
    <span class="modal-action modal-close waves-effect waves-green btn-flat">Close</span>
    <button class="waves-effect waves-green btn-flat" type="submit">Confirm
    </button>
  </div>
</form>
<script>
   $(document).ready(function(){
    $('#del_conf').modal({dismissible:false,endingTop: '35%'});
    $(".del_conf").click(function(){
      $('#del_id').val($(this).data('id'));
    });
   });
</script>