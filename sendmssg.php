<?php
include 'connect.php';
if(isset($_POST['send'])){

    $id = 'create_unique_id()';
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $message = $_POST['message'];
    $message = filter_var($message, FILTER_SANITIZE_STRING);
 
    $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
    $verify_message->execute([$name, $email, $number, $message]);
 
    if($verify_message->rowCount() > 0){
       $warning_msg[] = 'message sent already!';
    }else{
       $insert_message = $conn->prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
       $insert_message->execute([$id, $name, $email, $number, $message]);
       $success_msg[] = 'message send successfully!';
    }
 
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
    <!-- contact section starts  -->

<section class="contact" id="contact">

<div class="row">

   <form action="" method="post">
      <h3>send us message</h3>
      <input type="text" name="name" required maxlength="50" placeholder="enter your name" class="box">
      <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
      <input type="number" name="number" required maxlength="10" min="0" max="9999999999" placeholder="enter your number" class="box">
      <textarea name="message" class="box" required maxlength="1000" placeholder="enter your message" cols="30" rows="10"></textarea>
      <input type="submit" value="send message" name="send" class="btn">
   </form>

   <div class="faq">
      <h3 class="title">frequently asked questions</h3>
      <div class="box active">
         <h3>What utilities are included in the rent?</h3>
         <p>Utilities included in the rent are water, gas and electricity</p>
      </div>
      <div class="box">
         <h3>Are there any additional costs to pay on top of the monthly rent?</h3>
         <p> No, there are no additional costs to pay on top of the monthly rent, unless you cause damage to the apartment</p>
      </div>
      <div class="box">
         <h3>Is it possible to sublet the apartment?</h3>
         <p>No, it is not possible to sublet the apartment. It is strictly forbidden></p>
      </div>
      <div class="box">
         <h3>How is the rent payment procedure?</h3>
         <p>The rent payment procedure consists of paying the monthly rent before the agreed due date</p>
      </div>
      <div class="box">
         <h3>Are pets allowed in the apartment?</h3>
         <p>fortunately pets are allowed in the apartment</p>
      </div>
   </div>

</div>

</section>

<!-- contact section ends -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>

<?php include 'message.php'; ?>

</body>
</html>