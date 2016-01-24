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
if($_SESSION['id']==NULL)
	{
		//user is trying to fraud with the system
		echo json_encode(array('status' => 'false','error'=>'Attacked'));
		return;
	}

else if(setup('zeit_event'))
{
	if(setup('details'))
	{
		$det_array=array();
		//asked for event details => team size
		$event = mysqli_real_escape_string($connect,htmlentities($_REQUEST['zeit_event']));
		$myquery=sprintf("SELECT team_size_min,team_size_max FROM %s WHERE event='%s'",$table_event_detail,$event);
			if($resQuery=mysqli_query($connect,$myquery))
			{
				// echo 'Query2</ br>';
				mysqli_data_seek($resQuery,0);
				if($myValue = mysqli_fetch_row($resQuery))
				{
					//now return the event details and show up team reg form on its calling fngerprint.js
					//ok got it :P

					array_push($det_array,'success'=>'true','min_size'=>$myValue[0],'max_size'=>$myValue[1]));
					//now checking if the user has already created his/her team
					$myquery=sprintf("SELECT id,%s FROM %s WHERE id='%s'",$table_event,$event,$_SESSION['id']);
					if($resQuery=mysqli_query($connect,$myquery))
					{
						
						mysqli_data_seek($resQuery,0);
						if($myValue = mysqli_fetch_row($resQuery))
						{
							//id exists
							//storing the members name
							array_push($det_array,explode(" ",$myValue[1]));
							json_encode($det_array);
						}
						else
						{
							//No team Members have pre registered yet
						}
					}
					else
					{
						// echo json_encode(array('status'=>'false','error'=>'S Query Error'));
						// return;
					}
					
				}
				else
				{
					echo json_encode(array('success'=>'false','error'=>'no such event exists'));
				}
			}
			else
			{
				echo json_encode(array('success'=>'false','error'=>'Query Failure'));
				return;
			}
	}
	else if(setup('team-member-id'))
	{
		//getting the event details for submission
		$min,$max;
		$event = mysqli_real_escape_string($connect,htmlentities($_REQUEST['zeit_event']));
		$myquery=sprintf("SELECT team_size_min,team_size_max FROM %s WHERE event='%s'",$table_event_detail,$event);
		if($resQuery=mysqli_query($connect,$myquery))
		{
			// echo 'Query2</ br>';
			mysqli_data_seek($resQuery,0);
			if($myValue = mysqli_fetch_row($resQuery))
			{
				//setting up min and max team members limit
				$min=$myValue[0];
				$max=$myValue[1];
				
			}
			else
			{
				echo json_encode(array('success'=>'false','error'=>'no such event exists'));
				return;
			}
		}
		else
		{
			echo json_encode(array('success'=>'false','error'=>'Query Failure'));
			return;
		}

		//submitted team members details
		$member_id=array();
		if (is_array($_POST['team-member-id'])) {
			if(!sizeof($_POST['team-member-id'])>=$min-1 || !sizeof($_POST['team-member-id'])<=$max)
			{
				echo json_encode(array('status'=>'false','error'=>'team size incorrect'));
				return;
			}
	  	$i=0;
	    foreach($_POST['team-member-id'] as $value){
	      $tmp_member_id = trim($value);
	      if(preg_match("/Z16[0-9]{7}/i", $tmp_member_id))
	      {
	      	//ID FORMAT Z160000001
	      	//member id is valid
	      	//storing the member id in an array as upper case
	      	array_push($member_id, strtoupper($tmp_member_id));

	      }
	      else
	      {
	      	//invalid id passed
	      	echo json_encode(array('status'=>'false','error'=>'invalidId','index'=>$i));
	      	return;
	      }
	      $i++;

	    }

	    //now we are required to store it in DB
	    $memb = implode(" ",$member_id);
	    $myquery=sprintf("SELECT id FROM %s WHERE id='%s'",$table_event,$_SESSION['id']);
		if($resQuery=mysqli_query($connect,$myquery))
		{
			
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
					echo json_encode(array('status'=>'false','error'=>'I Query Error'));
					return;
				}
			}
		}
		else
		{
			echo json_encode(array('status'=>'false','error'=>'S Query Error'));
			return;
		}

		//now id is for sure there in the table
		//proceed to fill up the event team details

		$myquery=sprintf("UPDATE %s SET %s='%s' WHERE id='%s'",$table_event,$event,$memb,$_SESSION['id']);
		if($resQuery=mysqli_query($connect,$myquery))
		{
			echo json_encode(array('status' => 'true','members'=>$memb));
		}
		else
		{
			echo json_encode(array('status' => 'false','error'=>'No such event Exists'));
		}

	  }
	  else {
	  	echo json_encode(array('success'=>'false','error'=>'Not A'));
	  	return;
	  }
	  //team members details captured

	}
	else
	{
		echo json_encode(array('status'=>'false'));
	}
}
else
{
	echo json_encode(array('status' => 'false','error'=>'Fields Missing'));
}

?>