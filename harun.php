<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Pitti Restaurant — Menu (Harun)</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Single consolidated stylesheet -->
  <link rel="stylesheet" href="harun.css">
</head>

<body>
  <header>
    <div class="com">
      <div class="logo"><img src="assets/image.png" alt=""></div>
      <nav class="main-navigation">
        <ul class="nav-list">
          <li class="nav-item"><a href="index.php" class="nav-link">Homepage</a></li>
          <li class="nav-item"><a href="menu.php" class="nav-link">Menu</a></li>
          <li class="nav-item"><a href="about.php" class="nav-link">About Us</a></li>
          <li class="nav-item"><a href="contactus.php" class="nav-link">Contact Us</a></li>
        </ul>
      </nav>

      <div class="icon-links">
        <a href="index.php"> <i class="fas fa-home"></i></a>

        <div class="user-dropdown">
          <i class="fas fa-user"></i>
          <div class="user-menu">
            <a href="login.php">Login</a>
            <a href="registration.php">Sign Up</a>
          </div>
        </div>

        <a href="#" id="search-btn"> <i class="fas fa-search"></i></a>

        <a href="cart.php" style="position:relative;">
          <i class="fas fa-shopping-cart"></i>
          <span class="cart-badge">0</span>
        </a>
      </div>
    </div>

    <div class="search-box" id="search-box">
      <input type="text" placeholder="search...">
    </div>
  </header>

  <div>
    <h1>Pitti Restaurant — Menu</h1>
    <p class="lead">Enjoy our delicious meals.</p>
  </div>

  <section class="menu-wrap">
    <div class="menu" id="menu">

      <?php
      include 'db.php';
      $result = mysqli_query($conn, "SELECT * FROM tbl_menu");

      while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['menu_id'];
        $des = substr($row['description'], 0, 69);

        echo "
        <article class='menu-item' onclick=\"openModal('{$id}')\">
          <div class='thumb' style='background-image:url(photos/{$row['image']});'></div>
          <div class='title-row'>
            <div class='name'>{$row['name']}</div>
            <div class='price'>₦{$row['price']}</div>
          </div>
          <div class='desc'>{$des}</div>
        </article>

        <div class='modal' id='modal-{$id}'>
          <div class='modal-content'>
            <span class='close-btn' onclick=\"closeModal('{$id}')\">&times;</span>
            <img src='photos/{$row['image']}' alt='{$row['name']}'>
            <h2>{$row['name']}</h2>
            <p>{$row['description']}</p>
            <h3 class='price'>Price: ₦{$row['price']}</h3>
            <button class='btn'> Order Now </button>
          </div>
        </div>
        ";
      }
      ?>
    </div>
  </section>

  <script>
    function openModal(id) {
      document.getElementById("modal-" + id).style.display = "flex";
      document.body.style.overflow = "hidden";
    }

    function closeModal(id) {
      document.getElementById("modal-" + id).style.display = "none";
      document.body.style.overflow = "auto";
    }
  </script>

</body>

</html>
