<?php
// About Us page
session_start()
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>About Us — Pitti Restaurant</title>
  <link rel="stylesheet" href="about.css">
</head>
<body>
  <header class="site-header">
    <div class="header-inner">
      <a class="brand" href="index.php">
        <img src="assets/image.png" alt="Pitti logo">
        <strong style="color:#fff;">Pitti Restaurant</strong>
      </a>

      <nav aria-label="Main navigation">
        <ul class="nav-list">
             <?php if (isset($_SESSION['customer_id'])):  ?>
            <li><a href="customer_dashboard.php">Dashboard</a></li>
            <?php endif; ?>
          <li><a href="index.php">Homepage</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a class="active" href="about.php">AboutUs</a></li>
          <li><a href="contactus.php">ContactUs</a></li>
        </ul>
      </nav>

      <div class="header-actions">
        <a class="action-btn" href="login.php" title="Sign in">&#128100;</a>
        <button class="action-btn" id="mobile-search" title="Search">🔍</button>
      </div>
    </div>
  </header>

  <main>
    <section class="about-hero">
      <div class="about-hero-inner">
        <h1>About Pitti Restaurant</h1>
        <p class="lead">A family-run restaurant serving quality, seasonal dishes with a focus on flavour and hospitality.</p>
      </div>
    </section>

    <section class="about-content">
      <div class="container">
        <div class="story">
          <h2>Our Story</h2>
          <p>Founded with a passion for honest cooking, Pitti Restaurant began as a small neighborhood spot. Our mission is to bring thoughtfully prepared meals to our community — food that comforts, delights, and brings people together.</p>
          <p>We source local ingredients whenever possible and prepare dishes with care. Every plate reflects a commitment to quality and to the people who enjoy our food.</p>
        </div>

        <aside class="image-card">
          <img src="assets/image1.png" alt="Our restaurant interior">
        </aside>
      </div>
    </section>

    <section class="team">
      <div class="max">
        <h2>Meet The Team</h2>
        <div class="team-grid">
          <div class="member">
            <img src="assets/habiba.jpg" alt="Chef">
            <h3>Chef Habiba</h3>
            <p>Head chef and recipe creator, inspired by traditional and modern flavors.</p>
          </div>
          <div class="member">
            <img src="assets/emma.jpg" alt="Manager">
            <h3>Emmanuel</h3>
            <p>Front of house manager — the friendly face you see when you arrive.</p>
          </div>
          <div class="member">
            <img src="assets/image.png" alt="Team">
            <h3>The Team</h3>
            <p>A small, dedicated crew that treats every guest like family.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="values">
      <div class="max">
        <h2>Our Values</h2>
        <div class="values-grid">
          <div class="value">Quality Ingredients</div>
          <div class="value">Sustainable Sourcing</div>
          <div class="value">Warm Hospitality</div>
        </div>
      </div>
    </section>

    <section class="cta-strip">
      <div class="max">
        <p>Curious to try our menu? Book a table or drop by — we'd love to meet you.</p>
        <a class="btn" href="menu.php">See Menu</a>
      </div>
    </section>
  </main>

  <footer>
    <div class="footer-inner">&copy; <span id="year"></span> Pitti Restaurant — All rights reserved</div>
  </footer>

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();
    document.getElementById('mobile-search').addEventListener('click', function(){ alert('Search placeholder'); });
  </script>
</body>
</html>
