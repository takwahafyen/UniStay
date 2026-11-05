<header class="header">

   <section class="flex">

      <a class="logo">Admin space</a>

      <nav class="navbar">
         <a href="dashboard.php">home</a>
         <a href="bookings.php">bookings</a>
         <a href="admins.php">admins</a>
         <a href="messages.php">messages</a>
         <a href="register.php">register</a>
         <a href="index.php">login</a>
         <a href="#" onclick="logout()">logout</a>

<script>
function logout() {
  if (confirm('Se déconnecter de ce site ?')) {
    window.location.href = 'index.php';
  }
}
</script>


      <div id="menu-btn" class="fas fa-bars"></div>

   </section>

</header>