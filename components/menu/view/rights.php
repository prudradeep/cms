<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Componenet:Auth
*View:		Rights
*Vars:    Usertypes, Data, Pager, Componenets, JSON Data, Drop
*/

if (! defined('PHINDART')) { die('Access denied'); }
?>
<style>
p{margin: 0;}
.arrow_up, .arrow_down{cursor: pointer;font-weight: bold;}
[type="checkbox"]+label {
   height: 16px;
 }
</style>
<div class="fixed-action-btn horizontal">
  <a class="btn-floating btn-large red waves-effect waves-light new_menu" data-target="add_menu">
    <i class="large material-icons">add</i>
  </a>
</div>
  <div id="add_menu" class="modal">
    <div class="modal-content">
      <h4 id="head_menu">New Menu</h4>
      <form action="<?=BASE.$this->component?>/rights/insert" method="post" id="newmenu">
        <input type="hidden" value="" name="id" id="edit_menu"/>
        <div class="row">
         <div class="input-field col s12">
            <select name="parent" id="parent" required>
              <option value='0'>Parent</option>
              <?php
              foreach ($data['drop'] as $key => $value) {
                echo "<option value='$value[id]'>$value[name]</option>";
              }
              ?>
            </select>
            <label for="parent">Parent</label>
         </div>
      </div>
      <div class="row margintop25">
         <div class="input-field col s4">
            <input id="display_name" type="text" name="display_name" required>
            <label for="display_name">Display Name</label>
         </div>
         <div class="input-field col s4">
            <input id="name" type="text" name="name" required>
            <label for="name">Short Name</label>
         </div>
         <div class="input-field col s4">
            <input id="link" type="text" name="link" class="autocomplete" required>
            <label for="link">Link</label>
         </div>
      </div>
      <div class="row">
         <div class="input-field col">
            <input type="checkbox" id="all_new" class="all_check" name="all"/>
            <label for="all_new">All</label>
         </div>
         <?php foreach ($data['usertypes'] as $k_ => $v_) {
             echo <<<HTML
<div class="input-field col">
<input type="checkbox" id="new_$v_[id]" class="all_new" name="$v_[id]"/>
<label for="new_$v_[id]">{$v_['name']}</label>
</div>
HTML;
          } ?>
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
<form class="container" action="<?=BASE.$this->component?>/rights/updater" method="post" id="updatemenu">
<h4>Menu Rights<button class="btn waves-effect waves-light airtel-red round-btn right" type="submit">Update
    <i class="material-icons left">done</i>
  </button></h4>
<table class="responsive-table striped">
  <thead>
    <tr>
        <th>Order</th>
        <th>Parent</th>
        <th>Display Name</th>
        <th>Short Name</th>
        <th>All</th>
        <?php
        foreach ($data['usertypes'] as $key => $value) {
           echo "<th>$value[name]</th>";
        }
        ?>
        <th>Options</th>
    </tr>
  </thead>
  <tbody>
      <?php
        $MaxOrders = [];
        foreach ($data['data'] as $key => $value) {
          $all='';
          $dis='';
          $MaxOrders[$value['parent']] = $value['_order'];
          if($value['user_access'][0]=="*"){
            $all='checked';
            $dis='disabled';
          }
          echo <<<HTML
<tr>
<td order="$value[_order]" parent="$value[parent]"><i class="material-icons arrow_up">expand_less</i><i class="material-icons arrow_down">expand_more</i><input name="$value[id][order]" type="hidden" value="$value[_order]"/></td>
<td>$value[parent]</td>
<td>$value[display_name]</td>
<td>$value[name]</td>
<td><p>
<input type="checkbox" id="$key$value[id]-1" data-name="$value[name]-all" data-parent="$value[parent]-all" class="all_check" name="$value[id]['all']" {$all}/>
<label for="$key$value[id]-1"></label>
</p></td>
HTML;
foreach ($data['usertypes'] as $k_ => $v_) {
   $check = in_array($v_['id'], $value['user_access'])?'checked':'';
   echo <<<HTML
<td>
<p>
<input type="checkbox" id="$key$value[id]$k_" class="$key$value[id]-1" data-name="$value[name]-$v_[name]" data-parent="$value[parent]-$v_[name]" name="$value[id][$v_[id]]" {$dis} {$check}/>
<label for="$key$value[id]$k_"></label>
</p>
</td>
HTML;
}
echo <<<HTML
<td>
<a class="btn-floating red waves-effect waves-light edit_menu" data-id="$value[id]" data-target="add_menu">
<i class="large material-icons">edit</i>
</a>
<a class="btn-floating teal waves-effect waves-light del_conf"  data-id="$value[id]" data-target="del_conf">
<i class="large material-icons">delete_forever</i>
</a>
</td>
</tr>
HTML;
}
        ?>
  </tbody>
</table>
<!-- Paging -->
<?=$data['pager'] ?>
<!-- Paging -->
</form>
<?php $this->theme->loadView('del_conf', ['action'=>BASE.$this->component.'/rights']); ?>
<script>
   $(document).ready(function(){   
    var data= <?=$data['json']?> 
     $('input.autocomplete').autocomplete({
      data: {<?php foreach ($data['comps'] as $key => $value) {
        $value = strtolower($value);
        echo "'$value':null,";
      }?>},
      limit: 20, // The max amount of results that can be shown at once. Default: Infinity.
      onAutocomplete: function(val) {
        // Callback function when value is autcompleted.
      },
      minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
    });
    $('.all_check').click(function(){
       id = $(this).attr('id');       
       if($(this).is(':checked'))
          $('.'+id).attr('disabled',true);
       else
          $('.'+id).attr('disabled',false);
    });
    $('#updatemenu').on('click', 'input', function(){
      name = $(this).data('name');
      that = this; 
      $('input[data-parent='+name+']').each(function(){       
        if(!$(that).is(':checked')){
          if($(this).is(':checked')){
            $(this).click();
          }
        }else if(!$(this).is(':checked')){
          $(this).click();
        }
       });
     });
    $('button[type="reset"]').click(function(){
      id = $('.all_check').attr('id');
      $('.'+id).attr('disabled',false);
    });
    $(".new_menu").click(function(){
      $('#head_menu').html('New Menu');
      $("#newmenu").attr('action', "<?=BASE.$this->component?>/rights/insert");
    });
    $(".edit_menu").click(function(){
      $('#head_menu').html('Edit Menu');
      $("#newmenu").attr('action', "<?=BASE.$this->component?>/rights/update");
      $('#edit_menu').val($(this).data('id'));
      that = $(this);
      $.each(data, function(i,v){
        if(v.id==$(that).data('id')){
          $('#parent').val($('#parent').find("option:contains('"+v.parent+"')").val());
          $('#name').val(v.name);
          $('#link').val(v.link);
          $('#display_name').val(v.display_name);
          if(v.user_access=='*')
            $('#all_new').click();
          else{
            $.each(v.user_access, function(i,v){
              $('#new_'+v).click();
            });
            $('select').material_select();
            Materialize.updateTextFields();
          }
          Materialize.updateTextFields();
        }
      });
    });
    maxOrders = <?=json_encode($MaxOrders)?>;
    $('.arrow_up').click(function(){
      preOrder = order = $(this).parent().attr('order');
      if(order>1){
        order = parseInt(order)-1;
        $(this).parent().children('input').val(order);
        $(this).parent().attr('order',order);
        $(this).parent().parent().prev().children('td:first-child').attr('order',preOrder);
        $(this).parent().parent().prev().children('td:first-child').children('input').val(preOrder);
        $(this).parent().parent().insertBefore($(this).parent().parent().prev());
      }
    });
    $('.arrow_down').click(function(){
      neOrder = order = $(this).parent().attr('order');
      if(order<maxOrders[$(this).parent().attr('parent')]){
        order = parseInt(order)+1;
        $(this).parent().children('input').val(order);
        $(this).parent().attr('order',order);
        $(this).parent().parent().next().children('td:first-child').attr('order',neOrder);
        $(this).parent().parent().next().children('td:first-child').children('input').val(neOrder);
        $(this).parent().parent().insertAfter($(this).parent().parent().next());
      }
    });
  });
</script>