<?php
require_once 'connect.inc.php';
require_once 'readymade_functions.inc.php';

function setup($field){
	if( (isset($_REQUEST[$field])) && (!empty($_REQUEST[$field])) )
		return true;
	else
	{

		return false;
	}
}

if(setup('accessToken') && setup('name') && setup('email') && setup('prof_id') && setup('gender'))
{
	//all fields are available
	$name = mysqli_real_escape_string($connect,htmlentities($_REQUEST['name']));
	$email = mysqli_real_escape_string($connect,htmlentities($_REQUEST['email']));
	$gender = mysqli_real_escape_string($connect,htmlentities($_REQUEST['gender']));
	$prof_id = mysqli_real_escape_string($connect,htmlentities($_REQUEST['prof_id']));
	$accessToken = mysqli_real_escape_string($connect,htmlentities($_REQUEST['accessToken']));
	
	$_SESSION['id']=NULL;
	$_SESSION['accessToken'] =NULL;
	// echo 'Getting in...</ br>';
	$myquery=sprintf("SELECT id,reg_complete FROM %s WHERE profile_id='%s'",$table_student,$prof_id);
	if($resQuery=mysqli_query($connect,$myquery))
	{
		// echo 'user found';
		mysqli_data_seek($resQuery,0);
		if($myValue = mysqli_fetch_row($resQuery))
		{
			// echo'got the user id';
			#user already exists in database
			$_SESSION['id'] = $myValue[0];
			// $_SESSION['reg_complete']=$myValue[1];
			// echo 'is isnt null</ br>';
			//user has updated its access token
			if(setField('accessToken',$accessToken))
			{
				// echo 'accessToken set';
				$_SESSION['accessToken']=$accessToken;
				echo json_encode(array('reg_complete' => $myValue[1],
				'status'=>'true',
				'id'=>'Z16'.str_pad($_SESSION['id'], 7, "0", STR_PAD_LEFT)));
				return;
			}
			else
			{
				// echo 'error setting access token';
				// echo json_encode(array('status'=>'false'));
				$_SESSION['accessToken']=NULL;
				echo json_encode(array('status'=>'false'));
				return;
			}
		}
		else
		{
			// echo'user id Null :0';
			#user not found
			$_SESSION['id'] = NULL;
		}
	}
	else
	{
		#error running query
		// echo 'user not found</ br>';
		$_SESSION['id'] = NULL;
	}
	// echo 'check for user id</ br>';
	if($_SESSION['id']==NULL)
	{
		//a new user
		// echo 'id is null</ br>';
		$myquery=sprintf("INSERT INTO %s (name,email,gender,profile_id,accessToken) VALUES ('%s','%s','%s','%s','%s')",$table_student,$name,$email,$gender,$prof_id,$accessToken);
		if($resQuery=mysqli_query($connect,$myquery))
		{
			//getting the user id
			// echo 'Query1</ br>';
			$myquery=sprintf("SELECT id FROM %s WHERE profile_id='%s'",$table_student,$prof_id);
			if($resQuery=mysqli_query($connect,$myquery))
			{
				// echo 'Query2</ br>';
				mysqli_data_seek($resQuery,0);
				if($myValue = mysqli_fetch_row($resQuery))
				{
					// echo 'fetch1</ br>';
					#user already exists in database
					echo json_encode(array('status'=>'true','id'=>'Z16'.str_pad($_SESSION['id'], 7, "0", STR_PAD_LEFT)));
					$_SESSION['id'] = $myValue[0];
					$_SESSION['accessToken']=$accessToken;
					return;
				}
				else
				{
					#user not found
					// echo 'fetch2</ br>';
					echo json_encode(array('status'=>'false'));
					$_SESSION['id'] = NULL;
					$_SESSION['accessToken']=NULL;
					return;
				}
			}
			else
			{
				// echo 'Query2Failed</ br>';
				#error finding the user
				echo json_encode(array('status'=>'false'));
				$_SESSION['id'] = NULL;
				$_SESSION['accessToken']=NULL;
				return;
			}

		}
		else
		{
			// echo 'Query1 Failed</ br>';
			echo json_encode(array('status'=>'false'));
			$_SESSION['accessToken']=NULL;
			$_SESSION['id']=NULL;
			return;
		}
	}
	else
	{
		

	}
	

}
else
{
	// echo "fields missing";
	echo json_encode(array('status'=>'false'));
	return;
}

?>