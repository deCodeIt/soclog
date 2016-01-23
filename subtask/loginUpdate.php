<?php
require_once 'connect.inc.php';

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
	echo '1</ br>';
	$myquery=sprintf("SELECT %s FROM %s WHERE prof_id='%s'",'id',$table_student,$prof_id);
	if($resQuery=mysqli_query($connect,$myquery))
	{
		mysqli_data_seek($resQuery,0);
		if($myValue = mysqli_fetch_row($resQuery))
		{
			#user already exists in database
			$_SESSION['id'] = $myValue[0];
		}
		else
		{
			#user not found
			$_SESSION['id'] = NULL;
		}
	}
	else
	{
		#error finding the user
		$_SESSION['id'] = NULL;
	}
	echo '2</ br>';
	if($_SESSION['id']==NULL)
	{
		//a new user
		$myquery=sprintf("INSERT INTO %s (name,email,gender,prof_id,accessToken) VALUES ('%s','%s','%s','%s','%s')",$table_student,$name,$email,$gender,$prof_id,$accessToken);
		if($resQuery=mysqli_query($connect,$myquery))
		{
			//getting the user id
			$myquery=sprintf("SELECT %s FROM %s WHERE prof_id='%s'",'id',$table_student,$prof_id);
			if($resQuery=mysqli_query($connect,$myquery))
			{
				mysqli_data_seek($resQuery,0);
				if($myValue = mysqli_fetch_row($resQuery))
				{
					#user already exists in database
					$_SESSION['id'] = $myValue[0];
					$_SESSION['accessToken']=$accessToken;
				}
				else
				{
					#user not found
					$_SESSION['id'] = NULL;
					$_SESSION['accessToken']=NULL;
				}
			}
			else
			{
				#error finding the user
				$_SESSION['id'] = NULL;
				$_SESSION['accessToken']=NULL;
			}

		}
		else
		{
			$_SESSION['accessToken']=NULL;
			$_SESSION['id']=NULL;
		}
	}
	else
	{
		//user has updated its access token
		if(setField('accessToken',$accessToken))
		{
			$_SESSION['accessToken']=$accessToken;
		}
		else
		{
			$_SESSION['accessToken']=NULL;
		}

	}
	

}
else
{
	echo "fields missing";
}

?>