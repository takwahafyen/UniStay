<?php
session_start();
if(isset($_POST['email']) && isset($_POST['password'])){ 
$email=$_POST['email'];
$password=$_POST['password'];
$con= mysqli_connect("localhost","root","","hotel");
$sql="select* from registrationtable where email='$email' and password='$password'";
$result= mysqli_query($con,$sql);

if(mysqli_num_rows($result)==1){
    if($password=="takwaaycha123")
     {header ('location:nameadminpage ');}
    else
    {header ('location:reservation.html');}
}
else {
    header ('location:#');}

}

?>