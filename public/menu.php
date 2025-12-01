<?php
session_start();
include '../includes/db.php';

// Check login
$customer_id = $_SESSION['customer_id'] ?? null;

// 🔹 Ensure DB has the new columns (run once)
mysqli_query($conn, "ALTER TABLE tbl_menu ADD COLUMN IF NOT EXISTS special TINYINT(1) NOT NULL DEFAULT 0");
mysqli_query($conn, "ALTER TABLE tbl_order ADD COLUMN IF NOT EXISTS is_notified TINYINT(1) NOT NULL DEFAULT 0");
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Pitti Restaurant — Menu</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/harun.css">
  <style>
    /* Minimal inline style to handle special badge positioning */
    .special-badge { color: orange; font-weight: 700; margin-left: 6px; font-size: 0.9rem; }
  </style>
</head>

<body>
  <header>
    <div class="com">
      <div class="logo"><img src="../assets/image.png" alt=""></div>
      <nav class="main-navigation">
        <ul class="nav-list">
           <?php if (isset($_SESSION['customer_id'])):  ?>
          <li><a href="../customer/customer_dashboard.php" class="nav-link">Dashboard</a></li>
          <?php endif; ?>
          <li><a href="index.php" class="nav-link">Homepage</a></li>
          <li><a href="menu.php" class="nav-link">Menu</a></li>
          <li><a href="about.php" class="nav-link">AboutUs</a></li>
          <li><a href="contactus.php" class="nav-link">ContactUs</a></li>
        </ul>
      </nav>

      <div class="icon-links">
        <a href="index.php"><i class="fas fa-home"></i></a>

        <?php if ($customer_id): ?>
        <a href="customer/my_orders.php" class="notification" id="notification-bell">
          <i class="fas fa-bell"></i>
          <?php
          $result = mysqli_query($conn, "SELECT COUNT(*) AS ready_count FROM tbl_order WHERE customer_id=$customer_id AND status='confirmed' AND is_notified=0");
          $row = mysqli_fetch_assoc($result);
          if ($row['ready_count'] > 0) {
              echo "<span class='badge'>{$row['ready_count']}</span>";
          }
          ?>
        </a>
        <?php else: ?>
        <div class="user-dropdown">
          <i class="fas fa-user"></i>
          <div class="user-menu">
            <a href="login.php">Login</a>
            <a href="registration.php">Sign Up</a>
             <a href="../customer/customer_dashboard.php">Dashboard</a>
          </div>
        </div>
        <?php endif; ?>

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
      $result = mysqli_query($conn, "SELECT * FROM tbl_menu");
      while ($row = mysqli_fetch_assoc($result)) {
          $id = $row['menu_id'];
          $des = substr($row['description'], 0, 69);
          $specialBadge = $row['special'] ? "<span class='special-badge'>Today’s Special</span>" : "";

          echo "
          <article class='menu-item' onclick=\"openModal('{$id}')\">
            <div class='thumb' style='background-image:url(../photos/{$row['image']});'></div>
            <div class='title-row'>
              <div class='name'>{$row['name']} {$specialBadge}</div>
              <div class='price'>₦{$row['price']}</div>
            </div>
            <div class='desc'>{$des}</div>
            <div class='actions'>
              <a href='#' class='btn small add-to-cart' data-id='{$id}' data-name='{$row['name']}' data-price='{$row['price']}' onclick=\"event.stopPropagation();\">Order</a>
            </div>
          </article>

          <div class='modal' id='modal-{$id}'>
            <div class='modal-content'>
              <span class='close-btn' onclick=\"closeModal('{$id}')\">&times;</span>
              <img src='../photos/{$row['image']}' alt='{$row['name']}'>
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

  <!-- MODAL SCRIPT -->
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

  <!-- CART SCRIPT -->
  <script>
    (function() {
      function getCart() { return JSON.parse(localStorage.getItem('cart') || '[]'); }
      function saveCart(cart) { localStorage.setItem('cart', JSON.stringify(cart)); updateBadge(); }
      function updateBadge() {
        var cart = getCart();
        var total = cart.reduce((s, i) => s + (i.qty || 1), 0);
        var badge = document.querySelector('.cart-badge'); if (badge) badge.textContent = total;
      }
      function addItem(id, name, price) {
        var cart = getCart(); var item = cart.find(i => String(i.id) === String(id));
        if(item){ item.qty = (item.qty || 1) + 1; } else { cart.push({id,name,price,qty:1}); }
        saveCart(cart); showToast(name + ' added to cart');
      }
      function showToast(msg){
        var t = document.createElement('div'); t.textContent = msg;
        Object.assign(t.style,{position:'fixed',left:'50%',transform:'translateX(-50%)',bottom:'26px',background:'rgba(0,0,0,0.8)',color:'#fff',padding:'10px 14px',borderRadius:'8px',zIndex:2000,fontWeight:'700'});
        document.body.appendChild(t);
        setTimeout(()=>{ t.style.transition='opacity 300ms'; t.style.opacity='0'; setTimeout(()=>document.body.removeChild(t),300); },1200);
      }
      document.addEventListener('DOMContentLoaded', function(){
        updateBadge();
        document.querySelectorAll('.add-to-cart').forEach(el => el.addEventListener('click', function(e){
          e.preventDefault(); e.stopPropagation();
          addItem(this.dataset.id, this.dataset.name, parseFloat(this.dataset.price));
        }));
        var cartLink = document.querySelector('a[href="cart.php"]');
        if(cartLink){ cartLink.addEventListener('click', e => { e.preventDefault(); toggleCartModal(); }); }
        ensureCartModal();
      });

      // CART MODAL
      function ensureCartModal(){
        var modal = document.getElementById('cart-modal');
        if(!modal) return;
        document.getElementById('cart-close').addEventListener('click', ()=>modal.classList.remove('visible'));
        document.getElementById('clear-cart').addEventListener('click', e=>{ e.preventDefault(); localStorage.removeItem('cart'); renderCart(); updateBadge(); });
        renderCart();
      }
      function toggleCartModal(){ var modal = document.getElementById('cart-modal'); if(!modal) return; modal.classList.toggle('visible'); renderCart(); }
      function renderCart(){
        var list = document.getElementById('cart-list'); var totalEl = document.getElementById('cart-total'); if(!list) return;
        var cart = JSON.parse(localStorage.getItem('cart') || '[]'); list.innerHTML=''; var total=0;
        if(cart.length===0){ list.innerHTML='<div style="padding:18px;color:var(--muted)">Your cart is empty.</div>'; totalEl.textContent='₦0'; return; }
        cart.forEach((item,idx)=>{
          var row=document.createElement('div'); row.className='cart-item';
          var thumb=document.createElement('div'); thumb.className='ci-thumb';
          var info=document.createElement('div'); info.className='ci-info';
          info.innerHTML='<b>'+escapeHtml(item.name)+'</b><div style="font-size:0.9rem;color:var(--muted)">₦'+Number(item.price).toFixed(2)+'</div>';
          var actions=document.createElement('div'); actions.className='ci-actions';
          var qty=document.createElement('div'); qty.className='qty-control';
          var minus=document.createElement('button'); minus.textContent='-';
          var qSpan=document.createElement('span'); qSpan.textContent=item.qty||1; qSpan.style.color='#fff'; qSpan.style.minWidth='18px'; qSpan.style.textAlign='center';
          var plus=document.createElement('button'); plus.textContent='+';
          var rem=document.createElement('button'); rem.textContent='Remove'; rem.style.background='transparent'; rem.style.border='0'; rem.style.color='var(--muted)'; rem.style.cursor='pointer';
          qty.append(minus,qSpan,plus); actions.append(qty,rem); row.append(thumb,info,actions); list.appendChild(row);
          total += (item.price||0)*(item.qty||1);
          plus.addEventListener('click', e=>{ e.stopPropagation(); changeQty(idx,(item.qty||1)+1); });
          minus.addEventListener('click', e=>{ e.stopPropagation(); changeQty(idx,Math.max(1,(item.qty||1)-1)); });
          rem.addEventListener('click', e=>{ e.stopPropagation(); removeItem(idx); });
        });
        totalEl.textContent='₦'+Number(total).toFixed(2);
      }
      function changeQty(idx, qty){ var cart=JSON.parse(localStorage.getItem('cart')||'[]'); if(!cart[idx]) return; cart[idx].qty=qty; localStorage.setItem('cart',JSON.stringify(cart)); renderCart(); updateBadge(); }
      function removeItem(idx){ var cart=JSON.parse(localStorage.getItem('cart')||'[]'); cart.splice(idx,1); localStorage.setItem('cart',JSON.stringify(cart)); renderCart(); updateBadge(); }
      function escapeHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
    })();
  </script>

  <!-- CART MODAL HTML -->
  <div class="cart-modal" id="cart-modal" aria-hidden="true">
    <header>
      <h3>Your cart</h3>
      <button id="cart-close" aria-label="Close cart" style="background:transparent;border:0;color:var(--accent);cursor:pointer;font-weight:800">✕</button>
    </header>
    <div class="cart-list" id="cart-list"></div>
    <div class="cart-footer">
      <div class="cart-total"><span>Total</span><span id="cart-total">₦0</span></div>
      <div class="cart-actions">
        <a href="checkout.php" class="btn">Checkout</a>
        <a href="#" id="clear-cart" class="btn btn-outline">Clear</a>
      </div>
    </div>
  </div>
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
