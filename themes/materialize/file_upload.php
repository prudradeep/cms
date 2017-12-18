<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Theme:		Materialize
*View:		File Upload
*Vars:		action: Form Search Action, page: Page #, file: FileType, format: True/False
*/

if (! defined('PHINDART')) { die('Access denied'); }
if(!isset($data['file'])){
  $data['file']='csv';
}
if(!isset($data['format'])){
  $data['format']=true;
}else{
  $data['format']=false;
}
?>
<div id="file_upload" class="modal bottom-sheet">
    <div class="modal-content">
      <h4>Upload File</h4>
      <form method="post" action="<?=$data['action']?>/upload/<?=@$data['page']?>" enctype="multipart/form-data">
      	<div class="row">
              <div class="col l6">
      		      <div class="file-field input-field inline">
                  <div class="btn">
                    <span><?=strtoupper($data['file'])?> File</span>
                    <input type="file" name="upload_file" accept=".<?=$data['file']?>" required>
                  </div>
                  <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                  </div>
                </div>  
              </div>
              <div class="col l2">
              	<div class="input-field inline">
                		<button class="btn waves-effect waves-light airtel-red round-btn" type="submit">Upload</button>
            		</div>
              </div>
              <?php
              if($data['format']){
              ?>
              <div class="col l2">
                <div class="input-field inline">
                    <a class="btn waves-effect waves-light airtel-red round-btn" href="<?=$data['action']?>/format"><i class="material-icons left">file_download</i>Format</a>
                </div>
              </div>
              <?php
              }
              ?>
              <div class="col l2">
                <div class="input-field inline">
                    <button class="modal-action modal-close waves-effect waves-green btn round-btn airtel-red" type="reset">Close
                  </button>
                </div>
              </div>
            </div>
      </form>
  </div>
</div>