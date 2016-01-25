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

	$event_list = array();
	$reg_events = array();
	// $event = mysqli_real_escape_string($connect,htmlentities($_REQUEST['zeit_event']));
		
	if($_SESSION['id']==NULL)
	{
		//user is trying to fraud with the system
		echo json_encode(array('status' => 'false','error'=>'Attacked'));
		return;
	}

	//checking if id already exists in events table
	$myquery=sprintf("SELECT event,name,src FROM %s",$table_event_detail);
	if($resQuery=mysqli_query($connect,$myquery))
	{
		// selected all lists of events
		$i=0;
		
		while($myValue = mysqli_fetch_assoc($resQuery))
		{
			
			$event_list[$i]['event']=$myValue['event'];
			$event_list[$i]['name']=$myValue['name'];
			$event_list[$i]['src']=$myValue['src'];
			$event_list[$i]['reg']=2; //not registered by default
			$i++;

		}
		$count = $i;
		//Now check what all events are registered by the student

		for ($i=0; $i < $count; $i++) { 
			# code...
			$myquery=sprintf("SELECT id,%s FROM %s WHERE id='%s'",$event_list[$i]['event'],$table_event,$_SESSION['id']);
			if($resQuery=mysqli_query($connect,$myquery))
			{
				// echo 'Query2</ br>';
				mysqli_data_seek($resQuery,0);
				if($myValue = mysqli_fetch_row($resQuery))
				{
					$event_list[$i]['reg']=$myValue[1];
					if(1==1)
					{
						//registered for this event
						array_push($reg_events,$event_list[$i]);
					}
				}
			}
		}
		echo json_encode(array('status' => 'true','data'=>$reg_events));
	}
	else
	{
		// echo 'Query2Failed</ br>';
		#error finding the user
		echo json_encode(array('status' => 'false','error'=>'Query Error'));
		return;
	}

?>