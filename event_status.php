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

if(setup('zeit_event'))
{
	$event = mysqli_real_escape_string($connect,htmlentities($_REQUEST['zeit_event']));
		
	if($_SESSION['id']==NULL)
	{
		//user is trying to fraud with the system
		echo json_encode(array('status' => 'false','error'=>'Attacked'));
		return;
	}

	//checking if id already exists in events table
	$myquery=sprintf("SELECT id,%s FROM %s WHERE id='%s'",$event,$table_event,$_SESSION['id']);
			if($resQuery=mysqli_query($connect,$myquery))
			{
				// echo 'Query2</ br>';
				mysqli_data_seek($resQuery,0);
				if($myValue = mysqli_fetch_row($resQuery))
				{
					echo json_encode(array('status' => 'true','reg'=>$myValue[1]));
					return;
				}
				else
				{
					//Id doesnt exist
					echo json_encode(array('status' => 'false','error'=>'Login First'));
					return;
				}
			}
			else
			{
				// echo 'Query2Failed</ br>';
				#error finding the user
				echo json_encode(array('status' => 'false','error'=>'Query Error'));
					return;
				return;
			}

}
else
{
	echo json_encode(array('status' => 'false','error'=>'Fields Missing'));
	return;
}

?>