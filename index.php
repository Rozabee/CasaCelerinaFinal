<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/');
   header('location:index.php');
}

if(isset($_POST['check'])){

   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['rooms'];
   }

   // if the hotel has total 30 rooms 
   if($total_rooms >= 30){
      $warning_msg[] = 'rooms are not available';
   }else{
      $success_msg[] = 'rooms are available';
   }

}

if(isset($_POST['book'])){

   $booking_id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $rooms = $_POST['rooms'];
   $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
   $check_out = $_POST['check_out'];
   $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
   $adults = $_POST['adults'];
   $adults = filter_var($adults, FILTER_SANITIZE_STRING);
   $childs = $_POST['childs'];
   $childs = filter_var($childs, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['rooms'];
   }

   if($total_rooms >= 30){
      $warning_msg[] = 'rooms are not available';
   }else{

      $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
      $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);

      if($verify_bookings->rowCount() > 0){
         $warning_msg[] = 'room booked alredy!';
      }else{
         $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?,?,?,?,?)");
         $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);
         $success_msg[] = 'room booked successfully!';
      }

   }

}

if(isset($_POST['send'])){

   $id = create_unique_id();
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
      $success_msg[] = 'Message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Now! Casa Celerina Farm Resort</title>
    <link href="css/styles.css" rel="stylesheet">

    <!-- font awesome cdn link -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
     <!-- swiper link  -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

     <!-- css file  -->
    <link rel="stylesheet" href="css/styles.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- home section starts -->

<section class="home" id="home"> 
        <div class="swiper home-slider">
           
            <div class="swiper-wrapper">

                <div class="box swiper-slide">
                    <img src="images/miAmoreBED (1).png" alt="">

                    <div class="flex"> 
                        <h3>Villa's</h3>
                        <a href="#availability" class="btn">Check Availability</a>
                    </div>

                </div>

                <div class="box swiper-slide">
                    <img src="images/Screenshot 2024-11-16 145759.png" alt="">
                    
                    <div class="flex">
                        <h3>Resort's Amenities</h3>
                        <a href="#reservation"  class="btn">Make a Reservation</a>
                    </div>
                </div>

                <div class="box swiper-slide">
                    <img src="images/miAmoreBED (1).png" alt="">
                    
                    <div class="flex">
                        <h3>Contact</h3>
                        <a href="#contact" class="btn">Contact Us</a>
                    </div>
                </div>
            </div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
    </div>
     </section>

<!-- home section ends -->

<!-- availability section starts  -->

<section class="availability" id="availability">
        
        <form action="" method="">
            
            <div class="flex">

                <div class="box">
                    <p>Check In <span>*</span></p>
                    <input type="date" name="check_in" class="input" required> 
                </div>

                <div class="box">
                    <p>Check Out <span>*</span></p>
                <input type="date" name="check_out" class="input" required> 
                </div>
<!-- ADULTS -->
                <div class="box">
                     <p>Adults <span>*</span></p>
                     <select name="adults" class="input" required>
                        <option value="1">1 adults</option>
                        <option value="2">2 adults</option>
                        <option value="3">3 adults</option>
                        <option value="4">4 adults</option>
                        <option value="5">5 adults</option>
                        <option value="6">6 adults</option>
                    </select>
                </div>
<!-- CHILD -->
                <div class="box">
                     <p>Childs <span>*</span></p>
                     <select name="childs" class="input" required>
                         <option value="0">0 Child</option>
                         <option value="1">1 Child</option>
                         <option value="2">2 Childs</option>
                        <option value="3">3 Childs</option>
                        <option value="4">4 Childs</option>
                        <option value="5">5 Childs</option>
                        <option value="6">6 Childs</option>
                    </select>
                </div>
                <!-- ROOMS -->
                                <div class="box">
                                    <p>Rooms <span>*</span></p>
                                    <select name="rooms" class="input" required>
                                        <option value="1">1 Room</option>
                                        <option value="2">2 Rooms</option>
                                        <option value="3">3 Rooms</option>
                                        <option value="4">4 Rooms</option>
                                        <option value="5">5 Rooms</option>
                                        <option value="6">6 Rooms</option>
                                    </select>
                                </div>
            </div>
        </div>
<!-- AVAIL -->
                <input type="submit" value="check availability" name="check" class="btn">
        
        </form>
     </section>

<!-- availability section ends -->

<!-- about section starts  -->

<section class="about" id="about">

<!-- Amenities -->
        <div class="row">
            <div class="image">
                <img src="images/miAmoreAmmenities (1).png" alt="">
            </div>
            <div class="content">
                <h3>Rooms</h3>
                <p>Lorem ipsum dolor sit amet consectetur  adipisicing elit. Exercitationem dolorem dolore tempore doloribus delectus in consectetur culpa saepe reiciendis!</p>
                <a href="#reservation" class="btn">Make a reservation.</a>
            </div>
        </div>

<!-- Contact us -->
        <div class="row revers">
            <div class="image">
                <img src="images/Screenshot 2024-11-16 182253.png" alt="">
            </div>
            <div class="content">
                <h3>Swimming Pools</h3>
                <p>Lorem ipsum dolor sit amet consectetur  adipisicing elit. </p>
                <a href="#availability" class="btn">Check Availability</a>
            </div>
        </div>
<!-- Contact us -->
        <div class="row  ">
            <div class="image">
                <img src="images/miAmoreBED (3).png" alt="">
            </div>
            <div class="content">
                <h3>More Information</h3>
                <p>Lorem ipsum dolor sit amet consectetur  adipisicing elit. </p>
                <a href="#contact" class="btn">Contact Us</a>
            </div>
        </div>

     </section>

<!-- about section ends -->

<!-- services section starts  -->

<section class="services"> 
        <div class="box-container">

            <div class="box">
                <img src="images/firepit.png" alt="firepit">
                <h3>Fire Pit</h3>
                <p>(With minimal fee for fire wood)
                </p>
            </div>

            <div class="box">
                <img src="images/adultpool-removebg-preview.png" alt="">
                <h3>1 Main Pool</h3>
                <p>(5FT. DEEP)
                </p>
            </div>
            
            <div class="box">
                <img src="images/kiddiepool.png" alt="">
                <h3>1 Kiddie Pool</h3>
                <p>(2FT. DEEP)
                </p>
            </div>

            <div class="box">
                <img src="images/tent.png" alt=" ">
                <h3>Free Tent Pitching</h3>
                <p>
                </p>
            </div>
            
            <div class="box">
                <img src="images/bar.png" alt=" ">
                <h3>Bar Lounge</h3>
                <p>
                </p>
            </div>

            <div class="box">
                <img src="images/beanbag.png" alt=" ">
                <h3>Bean Bag Lounge</h3>
                <p>
                </p>
            </div>

        </div>
     </section>

<!-- services section ends -->

<!-- reservation section starts  -->
       

<section class="reservation" id="reservation">

   <form action="" method="post">
      <h3>make a reservation</h3>
      <div class="flex">
         <div class="box">
            <p>your name <span>*</span></p>
            <input type="text" name="name" maxlength="50" required placeholder="enter your name" class="input">
         </div>
         <div class="box">
            <p>your email <span>*</span></p>
            <input type="email" name="email" maxlength="50" required placeholder="enter your email" class="input">
         </div>
         <div class="box">
            <p>your number <span>*</span></p>
            <input type="number" name="number" maxlength="10" min="0" max="9999999999" required placeholder="enter your number" class="input">
         </div>
         <div class="box">
            <p>rooms <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1" selected>1 room</option>
               <option value="2">2 rooms</option>
               <option value="3">3 rooms</option>
               <option value="4">4 rooms</option>
               <option value="5">5 rooms</option>
               <option value="6">6 rooms</option>
            </select>
         </div>
         <div class="box">
            <p>check in <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>check out <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1" selected>1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>
         <div class="box">
            <p>childs <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="0" selected>0 child</option>
               <option value="1">1 child</option>
               <option value="2">2 childs</option>
               <option value="3">3 childs</option>
               <option value="4">4 childs</option>
               <option value="5">5 childs</option>
               <option value="6">6 childs</option>
            </select>
         </div>
      </div>
      <input type="submit" value="book now" name="book" class="btn">
   </form>

</section>

<!-- reservation section ends -->

<!-- gallery section starts  -->

<section class="gallery" id="gallery">

    <div class="swiper gallery-slider">
        <div class="swiper-wrapper">
            <img src="images\327144990_6349062595126277_2442200509841659706_n.jpg" class="swiper-slide" alt="">
            <img src="images\370013929_256227737309533_4451094044501936050_n.jpg" class="swiper-slide" alt="">
            <img src="images\334170250_1913386622345703_1229650991678046382_n.jpg" class="swiper-slide" alt="">
            <img src="images\334590741_948091002861048_5902014935305171334_n.jpg" class="swiper-slide" alt="">
            <img src="images\417120259_330773156521657_2344987066291864358_n.jpg" class="swiper-slide" alt="">
            <img src="images\423132843_344113185187654_7623732524242065427_n.jpg" class="swiper-slide" alt="">
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- gallery section ends -->

<!-- contact section starts  -->

<section class="contact" id="contact">
<div class="row">
    <form action="" method="post">
        <h3>Send us a Message</h3>
        <input type="text" name="name" required maxlength="50" placeholder="Enter your name:" class="box">
        <input type="email" name="email" required maxlength="50" placeholder="Enter your Email:" class="box">
        <input type="number" name="number" required maxlength="11"  min="0" max="9999999999" placeholder="Enter your number:" class="box">
        <textarea name="message" class="box" required maxlength="1000" placeholder="Enter your message:" cols="30" rows="10"></textarea>

        <input type="submit" value="Send Message" name="send" class="btn">
    </form>

    <div class="faq">
        <h3 class="title">Frequently Asked Question</h3>
        <div class="box active">
            <h3>How to cancel?</h3>
            <p>Contact us.</p>
        </div>
        <div class="box">
            <h3>What are payment methods?</h3>
            <p>Currently under maintanance. Thank you for your patience</p>
        </div>          
        <div class="box">
            <h3>Is there any vacancy?</h3>
            <p>Please check the availability.</p>
        </div>
        <div class="box">
            <h3>What are the age requirement?</h3>
            <p>Individuals under 18 years old must have atleast 1 Adult companion. </p>
        </div>
        <div class="box">
            <h3>How ?</h3>
            <p>Kanlurang Robles, Barangay Masalukot 2 4323 Candelaria, Philippines</p>
            <p class="map"><<iframe src="https://www.google.com/maps/embed?pb=!1m24!1m12!1m3!1d24363.68735832311!2d121.41586899931474!3d13.936313983598925!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m9!3e6!4m3!3m2!1d13.936089599999999!2d121.4334645!4m3!3m2!1d13.942650899999999!2d121.4188755!5e1!3m2!1sen!2sph!4v1732432181572!5m2!1sen!2sph" width="500" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></p>
        </div>
    </div>
</div>
</section>

<!-- contact section ends -->

<!-- reviews section starts  -->

<section class="reviews" id="reviews">
    <div class="swiper reviews-slider">

        <div class="swiper-wrapper">
            <div class="swiper-slide box">
            <img src="images/jasper.png" alt="">
                <h3>Jasper Marquez</h3>
                <p>The most affordable yet relaxing private resort that I've been to recently. Ang bait po ng may ari and caretaker nila. Maganda yung place and malawak meron siyang mga area lalo na if you want to have small talks din. + the free bonfire 🔥tapos for 30 pax yung place. Free wifi na rin siya. Perfect sa mga events and surprises. Success ang plano namin hehe. Will definitely come back kasama naman ang buong tropa at pamilya.</p>
            </div>
            <div class="swiper-slide box">
                <img src="images/matt.png" alt="">
                <h3>Matt Willis</h3>
                <p>relaxing place , affordable and beautiful view super bait din ng may ari and si kua care taker.</p>
            </div>
            <div class="swiper-slide box">
                <img src="images/jenny.png" alt="">
                <h3>Jenny Ollero Castillo</h3>
                <p>very nice & relaxing place , clean surroundings, accommodating owner, ang view perfect sa nature lover.</p>
            </div>
            <div class="swiper-slide box">
                <img src="images/jenny.png" alt="">
                <h3>Jenny Ollero Castillo</h3>
                <p>very nice & relaxing place , clean surroundings, accommodating owner, ang view perfect sa nature lover.</p>
            </div>
            <div class="swiper-slide box">
                <img src="images/jenny.png" alt="">
                <h3>Jenny Ollero Castillo</h3>
                <p>very nice & relaxing place , clean surroundings, accommodating owner, ang view perfect sa nature lover.</p>
            </div>
           
        </div>
        <div class="swiper-pagination"></div>
    </div>


</section>

<!-- reviews section ends  -->


<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom javascipt -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>