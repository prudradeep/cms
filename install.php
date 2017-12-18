<?php
/*
*Author:  Pradeep Rajput
*Email:   prithviraj.rudraksh@gmail.com 
*Website: ----------
*File:    Install
*/    
    // start session
    if (session_id() == "") session_start();
    
    $isSecure = false;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $isSecure = true;
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
        $isSecure = true;
    }
    
    $path = str_replace('/install.php', '', $_SERVER['PHP_SELF']);

    if(isset($_POST['install'])){
        $secure='';
        if(!empty($_POST['issecure']) && $_POST['issecure']=='true')
          $secure='';
        else
          $secure='#';
        $htaccess = sprintf(file_get_contents('./install/htaccess.tmp'),$secure,$_POST['webroot']);
        $cFile = fopen('./.htaccess', 'w');
        fwrite($cFile, $htaccess);
        fclose($cFile);

        $htaccess = sprintf(file_get_contents('./install/htaccessapi.tmp'),$secure,$_POST['webroot'].'/api');
        $cFile = fopen('./api/.htaccess', 'w');
        fwrite($cFile, $htaccess);
        fclose($cFile);

        $config = sprintf(file_get_contents('./install/config.tmp'),$_POST['db_host'],$_POST['db_port'],$_POST['db_user'],$_POST['db_pass'],$_POST['db_base']);
        $cFile = fopen('./config.php', 'w');
        fwrite($cFile, $config);
        fclose($cFile);


        $_SESSION['install'] = $_POST;
        header('location:./install/');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Phindart: A PHP Framework!">
    <meta name="keywords" content="Phindart, Phindart PHP Framework,Phindart Framework">
    <title>Phindart</title>

    <!-- Materialize -->
    <link href="./themes/materialize/css/materialize.min.css" rel="stylesheet" />
    <link href="./themes/materialize/css/phindart.css" rel="stylesheet" />
    <link href="./plugs/material-icons/material-icons.css" rel="stylesheet" />    
    <script src="./plugs/jquery-3.2.1.min.js"></script>    
    <script src="./themes/materialize/js/materialize.min.js"></script>
    <style>
    .row{
      margin-bottom: 0px;
    }
    </style>
    <script>
        $(document).ready(function(){
            $('#db_base').focusout(function(){
              $.ajax({
                url: './install/dbcheck.php',
                method: 'post',
                data:{host:$('#db_host').val(),user:$('#db_user').val(),pass:$('#db_pass').val(),port:$('#db_port').val(),db:$('#db_base').val()},
                success: function(data){
                  $('#note').html(data);
                }
              });      
            });
            $('#issecure').change(function(){
              if(this.checked)
                $('#base').val("https://<?=$_SERVER['HTTP_HOST'].$path ?>/");
              else
                $('#base').val("http://<?=$_SERVER['HTTP_HOST'].$path ?>/");
            });
        });
    </script>
  </head>
  <body>
    <div class="valign-wrapper" style="height: 100%">
    <form method="post">
    <div class="row">
        <div class="col s12 m4">
            <div class="card">
                <div class="card-content">
                    <h5 class="center-align">Prerequisites</h5>
                    <p>If any one of these items not supported(Marked as <b>No</b>) then please take action to correct them.</p>
                    <ul class="collection">
                        <li class="collection-item"><div>PHP Version >= 7.0<span class="secondary-content"><?=phpversion()>=7.0?'Yes':'No'?></span></div></li>
                        <li class="collection-item"><div>Database support(PDO, PDO_MySql)<span class="secondary-content"><?=(extension_loaded('pdo') && extension_loaded('pdo_mysql'))?'Yes':'No'?></span></div></li>
                        <li class="collection-item"><div>XML support<span class="secondary-content"><?=extension_loaded('xml')?'Yes':'No'?></span></div></li>
                        <li class="collection-item"><div>JSON support<span class="secondary-content"><?=extension_loaded('json')?'Yes':'No'?></span></div></li>
                        <li class="collection-item"><div>URL Rewrite support<span class="secondary-content"><?=in_array('mod_rewrite', apache_get_modules())?'Yes':'No'?></span></div></li>
                        <li class="collection-item"><div>Configuration PHP writeable<span class="secondary-content"><?=is_writable ( './' )?'Yes':'No'?></span></div></li>
                      </ul>
                </div>
            </div>
        </div>
        <div class="col s12 m4">
            <div class="card">
                <div class="card-content">
                    <h5 class="center-align">Database Configuration</h5>                    
                      <div class="row">
                         <div class="input-field col m6">
                            <input id="db_host" type="text" name="db_host" value="localhost" required>
                            <label for="db_host">DB Host</label>
                         </div>
                         <div class="input-field col m6">
                            <input id="db_port" type="text" name="db_port" value="3306" required>
                            <label for="db_port">DB Port</label>
                         </div>
                      </div>
                      <div class="row">
                         <div class="input-field col m6">
                            <input id="db_user" type="text" name="db_user" value="root" required>
                            <label for="db_user">DB User</label>
                         </div>
                         <div class="input-field col m6">
                            <input id="db_pass" type="password" name="db_pass">
                            <label for="db_pass">DB Password</label>
                         </div>
                      </div>   
                      <div class="row">
                         <div class="input-field col m12">
                            <input id="db_base" type="text" name="db_base" required>
                            <label for="db_base">DB Name</label>
                            <p class="red-text" id="note"></p>
                         </div>
                      </div>
                </div>
            </div>
        </div>
        <div class="col s12 m4">
            <div class="card">
                <div class="card-content">
                    <h5 class="center-align">Other Configuration</h5>                    
                      <div class="row">
                         <div class="input-field col m6">
                            <input id="webroot" type="text" name="webroot" value="<?=$path?>" required>
                            <label for="timezone">Website Root</label>
                         </div>
                         <div class="input-field col m6">
                            <input id="issecure" type="checkbox" name="issecure" value="true" <?=$isSecure ? 'checked' : ''?>/>
                            <label for="issecure">Is Secure?</label>
                         </div>
                      </div>
                      <div class="row">
                         <div class="input-field col m6">
                            <input id="timezone" type="text" name="timezone" value="Asia/Kolkata" required>
                            <label for="timezone">Timezone</label>
                         </div>
                         <div class="input-field col m6">
                            <input id="logo" type="text" name="logo" value="Phindart" required>
                            <label for="logo">Brand Name</label>
                         </div>
                      </div>
                      <div class="row">
                         <div class="input-field col m6">
                            <input id="title" type="text" name="title" value="Phindart" required>
                            <label for="title">Title</label>
                         </div>
                         <div class="input-field col m6">
                            <input id="description" type="text" name="description" value="Phindart: A PHP Framework!" required>
                            <label for="description">Description</label>
                         </div>
                      </div>
                      <div class="row">
                         <div class="input-field col m6">
                            <input id="keys" type="text" name="keys" value="Phindart, Phindart PHP Framework,Phindart Framework" required>
                            <label for="keys">Keys</label>
                         </div>
                         <div class="input-field col m6">
                            <input id="copyright" type="text" name="copyright" value="2017-2018 &copy; All rights reserved." required>
                            <label for="copyright">Copyright</label>
                         </div>
                      </div>
                      <div class="row">
                         <div class="input-field col m12">
                            <input id="base" type="text" name="base" value="<?=$isSecure ? 'https' : 'http'?>://<?=$_SERVER['HTTP_HOST'].$path ?>/" required>
                            <label for="base">Websit URL</label>
                         </div>
                      </div>
                      <div class="row">
                         <div class="input-field col m6">
                            <input id="username" type="text" name="username" value="admin" required>
                            <label for="username">Username</label>
                         </div>
                         <div class="input-field col m6">
                            <input id="password" type="password" name="password" required>
                            <label for="password">Password</label>
                         </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
         <div class="input-field col s12 center-align">
            <button class="btn waves-effect waves-light airtel-red round-btn" name="install" type="submit">Submit
            </button>
            <button class="modal-action modal-close waves-effect waves-green btn round-btn airtel-red" type="reset">Close
            </button>
         </div>
      </div>
  </form>
</div>
  </body>
</html>
