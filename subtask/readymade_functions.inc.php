<?php
ob_start();
session_start();
require_once 'connect.inc.php';
$tmp_global_connect =$connect;
?>


<?php
function hasLoggedIn(){
	if ( isset($_SESSION['id']) && !empty($_SESSION['is'])) {
		#The student has logged in
		return true;
	}
	else
	{
		return false;
	}
}

function getField($field)
{
	global $tmp_global_connect;
	return _getField($field,$tmp_global_connect);
}

function setField($field,$value)
{
	global $tmp_global_connect;
	return _setField($field,$value,$tmp_global_connect);
}

function _getField($field,$connect)
{
	global $table_name_student;
	$myquery=sprintf("SELECT %s FROM %s WHERE id='%s'",$field,$table_student,$_SESSION['id']);
	if($resQuery=mysqli_query($connect,$myquery))
	{
		mysqli_data_seek($resQuery,0);
		if($myValue = mysqli_fetch_row($resQuery))
		{
			return $myValue[0];
		}
		else
		{
			return NULL;
		}
	}
	else
	{
		return NULL;
	}
}

function _setField($field,$value,$connect)
{
	$value = mysqli_real_escape_string($connect,$value);
	global $table_student;
	$myquery=sprintf("UPDATE %s SET %s='%s' WHERE id='%s'",$table_student,$field,$value,$_SESSION['id']);
	if($resQuery=mysqli_query($connect,$myquery))
	{
		return true;
	}
	else
	{
		return false;
	}
}

?>