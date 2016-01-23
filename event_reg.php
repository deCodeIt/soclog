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

if(setup('zeit_event') && setup('stat'))
{
	$event = mysqli_real_escape_string($connect,htmlentities($_REQUEST['zeit_event']));
	$status = mysqli_real_escape_string($connect,htmlentities($_REQUEST['stat']));
	if($status!=2)
	{
		//2 => not registered
		$status=2;
	}
	else
	{
		$status=1;
	}
	
	if($_SESSION['id']==NULL)
	{
		//user is trying to fraud with the system
		echo json_encode(array('status' => 'false','error'=>'Attacked'));
	}

	//checking if id already exists in events table
	$myquery=sprintf("SELECT id FROM %s WHERE id='%s'",$table_event,$_SESSION['id']);
			if($resQuery=mysqli_query($connect,$myquery))
			{
				// echo 'Query2</ br>';
				mysqli_data_seek($resQuery,0);
				if($myValue = mysqli_fetch_row($resQuery))
				{
					//id exists
					
				}
				else
				{
					//Id doesnt exist
					$myquery=sprintf("INSERT INTO %s (id) VALUES ('%s')",$table_event,$_SESSION['id']);
					if($resQuery=mysqli_query($connect,$myquery))
					{
						//ID is now in the table :)
					}
					else
					{
						return;
					}
				}
			}
			else
			{
				// echo 'Query2Failed</ br>';
				#error finding the user
				return;
			}

	$myquery=sprintf("UPDATE %s SET '%s'='%s' WHERE id='%s'",$table_student,$event,$status,$_SESSION['id']);
	if($resQuery=mysqli_query($connect,$myquery))
	{
		echo json_encode(array('status' => 'true','reg'=>$status));
	}
	else
	{
		echo json_encode(array('status' => 'false','error'=>'No such event Exists'));;
	}


}
else
{
	echo json_encode(array('status' => 'false','error'=>'Fields Missing'));
}

?>