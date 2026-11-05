<?php

$firstName = $_POST['username'];

$email = $_POST['email'];
$password = $_POST['password'];

$con= mysqli_connect("localhost","root","","hotel");
$sql="insert into signup(username,email,password)  values ('$firstName','$email','$password')";
  if(mysqli_query($con,$sql)){
   header ('location:loginay.html');
   $_SESSION['username']=$firstName;}
  else 
   {echo"error";}

   ?>