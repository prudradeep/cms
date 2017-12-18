<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Theme:		Materialize
*View:		Master View
*Vars:		action: Form Search Action
*/

if (! defined('PHINDART')) { die('Access denied'); }
?>
<div class="fixed-action-btn vertical">
    <a class="btn-floating btn-large red">
      <i class="large material-icons">menu</i>
    </a>
    <ul>
      <li><a class="btn-floating blue waves-effect waves-light upload" data-target="file_upload"><i class="material-icons">file_upload</i></a></li>
      <li><a class="btn-floating red waves-effect waves-light insert" data-target="add_edit"><i class="material-icons">add</i></a></li>
      <li><a class="btn-floating green waves-effect waves-light upload" href="<?=BASE.$this->component.$data['url']?>/download"><i class="material-icons">file_download</i></a></li>
    </ul>
</div>
<div class="container">
	<h4><?=$data['heading']?></h4>
	<?php  $this->theme->loadView('search', ['action'=>BASE.$this->component.$data['url'], 'options'=>$data['tableHeads']]); ?>
	<table class="responsive-table striped">
	  <thead>
	    <tr>
	    	<?php
	        foreach ($data['tableHeads'] as $value) {
	           echo "<th>$value</th>";
	        }
	        ?>	        
	        <th>Options</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
        foreach ($data['data'] as $key => $value) {
        	echo "<tr>";
        	foreach ($value as $k => $v) {
        		echo "<td>$v</td>";
        	}
        	$id = $value[$data['id']];
        	echo <<<HTML
<td>
<a class="btn-floating red waves-effect waves-light update" data-id="$id" data-target="add_edit">
<i class="large material-icons">edit</i>
</a>
<a class="btn-floating teal waves-effect waves-light del_conf"  data-id="$id" data-target="del_conf">
<i class="large material-icons">delete_forever</i>
</a>
</td>
HTML;
        	echo "</tr>";
		}
        ?>
	  </tbody>
	</table>
	<!-- Pagination -->
	<?= isset($data['pager'])?$data['pager']:''?>
</div>
<?php 
	$this->theme->loadView('file_upload', ['action'=>BASE.$this->component.$data['url'], 'page'=>$data['page']]);
	$this->theme->loadView('del_conf', ['action'=>BASE.$this->component.$data['url'], 'page'=>$data['page']]); 
?>