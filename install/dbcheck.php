<?php
	if(isset($_POST['user']) && isset($_POST['pass']) && $_POST['port']){
		$con=@mysqli_connect($_POST['host'],$_POST['user'],$_POST['pass'],$_POST['db'],$_POST['port']);
		if (mysqli_connect_errno()){
		  echo "Invalid DB Configs";
		}else{
			$ver = mysqli_get_server_info($con);
			mysqli_close($con);
			$ver = str_replace('5.5.5-', '', $ver);
			$ver = explode('-', $ver);
			if($ver[1]=='MariaDB' && version_compare($ver[0], '10.2.3')>=0){
				echo '';
			}else if($ver[1]=='MariaDB'){
				echo 'Required MariaDB >= 10.2.3, Installed: '.$ver[0];
			}
		}
	}
?>