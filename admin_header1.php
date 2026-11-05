<!DOCTYPE html>
<html>
<head>
  <title>Admin Header</title>
  <style>
    .navbar {
      display: flex;
      justify-content: space-around;
      width: 100%;
    }

    .navbar a {
      text-align: center;
    }
  </style>
</head>
<body>
  <header class="header">
    <section class="flex">
      <a class="logo">Admin space</a>
      <nav class="navbar">
        <a href="login.php">home</a>
        <a href="register.php">register</a>
        <a href="login.php">login</a>
        <a href="#" onclick="logout()">logout</a>

<script>
function logout() {
  if (confirm('Se déconnecter de ce site ?')) {
    window.location.href = 'login.php';
  }
}
</script>

      </nav>
      <div id="menu-btn" class="fas fa-bars"></div>
    </section>
  </header>
</body>
</html>
