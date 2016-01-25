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
	$event = mysqli_real_escape_string($connect,htmlentities($_REQUEST['zeit_event']));
	if(setup('cancel'))
	{
		$myquery=sprintf("UPDATE %s SET %s='' WHERE id='%s'",$table_event,$event,$_SESSION['id']);
		if($resQuery=mysqli_query($connect,$myquery))
		{
			// echo 'A22';
			echo json_encode(array('status' => 'true'));
			return;
		}
		else
		{
			// echo 'A23';
			echo json_encode(array('status' => 'false','error'=>'No such event Exists'));
			return;
		}
	}
	else if(setup('details'))
	{
		// echo 'A1';
		$det_array=array();
		//asked for event details => team size
		
		$myquery=sprintf("SELECT team_size_min,team_size_max FROM %s WHERE event='%s'",$table_event_detail,$event);
			if($resQuery=mysqli_query($connect,$myquery))
			{
				// echo 'A2';
				// echo 'Query2</ br>';
				mysqli_data_seek($resQuery,0);
				if($myValue = mysqli_fetch_row($resQuery))
				{
					//now return the event details and show up team reg form on its calling fngerprint.js
					//ok got it :P
					// echo 'A3';
					$det_array['status']='true';
					$det_array['min_size']=$myValue[0];
					$det_array['max_size']=$myValue[1];

					//now checking if the user has already created his/her team
					$myquery=sprintf("SELECT id,%s FROM %s WHERE id='%s'",$event,$table_event,$_SESSION['id']);
					if($resQuery=mysqli_query($connect,$myquery))
					{
						// echo 'A4';
						mysqli_data_seek($resQuery,0);
						if($myValue = mysqli_fetch_row($resQuery))
						{
							//id exists
							//storing the members name
							// echo 'A5';
							$det_array['member']=explode(" ",$myValue[1]);
							echo json_encode($det_array);
						}
						else
						{
							// echo 'A6';
							echo json_encode($det_array);
							//No team Members have pre registered yet
						}
					}
					else
					{
						// echo 'A7';
						echo json_encode($det_array);
						// echo json_encode(array('status'=>'false','error'=>'S Query Error'));
						// return;
					}
					
				}
				else
				{
					// echo 'A8';
					echo json_encode(array('status'=>'false','error'=>'no such event exists'));
					return;
				}
			}
			else
			{
				// echo 'A9';
				echo json_encode(array('status'=>'false','error'=>'Query Failure'));
				return;
			}
	}
	else if((setup('team-member')))
	{
		//getting the event details for submission
		$min=0;
		$max=0;
		$event = mysqli_real_escape_string($connect,htmlentities($_REQUEST['zeit_event']));
		$myquery=sprintf("SELECT team_size_min,team_size_max FROM %s WHERE event='%s'",$table_event_detail,$event);
		// echo 'A10';
		if($resQuery=mysqli_query($connect,$myquery))
		{
			// echo 'Query2</ br>';
			// echo 'A11';
			mysqli_data_seek($resQuery,0);
			if($myValue = mysqli_fetch_row($resQuery))
			{
				// echo 'A12';
				//setting up min and max team members limit
				$min=$myValue[0];
				$max=$myValue[1];
				// echo $min."+".$max;
			}
			else
			{
				// echo 'A13';
				echo json_encode(array('status'=>'false','error'=>'no such event exists'));
				return;
			}
		}
		else
		{
			// echo 'A14';
			echo json_encode(array('status'=>'false','error'=>'Query Failure'));
			return;
		}

		//submitted team members details
		$member_id=array();
		if (is_array($_POST['team-member'])) {
			// echo 'A15';
			if(sizeof($_POST['team-member'])<$min || sizeof($_POST['team-member'])>$max)
			{
				// echo 'A16';
				echo json_encode(array('status'=>'false','error'=>'team size incorrect'));
				return;
			}
	  	$i=0;
	    foreach($_POST['team-member'] as $value){
	      $tmp_member_id = trim($value);
	      // echo "'".$tmp_member_id."'";
	      if(preg_match("/Z16[0-9]{7}/i", $tmp_member_id))
	      {
	      	//ID FORMAT Z160000001
	      	//member id is valid
	      	//storing the member id in an array as upper case
	      	array_push($member_id, strtoupper($tmp_member_id));

	      }
	      else if(!empty($tmp_member_id) && $i<$min-1)
	      {
	      	//invalid id passed
	      	echo json_encode(array('status'=>'false','error'=>'invalidId','index'=>$i));
	      	return;
	      }
	      $i++;

	    }

	    //now we are required to store it in DB
	    // echo 'A17';
	    $memb = implode(" ",$member_id);
	    $myquery=sprintf("SELECT id FROM %s WHERE id='%s'",$table_event,$_SESSION['id']);
		if($resQuery=mysqli_query($connect,$myquery))
		{
			
			mysqli_data_seek($resQuery,0);
			if($myValue = mysqli_fetch_row($resQuery))
			{
				//id exists	
				// echo 'A18';
			}
			else
			{
				//Id doesnt exist
				$myquery=sprintf("INSERT INTO %s (id) VALUES ('%s')",$table_event,$_SESSION['id']);
				if($resQuery=mysqli_query($connect,$myquery))
				{
					//ID is now in the table :)
					// echo 'A19';
				}
				else
				{	// echo 'A20';
					echo json_encode(array('status'=>'false','error'=>'I Query Error'));
					return;
				}
			}
		}
		else
		{
			// echo 'A21';
			echo json_encode(array('status'=>'false','error'=>'S Query Error'));
			return;
		}

		//now id is for sure there in the table
		//proceed to fill up the event team details

		$myquery=sprintf("UPDATE %s SET %s='%s' WHERE id='%s'",$table_event,$event,$memb,$_SESSION['id']);
		if($resQuery=mysqli_query($connect,$myquery))
		{
			// echo 'A22';
			echo json_encode(array('status' => 'true','members'=>$memb));
		}
		else
		{
			// echo 'A23';
			echo json_encode(array('status' => 'false','error'=>'No such event Exists'));
		}

	  }
	  else {
	  	// echo 'A24';
	  	echo json_encode(array('status'=>'false','error'=>'Not A'));
	  	return;
	  }
	  //team members details captured

	}
	else
	{	// echo 'A25';
		echo json_encode(array('status'=>'false','error'=>'Nothing valuable found :('));
		return;
	}
}
else
{	// echo 'A26';
	echo json_encode(array('status'=>'false','Incorrect Fields'));
	return;
}

?>