<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Theme:		Materialize
*View:		Search
*Vars:		action: Form Search Action, options: Select Dropdown Options
*/

if (! defined('PHINDART')) { die('Access denied'); }
$where=['Eq'=>'=', 'Gt'=>'>', 'Lt'=>'<', 'Gteq'=>'>=', 'Lteq'=>'<=', 'Neq'=>'<>', 'Like'=>'LIKE', 'Notlike'=>'NOT LIKE'];
?>
<form method="post" action="<?=$data['action']?>/search">
	<div class="row">
        <div class="col m2">
          <div class="input-field inline">
    		    <select class="validate" name="search_by" id="sby" required>
    		      <option value="" disabled selected>By</option>
    		      	<?php
    		        foreach ($data['options'] as $key=>$value) {
    		      		echo "<option value='$key'>$value</option>";
    		        }
    		        ?>
    		    </select>
    		    <label for="sby" data-error="wrong" data-success="right">Search By</label>
		      </div>
        </div>
        <div class="col m2">
          <div class="input-field inline">
            <select class="validate" name="search_oper" id="search_oper" required>
              <option value="" disabled selected>Operation</option>
                <?php
                foreach ($where as $key=>$value) {
                  echo "<option value='$key'>$value</option>";
                }
                ?>
            </select>
            <label for="search_oper" data-error="wrong" data-success="right">Operation</label>
          </div>
        </div>
        <div class="col m2">
          <div class="input-field inline">
    		    <input id="txt" type="text" name="search_txt" class="validate" required>
        		<label for="txt" data-error="wrong" data-success="right">Search Text</label>
    		  </div>
        </div>
        <div class="col m2">
        	<div class="input-field inline">
          		<button class="btn waves-effect waves-light airtel-red round-btn" type="submit">Search</button>
      		</div>
        </div>
        <div class="col m3">
        	<div class="input-field inline">
          		<a class="btn waves-effect waves-light airtel-red round-btn" href="<?=$data['action']?>">Show All</a>
      		</div>
        </div>
      </div>
</form>