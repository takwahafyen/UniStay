<?php

include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', 'create_unique_id()', time() + 60*60*24*30, '/');
   header('location:index.php');
}

if(isset($_POST['check'])){

   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while ($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)) {
      $total_rooms += $fetch_bookings['rooms'];
  }
  
   }

   // if the hotel has total 30 rooms 
   if($total_rooms >= 30){
      $warning_msg[] = 'rooms are not available';
   }else{
      $success_msg[] = 'rooms are available';
   }
 
 
 ?>
 <!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
   <!-- availability section starts  -->

<section class="availability" id="availability">

<form action="" method="post">
   <div class="flex">
      <div class="box">
         <p>check in <span>*</span></p>
         <input type="date" name="check_in" class="input" required>
      </div>
      <div class="box">
         <p>check out <span>*</span></p>
         <input type="date" name="check_out" class="input" required>
      </div>
      <div class="box">
         <p>nbr_persons<span>*</span></p>
         <select name="nbr_persons" class="input" required>
            <option value="1">1 person</option>
            <option value="2">2 persons</option>
            <option value="3">3 persons</option>
            <option value="4">4 persons</option>
            <option value="5">5 persons</option>
            <option value="6">6 persons</option>
         </select>
      </div>
      <div class="box">
         <p>type_housing<span>*</span></p>
         <select name="type_housing" class="input" required>
            <option value="1">studio</option>
            <option value="2">small house</option>
            <option value="3">luxury house</option>
            <option value="4">apartment</option>
            <option value="5">state household</option>
            <option value="6">private household</option>
         </select>
      </div>
      <div class="box">
         <p>rooms <span>*</span></p>
         <select name="rooms" class="input" required>
            <option value="1">1 room</option>
            <option value="2">2 rooms</option>
            <option value="3">3 rooms</option>
            <option value="4">4 rooms</option>
            <option value="5">5 rooms</option>
            <option value="6">6 rooms</option>
         </select>
      </div>
   </div>
   <input type="submit" value="check availability" name="check" class="btn">
</form>

</section>

<!-- availability section ends -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>


<!-- custom js file link  -->
<script src="script.js"></script>
<?php include 'message.php'; ?>
</body>
</html>