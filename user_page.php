<?php


session_start();

if(!isset($_SESSION['user_name'])){
   header('location:login_form.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>user page</title>
   <style>
/*ANIMATION*/
.animate {
-webkit-animation: animatezoom 0.6s;
animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom {
from {-webkit-transform: scale(0)} 
to {-webkit-transform: scale(1)}
}

@keyframes animatezoom {
from {transform: scale(0)} 
to {transform: scale(1)}
}

body{
margin:0;
background-image:url("https://source.unsplash.com/featured/?house");
background-repeat:no-repeat;
background-size:cover;
width:100%;
height:100%;
}
.box{
  background-color: rgba(255,255,255,0.5);
}


</style>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="admin_style.css">
   
    

</head>
<body>
 <!--  
<div class="container">

   <div class="content">
     
      <h1>welcome <span><?php echo $_SESSION['user_name'] ?></span></h1>
      <a href="logout.php" class="btn">logout</a>
   </div>

</div>-->


<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">
   <div class="box">
      <h3>Make a reservation</h3>
      <a href="housesu.php" class="btn">book now</a>
   </div>

   <div class="box">
      <h3>send a message</h3>
      <a href="sendmssg.php" class="btn">send now</a>
   </div>
   <div class="box">
      <h3>check availability</h3>
      <a href="availabilityy.php" class="btn">check now</a>
   </div>
   <div class="box">
      <h3>logout</h3>
      <a href="index.php" class="btn">logout now</a>
   </div>
</section>


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="admin_script.js"></script>

<?php include 'message.php'; ?>


</body>
</html>