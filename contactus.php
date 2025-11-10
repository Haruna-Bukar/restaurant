<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Pitti Restaurant</title>
  <link rel="stylesheet" href="contactus.css">
  <style>
    /* Footer */
footer{padding:20px 16px;background:#fff;border-top:1px solid rgba(0,0,0,0.06)}
.footer-inner{max-width:var(--max-width);margin:0 auto;text-align:center;color:#666}

  </style>
</head>
<body>

<header>
  <div class="com">
      <div class="logo"><img src="assets/image.png" alt=""></div>
      <nav class="main-navigation">
        <ul class="nav-list">
          <li class="nav-item"><a href="index.php" class="nav-link">Homepage</a></li>
          <li class="nav-item"><a href="menu.php" class="nav-link">Menu</a></li>
          <li class="nav-item"><a href="about.php" class="nav-link">AboutUs</a></li>
          <li class="nav-item"><a href="contactus.php" class="nav-link">ContactUs</a></li>
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
  <div class="contact-container">
    <h1>Contact Us</h1>

    <div class="contact-info">
      <div class="info-box">
        <h3>📍 Our Location</h3>
        <p>Pitti Restaurant</p>
        <p>123 Food Street, Lagos, Nigeria</p>
      </div>

      <div class="info-box">
        <h3>📞 Call Us</h3>
        <p>+234 806 439 9622</p>
        <p>+234 904 700 8268</p>
      </div>

      <div class="info-box">
        <h3>📧 Email Us</h3>
        <p>info@pittirestaurant.com</p>
        <p>support@pittirestaurant.com</p>
      </div>
    </div>

    <form action="send_message.php" method="POST">
      <h3 style="color:#ff9800;">Send Us a Message</h3>
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" rows="5" placeholder="Your Message..." required></textarea>
      <input type="submit" value="Send Message">
    </form>

    <!-- Google Maps Embed -->
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3941.6849782677724!2d7.4951!3d9.0579!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104dd4f25b96a3af%3A0x92a38c3a263ea5cb!2sLagos%2C%20Nigeria!5e0!3m2!1sen!2sng!4v1698074356111!5m2!1sen!2sng" 
      allowfullscreen="" loading="lazy">
    </iframe>
  </div>

  <footer>
         <div class="footer-inner">&copy; <span id="year"></span> Pitti Restaurant — All rights reserved</div>
  </footer>

  <Script>
      // Populate year
                document.getElementById('year').textContent = new Date().getFullYear();
                // mobile search (placeholder)
                document.getElementById('mobile-search').addEventListener('click', function(){
                        alert('Search is a preview-only feature on this page.');
                });

  </Script>
</body>
</html>
