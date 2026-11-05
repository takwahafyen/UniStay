<?php

@include 'config.php';

/*if(isset($_POST['add_to_cart'])){

   $house_name = $_POST['house_name'];
   $house_price = $_POST['house_price'];
   $house_image = $_POST['house_image'];
   $house_quantity = 1;

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$house_name'");

   if(mysqli_num_rows($select_cart) > 0){
      $message[] = 'house already added to cart';
   }else{
      $insert_house = mysqli_query($conn, "INSERT INTO `cart`(name, price, image, quantity) VALUES('$house_name', '$house_price', '$house_image', '$house_quantity')");
      $message[] = 'house added to cart succesfully';
   }

}*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>houses</title>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style2.css">
</head>
<body>
   
<?php

if(isset($message)){
   foreach($message as $message){
      echo '<div class="message"><span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
   };
};

?>

<?php include 'header.php'; ?>

<div class="container">

<section class="houses">

   <h1 class="heading">latest houses</h1>

   <div class="box-container">

      <?php
      
      $select_houses = mysqli_query($conn, "SELECT * FROM `houses`");
      if(mysqli_num_rows($select_houses) > 0){
         while($fetch_house = mysqli_fetch_assoc($select_houses)){
      ?>

      <form action="" method="post">
         <div class="box">
            <img src="../tak/<?php echo $fetch_house['image']; ?>" alt="">
            <h3><?php echo $fetch_house['name']; ?></h3>
            <div class="price">$<?php echo $fetch_house['price']; ?>/-</div>
            <input type="hidden" name="house_name" value="<?php echo $fetch_house['name']; ?>">
            <input type="hidden" name="house_price" value="<?php echo $fetch_house['price']; ?>">
            <input type="hidden" name="house_image" value="<?php echo $fetch_house['image']; ?>">
            <a href="book.php" class="btn">add to cart</a>
         </div>
      </form>

      <?php
         };
      };
      ?>

   </div>

</section>

</div>

<!-- custom js file link  -->
<script src="script2.js"></script>

</body>
</html>