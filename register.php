<?php
require_once 'subtask/connect.inc.php';
require_once 'subtask/readymade_functions.inc.php';

function setup($field){
	if( (isset($_REQUEST[$field])) && (!empty($_REQUEST[$field])) )
		return true;
	else
	{

		return false;
	}
}

if(setup('email') && setup('location') && setup('college'))
{
	$email = mysqli_real_escape_string($connect,htmlentities($_REQUEST['email']));
	$location = mysqli_real_escape_string($connect,htmlentities($_REQUEST['location']));
	$college = mysqli_real_escape_string($connect,htmlentities($_REQUEST['college']));

	if($_SESSION['id']==NULL)
	{
		//user is trying to fraud with the system
		return NULL;
	}

	$myquery=sprintf("UPDATE %s SET email='%s',location='%s',college='%s' WHERE id='%s'",$table_student,$email,$location,$college,$_SESSION['id']);
	if($resQuery=mysqli_query($connect,$myquery))
	{
		return true;
	}
	else
	{
		return false;
	}


}
else
{
	return NULL;
}

?>