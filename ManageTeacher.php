<?php 
include('conn.php');

$query = "select * from users";

if (isset($_GET['orderby'])&& (($_GET['orderby']=='FirstName') || ($_GET['orderby']=='LastName')||($_GET['orderby']=='Email') ||($_GET['orderby']=='School') ||($_GET['orderby']=='TeacherCode') ||($_GET['orderby']=='isVerified') ))
{
    $query .= " ORDER BY " . $_GET['orderby'] . " ASC";
}

$sql = mysql_query($query);

if(isset($_REQUEST['Delete']))
{
	$delete = $_REQUEST['chk'];
	$temp = count($delete);
	for($i=0;$i<=$temp;$i++)
	{
		$sql = "delete from users where UserID = '$delete[$i]'";
		$result = mysql_query($sql);
	}
		header('location:ManageTeacher.php');
}


if(isset($_REQUEST['Approve']))
{
	$approve = $_REQUEST['chk'];
	changeStatus($approve,'1');
}

if(isset($_REQUEST['Disapprove']))
{
	$approve = $_REQUEST['chk'];
	changeStatus($approve,'0');
}


if(isset($_REQUEST['id']))
{
	if($_REQUEST['status'] == 'Approve')
	{
		$approve = array($_REQUEST['id']);
		changeStatus($approve,'1');
	} 
	else
	{
		$approve = array($_REQUEST['id']);
		changeStatus($approve,'0');
	}
}


function changeStatus($approve,$status)
{
	$temp = count($approve);
	for($i=0;$i<$temp;$i++)
	{
	   $sql = "update users set isVerified = '$status' where UserID = '$approve[$i]'";	 
	   $query = mysql_query($sql);
	   if(($query) && ($status == '1'))
	   {
	   		$equery = "select * from users where UserID = '$approve[$i]'";
	        $Udata = mysql_query($equery);
	   	    $userdata = mysql_fetch_array($Udata);
		    $randomNum = RandGenerate();
			$csql = "update users set TeacherCode = '$randomNum' where UserID = '$approve[$i]'";	 
			$uquery = mysql_query($csql);
			if($uquery)
			{
						$to = $Udata['Email'];
						$from = 'patelarpita19191@gmail.com';
						$Full_Name = $userdata['FirstName'];
						$subject = $Full_Name. " is Approved";
						$message = '<html><body>';   
						$message .= '<table cellpadding="10" border="0">';
						$message .= '<tr><td colspan=2>&nbsp;</td></tr>';
						$message .= "<tr style='background: #eee;'><td colspan='2'><strong>Dear User,</strong></td></tr>";
						$message .= "<tr><td colspan='2'>You are  approved to use our website with below Details.</td></tr>";    
						$message .= '<tr><td colspan=2>&nbsp;</td></tr>';            
						$message .= "<tr><td colspna='2'> TeacherID:".$randomNum." </td></tr>";
						$message .= '<tr><td colspan=2>&nbsp;</td></tr>';
						$message .= "<tr><td colspan='2'>LogIn Details</td></tr>";
						$message .= '<tr><td colspan=2>UserId:'.$userdata['Email'].'</td></tr>';
						$message .= '<tr><td colspan=2>Passsword:'.$userdata['Password'].'</td></tr>';
						$message .= '<tr><td colspan=2>&nbsp;</td></tr>';
	  					$message .= "<tr><td colspan=2 >Click Here to redirect to our website.</td></tr>";
						$message .= "<tr><td colspan='2'>Thank you.</tr>"; 
						$message .= "</table>";             
						$message .= "</body></html>";   
						$headers = "From: " . $from . "\r\n";
						$headers .= "CC: patelarpita1991@gmail.com\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
						
					//	echo $message;
					
						 $result = mail($to, "$subject", $message, "From:" . $headers);
						
						 if(!$result)
						 {
						 echo "Sorry,We couldn't send mail to User";
						 }						 
			}
	   }
	}
	   header('location:ManageTeacher.php');
}
function RandGenerate()
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $result = '';
    for ($i = 0; $i < 6; $i++)
	{
        $result .= $characters[mt_rand(0, 51)];
	}
	return $result;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Bootstrap Admin Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

   
    <link href="css/sb-admin.css" rel="stylesheet">

       <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body> 
		<div id="wrapper">

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
          <?php
		  include('menu.php');		  
		  ?>		  
        </nav>

        <div id="page-wrapper">
            <div class="container-fluid">             
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Manage Teacher
                        </h1>
                        <ol class="breadcrumb">                           
                            <li class="active">
                                <i class="fa fa-table"></i> Manage Teacher
                            </li>
                        </ol>
                    </div>
                </div>
                             
                    <div class="col-lg-6">
                        <h2>List of Teachers</h2>
                        <div class="table-responsive">
						<form method="post" name="myform">	
                            <table class="table table-bordered table-hover table-striped">
                                    <tr>
										<th><input type="submit" name="Delete" class="btn btn-info" value="Delete"></th>	
                                        <th><a href="ManageTeacher.php?orderby=FirstName">FirstName</a></th>
                                        <th><a href="ManageTeacher.php?orderby=LastName">LastName</a></th>
                                        <th><a href="ManageTeacher.php?orderby=Email">Email</a></th>
										<th><a href="ManageTeacher.php?orderby=School">School</a></th>
										<th><a href="ManageTeacher.php?orderby=TeacherCode">UniqueId</a></th>
                                        <th><a href="ManageTeacher.php?orderby=isVerified">Status</a></th>
										<th><input name="Approve" type="submit" class="btn btn-info" value="Approve"></th>
										<th><input name="Disapprove" type="submit" class="btn btn-info" value="Disapprove"></th>
                                    </tr>
                                
								<?php 
										while($row = mysql_fetch_array($sql))
										{
										 ?>
										 <tr>
										<td><input type="checkbox" name="chk[]" value="<?php echo $row['UserID'];?>" /></td>
                                        <td><?php echo $row['FirstName'];?></td>
                                        <td><?php echo $row['LastName'];?></td>
                                        <td><?php echo $row['Email'];?></td>
										<td><?php echo $row['School'];?></td>
										<td><?php echo $row['TeacherCode'];?></td>
                                  		<td> <?php 
											if($row['isVerified'] == '0')
											{ ?>
												<a href="ManageTeacher.php?status=Approve&id=<?php echo $row['UserID'];?>">Approve</a>
												<a href="ManageTeacher.php?status=Disapprove&id=<?php echo $row['UserID'];?>">Disapprove</a>
											<?php }else
											{
												echo "Approved";
											}
										?></td>
										<?php 
											if($row['isVerified'] == '1')
											{ 
											echo "<td><a href='signup.php?status=edit&id=".$row['UserID']."'>Edit</a></td>";
											echo "<td><a href='profile.php?status=login&id=".$row['UserID']."'>Login As Teacher</a></td>";											
											}
											else
											{ ?>
												<td></td>
												<td></td>	
										<?php	}
										?>
									   </tr>
										<?php 
									}	
								?>
                            </table>
							</form>
                        </div> 	
                    </div>
                </div>
                </div>
            </div>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>
