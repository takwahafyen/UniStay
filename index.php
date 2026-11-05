<?php

include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $expiration = time() + 60*60*24*30; // Timestamp de 30 jours à partir de maintenant
setcookie('user_id', '1712559241', $expiration, '/');


   header('location:index.php');
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
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
   <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet"/>



   <link rel="stylesheet" href="style.css">

</head>
<body>
<h1 id="title-site">Home Seeking</h1>
<?php include 'user_header.php'; ?>

<!-- home section starts  -->
<section class="home" id="home">

   <div class="swiper home-slider">

      <div class="swiper-wrapper">

         

         <div class="box swiper-slide">
            <img src="accueil2.jpg" alt="">
         </div>

         <div class="box swiper-slide">
            <img src="accueil2.jpg" alt="">
            <div class="flex">
               <h3>luxurious halls</h3>
            </div>
         </div>

      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

   </div>

</section>

<!-- home section ends -->


<!-- about section starts  -->

<section class="about" id="about">

   <div class="row">
      <div class="image">
         <img src="apropos1.jpg" alt="">
      </div>
      <div class="content">
         <h3>A big choice</h3>
         <p>our site offers you a wide selection of accommodation to meet all your needs during your studies. Whether you are looking for a private room or a roommate, we have what you need. All of our accommodations are carefully selected to provide comfort, safety and convenience</p>
      
      </div>
   </div>

   <div class="row revers">
      <div class="image">
         <img src="apropos2.jpg" alt="">
      </div>
      <div class="content">
         <h3>Welcoming rooms and apartments all around your establishment!</h3>
         <p>We understand that student life can be stressful, which is why we're here to help you find your perfect accommodation so you can focus on what really matters - your studies and your student life - . We are here to accompany you throughout the rental process and to answer all your questions</p>

      </div>
   </div>

   <div class="row">
      <div class="image">
         <img src="apropos3.jpg" alt="">
      </div>
      <div class="content">
         <h3>Save time and money!</h3>
         <p>we work with more than 200 partners to provide you with the best choice and the widest selection of furnished apartment rentals with reasonable prices for the medium or long term in Gabès. We only work with trusted partners to bring you the most secure and up-to-date offerings. We compare thousands of furnished apartment rental ads, filter by neighborhood, and view the different ads listed on the site</p>
         
      </div>
   </div>

</section>

<!-- about section ends -->

<!-- services section starts  -->

<section class="services">

   <div class="box-container">

      <div class="box">
         <img src="cadena.png" alt="">
         <h3>Security</h3>
         <p>apartments can offer a higher level of security than single-family homes, as buildings can be equipped with security systems such as surveillance cameras, intercoms and locked doors</p>
      </div>

      <div class="box">
         <img src="cout.png" alt="">
         <h3>Cost</h3>
         <p>apartments are often less expensive than detached houses, which can be a financial advantage for tenants</p>
      </div>

      <div class="box">
         <img src="ville.jpeg" alt="">
         <h3>Convenience</h3>
         <p>Apartments are often located in urban areas where amenities like shops, restaurants and public transport are easily accessible</p>
      </div>

      <div class="box">
         <img src="icon-4.png" alt="">
         <h3>Flexibility</h3>
         <p>apartments often offer greater flexibility in terms of rental length, which can be an advantage for tenants who need to move frequently or need short-term rental .

</p>
      </div>

      <div class="box">
         <img src="communaute.jpeg" alt="">
         <h3>Community</h3>
         <p>apartments often provide the opportunity to meet neighbors and connect with others in the community, which can be a social benefit for tenants.

</p>
      </div>

      <div class="box">
         <img src="amusement.jpg" alt="">
         <h3>equipment</h3>
         <p>many apartments offer amenities such as swimming pools, gyms, game rooms, outdoor areas, laundry rooms, and more. This equipment can add additional value to the rental.</p>
      </div>

   </div>

</section>

<!-- services section ends -->



<!-- gallery section starts  -->

<section class="gallery" id="gallery">

   <div class="swiper gallery-slider">
      <div class="swiper-wrapper">
         <img src="swape6.jpg" class="swiper-slide" alt="">
         <img src="swape5.jpg" class="swiper-slide" alt="">
         <img src="swape22.jpg" class="swiper-slide" alt="">
         <img src="swape1.jpg" class ="swiper-slide" alt="">
         <img src="swape4.jpg" class="swiper-slide" alt="">
         <img src="swape6.jpg" class="swiper-slide" alt="">
      </div>
      <div class="swiper-pagination"></div>
   </div>

</section>

<!-- gallery section ends -->




<?php include 'footer.php' ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="script.js"></script>

<?php include 'message.php'?>

</body>
</html>