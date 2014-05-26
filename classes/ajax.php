<?php
if(isset($_POST['action']))
{
	switch ($_POST['action']) {
		case 'begin':
			$time = time();
			setcookie('begin',$time,$time+3600,'/');
			echo $_COOKIE['begin'];
			break;
		case 'end':
			$time = time();
			setcookie('end',$time,$time+3600,'/');
			echo $_COOKIE['end'];
			break;		
		default:
			# code...
			break;
	}
}
