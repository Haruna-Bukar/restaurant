<?php
// customer_dashboard.php
session_start();
include 'db.php';

// Require login
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php?redirect=" . urlencode($_SERVER['PHP_SELF']));
  exit();
}
$customer_id = $_SESSION['customer_id'];
$firstname = $_SESSION['firstname'] ?? '';
$lastname = $_SESSION['lastname'] ?? '';

// Mark notifications as seen endpoint (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_read') {
  $q = "UPDATE tbl_order SET is_notified = 1 WHERE customer_id = ? AND status = 'confirmed'";
  $stmt = $conn->prepare($q);
  $stmt->bind_param("i", $customer_id);
  $stmt->execute();
  echo json_encode(['ok' => true]);
  exit();
}

// Fetch quick stats
$tot = $conn->query("SELECT COUNT(*) AS c FROM tbl_order WHERE customer_id = $customer_id")->fetch_assoc()['c'] ?? 0;
$pending = $conn->query("SELECT COUNT(*) AS c FROM tbl_order WHERE customer_id = $customer_id AND status='pending'")->fetch_assoc()['c'] ?? 0;
$confirmed = $conn->query("SELECT COUNT(*) AS c FROM tbl_order WHERE customer_id = $customer_id AND status='confirmed'")->fetch_assoc()['c'] ?? 0;
$completed = $conn->query("SELECT COUNT(*) AS c FROM tbl_order WHERE customer_id = $customer_id AND status='completed'")->fetch_assoc()['c'] ?? 0;

// Unread confirmed orders for notifications
$notifRes = $conn->query("SELECT order_id, total_price, order_type, ticket_code, order_date FROM tbl_order WHERE customer_id = $customer_id AND status='confirmed' AND is_notified = 0 ORDER BY order_date DESC");

// Today's specials for slider (simple)
$specialsRes = $conn->query("SELECT menu_id, name, image FROM tbl_menu WHERE special = 1 LIMIT 5");

// Favorite / sample items (top 4)
$favRes = // Favourite items based on customer's order history
  $favRes = $conn->query("
    SELECT m.menu_id, m.name, m.image, m.price, COUNT(o.menu_id) AS order_count
    FROM order_items o
    JOIN tbl_menu m ON o.menu_id = m.menu_id
    JOIN tbl_order t ON o.order_id = t.order_id
    WHERE t.customer_id = $customer_id
    GROUP BY m.menu_id
    ORDER BY order_count DESC
    LIMIT 4
");
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Customer Dashboard — Pitti Restaurant</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="harun.css">
  <style>
    :root {
      --primary: #ff9800;
      --dark: #0f1724;
      --card: #1c2942;
      --muted: #9aa6b2;
      --light: #ffffff;
      --maxw: 11100px;
    }

    * {
      box-sizing: border-box
    }

    body {
      margin: 0;
      font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, Arial;
      background: linear-gradient(180deg, var(--dark), #071021 140%);
      color: var(--light);
      min-height: 100vh;
      padding: 22px;
      display: flex;
      justify-content: center
    }

    .container {
      width: 100%;
      max-width: var(--maxw)
    }

    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 22px
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px
    }

    .brand img {
      height: 46px;
      border-radius: 8px
    }

    .search {
      flex: 1;
      margin: 0 18px
    }

    .search input {
      width: 100%;
      padding: 10px 12px;
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, 0.04);
      background: transparent;
      color: var(--light)
    }

    .top-actions {
      display: flex;
      align-items: center;
      gap: 14px
    }

    .icon-btn {
      position: relative;
      padding: 8px;
      border-radius: 8px;
      background: transparent;
      border: 0;
      color: var(--muted);
      cursor: pointer
    }

    .icon-btn .badge {
      position: absolute;
      top: -6px;
      right: -6px;
      background: #e04b4b;
      color: white;
      padding: 3px 6px;
      border-radius: 50%;
      font-size: 12px
    }

    /* cards */
    .grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 18px
    }

    .card {
      background: var(--card);
      padding: 18px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(2, 8, 23, 0.5);
      min-height: 84px
    }

    .card h3 {
      margin: 0;
      font-size: 1.2rem;
      color: var(--primary)
    }

    .card p {
      margin: 6px 0 0;
      color: var(--muted)
    }

    @media(max-width:900px) {
      .grid {
        grid-template-columns: repeat(2, 1fr)
      }
    }

    /* content layout */
    .main {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 18px
    }

    @media(max-width:900px) {
      .main {
        grid-template-columns: 1fr
      }
    }

    /* Specials slider */
    .slider {
      background: transparent;
      padding: 12px;
      border-radius: 12px;
      margin-bottom: 14px
    }

    .slides {
      display: flex;
      gap: 12px;
      overflow: hidden
    }

    .slide {
      min-width: 260px;
      height: 140px;
      border-radius: 12px;
      background-size: cover;
      background-position: center;
      box-shadow: 0 8px 24px rgba(2, 8, 23, 0.6);
      display: flex;
      align-items: flex-end;
      padding: 12px;
      color: #fff;
      font-weight: 700
    }

    /* favourites */
    .favs {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px
    }

    .fav-item {
      background: var(--card);
      padding: 10px;
      border-radius: 10px;
      display: flex;
      gap: 12px;
      align-items: center
    }

    .fav-item img {
      width: 72px;
      height: 60px;
      border-radius: 8px;
      object-fit: cover
    }

    .fav-item .meta {
      color: var(--muted)
    }

    @media(max-width:900px) {
      .favs {
        grid-template-columns: 1fr
      }
    }

    /* notifications */
    .notify-box {
      background: var(--card);
      padding: 12px;
      border-radius: 12px
    }

    .notify-list {
      max-height: 220px;
      overflow: auto
    }

    .notify-item {
      padding: 10px;
      border-bottom: 1px dashed rgba(255, 255, 255, 0.03);
      display: flex;
      justify-content: space-between;
      gap: 8px;
      align-items: center
    }

    .notify-item:last-child {
      border-bottom: none
    }

    .notify-item .meta {
      color: var(--muted);
      font-size: 0.95rem
    }

    .btn {
      background: var(--primary);
      color: #fff;
      padding: 10px 12px;
      border-radius: 8px;
      text-decoration: none;
      display: inline-block;
      font-weight: 700;
      border: 0;
      cursor: pointer
    }

    .small {
      padding: 6px 10px;
      font-size: 0.9rem
    }

    /* profile dropdown */
    .profile {
      display: flex;
      align-items: center;
      gap: 10px
    }

    .profile .name {
      font-weight: 700
    }

    .profile .avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #fff;
      color: #000;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700
    }

    /* utils */
    .h-1 {
      font-size: 1rem;
      color: var(--muted);
      margin-bottom: 8px
    }

    .center {
      display: flex;
      align-items: center;
      gap: 8px
    }
  </style>
</head>

<body>
  <div class="container">

    <!-- TOP BAR -->
    <div class="topbar">
      <div class="brand">
        <img src="assets/image.png" alt="logo">
        <div>
          <div style="font-weight:800;font-size:18px">Pitti Restaurant</div>
          <div style="font-size:12px;color:var(--muted)">Delicious delivered & dining</div>
        </div>
      </div>

      <div class="search">
        <input type="text" placeholder="Search menu or orders...">
      </div>

      <div class="top-actions">
        <!-- Notification bell -->
        <button class="icon-btn" id="bellBtn" title="Notifications">
          <i class="fa-solid fa-bell"></i>
          <?php
          $unreadCount = $notifRes->num_rows;
          if ($unreadCount > 0) echo "<span class='badge' id='notifCount'>{$unreadCount}</span>";
          ?>
        </button>

        <!-- quick profile -->
        <div class="profile">
          <div class="name">Hello, <?= htmlspecialchars($firstname ? $firstname : 'Guest') ?></div>
          <div class="avatar"><?= strtoupper(substr($firstname, 0, 1) ?: 'U') ?></div>
          <div style="margin-left:8px">
            <a href="profile.php" class="btn small">Profile</a>
          </div>
        </div>
      </div>
    </div>

    <!-- CARDS -->
    <div class="grid">
      <div class="card">
        <h3><?= $tot ?></h3>
        <p>Total Orders</p>
      </div>
      <div class="card">
        <h3><?= $pending ?></h3>
        <p>Pending</p>
      </div>
      <div class="card">
        <h3><?= $confirmed ?></h3>
        <p>Confirmed</p>
      </div>
      <div class="card">
        <h3><?= $completed ?></h3>
        <p>Completed</p>
      </div>
    </div>

    <div class="main">
      <!-- LEFT: Specials + Favourites -->
      <div>
        <div class="h-1">Today's Specials</div>
        <div class="slider" id="specialSlider">
          <div class="slides" id="slides">
            <?php
            if ($specialsRes && $specialsRes->num_rows) {
              while ($s = $specialsRes->fetch_assoc()) {
                $img = 'photos/' . ($s['image'] ?: 'placeholder.jpg');
                echo "<div class='slide' style=\"background-image:url('{$img}')\">{$s['name']}</div>";
              }
            } else {
              // fallback sample
              echo "<div class='slide' style=\"background-image:url('assets/image.png')\">Chef's Pick</div>";
            }
            ?>
          </div>
        </div>

        <div style="height:10px"></div>

        <div class="h-1">Favourite Items</div>
        <div class="favs">
          <div class="favs">
            <?php
            if ($favRes && $favRes->num_rows > 0) {
              while ($f = $favRes->fetch_assoc()) {
                $img = 'photos/' . ($f['image'] ?: 'placeholder.jpg');
                echo "
            <div class='fav-item'>
                <img src='{$img}' alt=''>
                <div>
                    <div style='font-weight:700'>{$f['name']}</div>
                    <div class='meta'>₦{$f['price']}</div>
                    <div class='meta'>Ordered: {$f['order_count']}x</div>
                </div>
            </div>";
              }
            } else {
              // Fallback: latest 4 menu items
              $fallback = $conn->query("SELECT menu_id, name, image, price FROM tbl_menu ORDER BY menu_id DESC LIMIT 4");
              while ($f = $fallback->fetch_assoc()) {
                $img = 'photos/' . ($f['image'] ?: 'placeholder.jpg');
                echo "
            <div class='fav-item'>
                <img src='{$img}' alt=''>
                <div>
                    <div style='font-weight:700'>{$f['name']}</div>
                    <div class='meta'>₦{$f['price']}</div>
                </div>
            </div>";
              }
            }
            ?>
          </div>

        </div>
      </div>

      <!-- RIGHT: Notifications & Quick Actions -->
      <div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
          <div class="h-1">Notifications</div>
          <button class="btn small" id="markReadBtn">Mark all as read</button>
        </div>

        <div class="notify-box">
          <div class="notify-list" id="notifyList">
            <?php
            if ($notifRes && $notifRes->num_rows) {
              while ($n = $notifRes->fetch_assoc()) {
                $oid = $n['order_id'];
                $tp = number_format($n['total_price'], 2);
                $ticket = htmlspecialchars($n['ticket_code'] ?: "N/A");
                $date = date("d M Y H:i", strtotime($n['order_date']));
                echo "<div class='notify-item'>
                          <div>
                            <div style='font-weight:700'>Order #{$oid}</div>
                            <div class='meta'>Total ₦{$tp} • {$date}</div>
                          </div>
                          <div style='text-align:right'>
                            <a class='btn small' href='download_ticket.php?order_id={$oid}' target='_blank'>Download</a>
                          </div>
                        </div>";
              }
            } else {
              echo "<div style='padding:12px;color:var(--muted)'>No new notifications</div>";
            }
            ?>
          </div>

          <div style="margin-top:12px;display:flex;gap:8px;justify-content:space-between;align-items:center">
            <a class="btn" href="menu.php">Order More</a>
            <a class="btn" href="my_orders.php">View All Orders</a>
          </div>
        </div>

        <div style="height:14px"></div>

        <div class="h-1">Quick Actions</div>
        <div style="display:flex;gap:10px;margin-top:8px;flex-wrap:wrap">
          <a class="btn" href="menu.php">Order Food</a>
          <a class="btn" href="profile.php">Edit Profile</a>
          <a class="btn" href="logout.php">Logout</a>
        </div>
      </div>
    </div>

  </div>

  <script>
    // Simple slider auto-scroll
    (function() {
      const slides = document.getElementById('slides');
      if (!slides) return;
      let idx = 0;
      const children = slides.children;
      if (children.length <= 1) return;
      setInterval(() => {
        idx = (idx + 1) % children.length;
        slides.style.transform = `translateX(-${idx * (children[0].offsetWidth + 12)}px)`;
      }, 3000);
    })();

    // Mark notifications as read
    document.getElementById('markReadBtn').addEventListener('click', function() {
      fetch('', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'action=mark_read'
        })
        .then(r => r.json())
        .then(j => {
          if (j.ok) {
            // remove badge & list
            const b = document.getElementById('notifCount');
            if (b) b.remove();
            const nl = document.getElementById('notifyList');
            nl.innerHTML = '<div style=\"padding:12px;color:var(--muted)\">No new notifications</div>';
          }
        }).catch(console.error);
    });
  </script>
</body>

</html>