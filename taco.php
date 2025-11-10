<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Pitti Restaurant — Menu</title>
  <link rel="stylesheet" href="menus.css">
</head>

<body>
  <header>
  <div class="com">
      <div class="logo"><img src="assets/image.png" alt=""></div>
      <nav class="main-navigation">
        <ul class="nav-list">
          <li class="nav-item"><a href="index.php" class="nav-link">Homepage</a></li>
          <li class="nav-item"><a href="menu.php" class="nav-link">Menu</a></li>
          <li class="nav-item"><a href="aboutus.php" class="nav-link">About Us</a></li>
          <li class="nav-item"><a href="contactus.php" class="nav-link">Contact Us</a></li>
        </ul>
      </nav>
      <div class="icon-links">
        <a href="index.php"> <i class="fas fa-home"></i></a>
        <a href="login.php"> <i class="fas fa-user"></i></a>
        <a href="#" id="search-btn"> <i class="fas fa-search"></i></a>
      </div>
    </div>

    <div class="search-box" id="search-box">
      <input type="text" placeholder="search...">
    </div>

    </div>

  </header>

  <div>
<h1>Pitti Restaurant — Menu</h1>
<p class="lead">Enjoy our delicious meals — 4 per row layout.</p>
</div>


  <section class="menu-wrap">
    <div class="menu" id="menu">

      <!-- === PHP LOOP STARTS HERE === -->
      <?php
      include 'db.php';
      $result = mysqli_query($conn, "SELECT * FROM tbl_menu");

      while ($row = mysqli_fetch_assoc($result)) {
        echo "
        <article class='menu-item'>
          <div class='thumb' style='background-image:url(\"photos/{$row['image']}\");'></div>
          <div class='title-row'>
            <div class='name'>{$row['name']}</div>
            <div class='price'>₦{$row['price']}</div>
          </div>
          <div class='desc'>{$row['description']}</div>
        </article>
        ";
      }
      ?>
      <!-- === PHP LOOP ENDS HERE === -->

    </div>
  </section>
</body>

</html>
