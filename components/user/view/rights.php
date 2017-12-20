<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Componenet:User
*View:		Rights
*Vars:    Usertypes, Comps, Data
*/

if (! defined('PHINDART')) { die('Access denied'); }
$url = BASE.$this->component;
?>
<style>
p{margin: 0;}
[type="checkbox"]+label {
   height: 16px;
 }
</style>
<div class="fixed-action-btn">
    <a class="btn-floating blue waves-effect waves-light upload" data-target="file_upload"><i class="material-icons">file_upload</i></a>
</div>
<?php 
  $this->theme->loadView('file_upload', ['action'=>BASE.$this->component.$data['url'], 'file'=>'zip', 'format'=>false]);
?>
<form class="container" action="<?=BASE.$this->component.$data['url']?>/submit" method="post">
<h4>Component Rights
  <button class="btn waves-effect waves-light airtel-red round-btn right" type="submit">Save
    <i class="material-icons left">done</i>
  </button>
</h4>
<table class="responsive-table striped">
  <thead>
    <tr>
        <th>Components/Controller</th>
        <th>All</th>
        <?php
        foreach ($data['usertypes'] as $key => $value) {
           echo "<th>$value[name]</th>";
        }
        ?>
        <th>Status</th>
        <th>-</th>
    </tr>
  </thead>
  <tbody>
      <?php
        $ucs = count($data['usertypes'])+2;
        foreach ($data['comps'] as $key => $value) {
          $z=0;
          foreach ($value as $k => $v) {
              if($k==="install" || $k==="uninstall")
                continue;
              $all='';
              $dis='';
              $cs = count($value)-2;
              if($key==$k)
                $name = $key;
              else
                $name = $key."/".$v['name'];
              if(isset($data['data']["$name"]) && $data['data']["$name"][0]=="*"){
                $all='checked';
                $dis='disabled';
             }
            echo <<<HTML
<tr><td>$name</td>
HTML;
if($value['uninstall']){
  echo <<<HTML
<td>
<p>
<input type="checkbox" id="$key$k-1" class="all_check" name="{$name}['all']" {$all}/>
<label for="$key$k-1"></label>
</p>
</td>
HTML;
  foreach ($data['usertypes'] as $k_ => $v_) {
    $check = (isset($data['data']["$name"]) && in_array($v_['id'], $data['data']["$name"]))?'checked':'';
    echo <<<HTML
<td>
<p>
<input type="checkbox" id="$key$k$k_" class="$key$k-1" name="{$name}[$v_[id]]" {$dis} {$check}/>
<label for="$key$k$k_"></label>
</p>
</td>
HTML;
  }
}else if($k==0){
  echo <<<HTML
<td rowspan='{$cs}' colspan='{$ucs}' class="center-align">
<a href="{$url}/rights/install/{$value['install']->comp_name}" class="btn green waves-effect waves-light"> Install
<i class="large material-icons left">library_add</i>
</a>
<a href="{$url}/rights/remove/{$value['install']->comp_name}" class="btn red waves-effect waves-light"> Remove
<i class="large material-icons left">delete_forever</i>
</a>
</td>
HTML;
}
if(isset($value['uninstall']->removable)){
echo "<td>";
$status_check = (isset($v['status']) && $v['status']===1)?'checked':'';
if($value['uninstall']->removable){
  echo <<<HTML
<div class="switch">
    <label>
      Disabled
      <input type="checkbox" name="{$name}[status]" {$status_check}/>
      <span class="lever"></span>
      Enabled
    </label>
</div>
HTML;
}else{
  echo <<<HTML
<input type="hidden" name="{$name}[status]" value="on"/>
<div class="switch">
    <label>
      Disabled
      <input type="checkbox" disabled {$status_check}/>
      <span class="lever"></span>
      Enabled
    </label>
</div>
HTML;
}
echo "</td>";
}
if($value['uninstall'] && $z==0){
  if(!$value['uninstall']->removable){
    echo <<<HTML
<td rowspan='$cs' class='left-align'>
<a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="{$value['uninstall']->name}, Ver:{$value['uninstall']->version}"><i class="material-icons">info_outline</i></a>
</td>
HTML;
  }else{
    echo <<<HTML
<td rowspan='{$cs}' class='left-align'>
<a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="{$value['uninstall']->name}, Ver:{$value['uninstall']->version}"><i class="material-icons">info_outline</i></a>
<a href="{$url}/rights/uninstall/{$value['uninstall']->comp_name}" >
<i class="red-text material-icons">delete</i>
</a>
</td>
HTML;
  }
}else if($value['install'] && $z==0){
  echo <<<HTML
<td rowspan='{$cs}' class='center-align'>
{$value['install']->name}<br />
Ver: {$value['install']->version}
</td>
HTML;
}
echo "</tr>";
          $z++;
          }
        }
        ?>
  </tbody>
</table>
</form>
<script>
   $(document).ready(function(){
      $('.all_check').click(function(){
         id = $(this).attr('id');
         if($(this).is(':checked'))
            $('.'+id).attr('disabled',true);
         else
            $('.'+id).attr('disabled',false);
      });
   });
</script>