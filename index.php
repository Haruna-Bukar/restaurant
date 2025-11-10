<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php
// Converted from tab.php: HTML moved here, styles exported to index.css
?>
<!doctype html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Pitti Restaurant</title>
        <link rel="stylesheet" href="index.css">
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
                                        <li><a href="about.php">AboutUs</a></li>
                                        <li><a href="contactus.php">ContactUs</a></li>
                                </ul>
                        </nav>

                        <div class="header-actions">
                                <a class="action-btn" href="login.php" title="Sign in"><span style="color:var(--muted)">&#128100;</span></a>
                                <button class="action-btn" id="mobile-search" title="Search">🔍</button>
                        </div>
                </div>
        </header>

        <main>
                <section class="hero">
                        <div class="hero-inner">
                                <div class="eyebrow">The Finest Cuts in the City</div>
                                <h1 class="hero-title">Welcome to <span class="accent">Pitti Restaurant</span></h1>
                                <p class="hero-sub">Savor handcrafted meals made from fresh ingredients. Family-friendly, cozy, and ready to serve you the finest flavors.</p>
                                <div class="hero-cta">
                                        <a class="btn" href="menu.php" id="order-now">Order Now</a>
                                        <a class="btn btn-outline" href="reservation.php" id="reserve-table">Reserve Table</a>
                                </div>
                        </div>
                </section>

                <section class="cards" aria-label="Highlights">
                        <article class="card">
                                <h3>Fresh Ingredients</h3>
                                <p>We pick only the best seasonal produce and premium meats to prepare every dish.</p>
                        </article>
                        <article class="card">
                                <h3>Cozy Ambience</h3>
                                <p>Relax in a warm, welcoming setting perfect for friends and family.</p>
                        </article>
                        <article class="card">
                                <h3>Open Hours</h3>
                                <p>Mon–Sun 10:00 — 22:00. Walk-ins welcome, reservations available.</p>
                        </article>
                </section>
        </main>

        <footer>
                <div class="footer-inner">&copy; <span id="year"></span> Pitti Restaurant — All rights reserved</div>
        </footer>

        <script>
                // Populate year
                document.getElementById('year').textContent = new Date().getFullYear();
                // mobile search (placeholder)
                document.getElementById('mobile-search').addEventListener('click', function(){
                        alert('Search is a preview-only feature on this page.');
                });
        </script>
</body>
</html>