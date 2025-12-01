<?php
session_start();
include 'db.php';

// Check login
$customer_id = $_SESSION['customer_id'] ?? null;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Pitti Restaurant — Menu</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root {
  --bg: #0f1724;
  --card: #0b1320;
  --accent: #ff9800;
  --muted: #9aa6b2;
}
* { box-sizing: border-box; }
body {
  margin: 0;
  font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, Arial;
  background: linear-gradient(180deg, var(--bg), #071021 120%);
  color: #e6eef6;
  padding: 20px;
  min-height: 100vh;
}
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  padding: 10px 0;
}
.logo img { height:50px; border-radius:8px; }
.nav-list { list-style:none; display:flex; gap:20px; padding:0; margin:0; }
.nav-link { color:var(--muted); text-decoration:none; font-weight:600; transition:.3s; }
.nav-link:hover { color:var(--accent); }
.icon-links { display:flex; align-items:center; gap:16px; }
.icon-links a { color: var(--muted); font-size:1.3rem; position:relative; }
.icon-links a:hover { color: var(--accent); }
.cart-badge { position:absolute; top:-5px; right:-5px; background:red; color:#fff; border-radius:50%; width:17px; height:17px; display:flex; justify-content:center; align-items:center; font-size:10px; font-weight:bold; }
.user-dropdown { position:relative; cursor:pointer; }
.user-menu { display:none; position:absolute; right:0; background:#0f1724; border:1px solid var(--accent); border-radius:8px; padding:10px; width:120px; }
.user-menu a { display:block; padding:8px 10px; color:white; text-decoration:none; }
.user-menu a:hover { background:var(--accent); color:black; border-radius:6px; }
.user-dropdown:hover .user-menu { display:block; }

h1 { margin:20px 0 5px; font-size:1.6rem; }
p.lead { margin:0 0 20px; color: var(--muted); }

.menu-wrap { position:relative; display:flex; flex-wrap:wrap; gap:20px; }
.menu-item {
  width: calc(25% - 20px);
  background: linear-gradient(180deg, var(--card), rgba(255,255,255,0.03));
  border-radius:14px;
  padding:12px;
  display:flex; flex-direction:column; gap:10px;
  cursor:pointer;
  border:1px solid rgba(255,255,255,0.03);
  transition: transform .2s, box-shadow .2s;
}
.menu-item:hover { transform:translateY(-5px); box-shadow:0 12px 32px rgba(2,8,23,0.6); }
.thumb { height:160px; border-radius:10px; background-size:cover; background-position:center; }
.title-row { display:flex; justify-content:space-between; }
.name { font-weight:700; }
.price { font-weight:800; color: var(--accent); }
.desc { font-size:.92rem; color: var(--muted); line-height:1.35; }
.btn { background:var(--accent); border:none; border-radius:8px; padding:8px 12px; color:#fff; font-weight:700; cursor:pointer; text-align:center; }
.btn:hover { background:#fff; color:#000; transition:.3s; }
.btn.small { width:100px; padding:6px 8px; font-size:.85rem; }
@media(max-width:900px){ .menu-item { width: calc(50% - 20px); } }
@media(max-width:600px){ .menu-item { width:100%; } }

/* Modal */
.modal { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); display:none; align-items:center; justify-content:center; z-index:999; }
.modal-content { background:#0f1724; padding:20px; border-radius:15px; width:90%; max-width:600px; text-align:center; position:relative; }
.close-btn { position:absolute; top:10px; right:20px; font-size:28px; cursor:pointer; color:var(--accent); }

/* Cart Modal */
.cart-modal { position:fixed; right:20px; top:70px; width:360px; max-height:calc(100vh - 120px); background:var(--card); border-radius:12px; display:none; flex-direction:column; z-index:2000; overflow:hidden; }
.cart-modal.visible{display:flex;}
.cart-modal header{padding:12px 14px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid rgba(255,255,255,0.03);}
.cart-list{padding:12px 14px; overflow:auto; flex:1;}
.cart-item{display:flex; gap:10px; align-items:center; padding:8px 0; border-bottom:1px dashed rgba(255,255,255,0.03);}
.ci-thumb{width:56px; height:44px; background:#091221; border-radius:6px;}
.ci-info{flex:1; color:var(--muted);}
.ci-info b{color:#fff;}
.ci-actions{display:flex; flex-direction:column; gap:6px; align-items:flex-end;}
.qty-control{display:flex; gap:6px; align-items:center;}
.qty-control button{background:transparent; border:1px solid rgba(255,255,255,0.04); color:#fff; padding:4px 8px; border-radius:6px; cursor:pointer;}
.cart-footer{padding:12px 14px; border-top:1px solid rgba(255,255,255,0.03); background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent);}
.cart-total{display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; color:#fff;}
.cart-actions{display:flex; gap:8px;}
.cart-actions .btn{flex:1;}

/* Slider */
.slider { max-width:100%; overflow:hidden; margin:20px 0; border-radius:15px; position:relative; }
.slides { display:flex; transition:0.5s; }
.slide { min-width:100%; height:220px; background-size:cover; background-position:center; }

/* Notification Bell */
.notification { position:relative; font-size:24px; color:var(--accent); cursor:pointer; }
.notification .badge { position:absolute; top:-8px; right:-8px; background:red; color:white; border-radius:50%; padding:3px 6px; font-size:12px; }
</style>
</head>
<body>

<header>
  <div class="logo"><img src="assets/image.png" alt=""></div>
  <nav>
    <ul class="nav-list">
      <li><a href="index.php" class="nav-link">Homepage</a></li>
      <li><a href="menu.php" class="nav-link">Menu</a></li>
      <li><a href="about.php" class="nav-link">AboutUs</a></li>
      <li><a href="contactus.php" class="nav-link">ContactUs</a></li>
    </ul>
  </nav>
  <div class="icon-links">
    <a href="index.php"><i class="fas fa-home"></i></a>
    <?php if($customer_id): ?>
    <div class="notification">
      <i class="fas fa-bell"></i>
      <?php
      $res = mysqli_query($conn,"SELECT COUNT(*) AS ready_count FROM tbl_order WHERE customer_id=$customer_id AND status='confirmed'");
      $row = mysqli_fetch_assoc($res);
      if($row['ready_count']>0) echo "<span class='badge'>{$row['ready_count']}</span>";
      ?>
    </div>
    <div class="user-dropdown">
      <i class="fas fa-user"></i>
      <div class="user-menu">
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>
    <?php else: ?>
      <a href="login.php"><i class="fas fa-user"></i></a>
    <?php endif; ?>
    <a href="cart.php" style="position:relative;"><i class="fas fa-shopping-cart"></i><span class="cart-badge">0</span></a>
  </div>
</header>

<!-- Slider -->
<div class="slider" id="slider">
  <div class="slides">
    <div class="slide" style="background-image:url('photos/special1.jpg');"></div>
    <div class="slide" style="background-image:url('photos/special2.jpg');"></div>
    <div class="slide" style="background-image:url('photos/special3.jpg');"></div>
  </div>
</div>

<h1>Pitti Restaurant — Menu</h1>
<p class="lead">Enjoy our delicious meals.</p>

<section class="menu-wrap">
<div class="menu" id="menu">
<?php
$result = mysqli_query($conn,"SELECT * FROM tbl_menu");
while($row = mysqli_fetch_assoc($result)){
  $id = $row['menu_id'];
  $desc = substr($row['description'],0,69);
  echo "
  <article class='menu-item' onclick=\"openModal('{$id}')\">
    <div class='thumb' style='background-image:url(photos/{$row['image']});'></div>
    <div class='title-row'><div class='name'>{$row['name']}</div><div class='price'>₦{$row['price']}</div></div>
    <div class='desc'>{$desc}</div>
    <div class='actions'><a href='#' class='btn small add-to-cart' data-id='{$id}' data-name='{$row['name']}' data-price='{$row['price']}' onclick='event.stopPropagation();'>Order</a></div>
  </article>

  <div class='modal' id='modal-{$id}'>
    <div class='modal-content'>
      <span class='close-btn' onclick=\"closeModal('{$id}')\">&times;</span>
      <img src='photos/{$row['image']}' alt='{$row['name']}'>
      <h2>{$row['name']}</h2>
      <p>{$row['description']}</p>
      <h3 class='price'>₦{$row['price']}</h3>
      <button class='btn'>Order Now</button>
    </div>
  </div>
  ";
}
?>
</div>
</section>

<!-- JS -->
<script>
// Slider
let currentIndex=0;
const slides=document.querySelectorAll(".slide");
setInterval(()=>{
  slides.forEach((s,i)=>s.style.transform=`translateX(-${currentIndex*100}%)`);
  currentIndex=(currentIndex+1)%slides.length;
},3000);

// Modal
function openModal(id){document.getElementById("modal-"+id).style.display="flex";document.body.style.overflow="hidden";}
function closeModal(id){document.getElementById("modal-"+id).style.display="none";document.body.style.overflow="auto";}

// Cart
(function(){
function getCart(){return JSON.parse(localStorage.getItem('cart')||'[]');}
function saveCart(cart){localStorage.setItem('cart',JSON.stringify(cart));updateBadge();}
function updateBadge(){var cart=getCart();var total=cart.reduce((s,i)=>s+(i.qty||1),0);document.querySelector('.cart-badge').textContent=total;}
function addItem(id,name,price){var cart=getCart();var item=cart.find(i=>i.id==id);if(item)item.qty=(item.qty||1)+1;else cart.push({id,name,price,qty:1});saveCart(cart);showToast(name+' added to cart');}
function showToast(msg){var t=document.createElement('div');t.textContent=msg;t.style.cssText='position:fixed;left:50%;transform:translateX(-50%);bottom:26px;background:rgba(0,0,0,0.8);color:#fff;padding:10px 14px;border-radius:8px;z-index:2000;font-weight:700';document.body.appendChild(t);setTimeout(()=>{t.style.transition='opacity 300ms';t.style.opacity=0;setTimeout(()=>document.body.removeChild(t),300);},1200);}
function renderCart(){var list=document.getElementById('cart-list');var totalEl=document.getElementById('cart-total');var cart=getCart();list.innerHTML='';var total=0;if(cart.length===0){list.innerHTML='<div style="padding:18px;color:var(--muted)">Your cart is empty.</div>';totalEl.textContent='₦0';return;}cart.forEach((item,idx)=>{var row=document.createElement('div');row.className='cart-item';var thumb=document.createElement('div');thumb.className='ci-thumb';var info=document.createElement('div');info.className='ci-info';info.innerHTML='<b>'+item.name+'</b><div style="font-size:.9rem;color:var(--muted)">₦'+Number(item.price).toFixed(2)+'</div>';var actions=document.createElement('div');actions.className='ci-actions';var qty=document.createElement('div');qty.className='qty-control';var minus=document.createElement('button');minus.textContent='-';var qSpan=document.createElement('span');qSpan.textContent=item.qty||1;qSpan.style.color='#fff';qSpan.style.minWidth='18px';qSpan.style.textAlign='center';var plus=document.createElement('button');plus.textContent='+';var rem=document.createElement('button');rem.textContent='Remove';rem.style.background='transparent';rem.style.border='0';rem.style.color='var(--muted)';rem.style.cursor='pointer';qty.appendChild(minus);qty.appendChild(qSpan);qty.appendChild(plus);actions.appendChild(qty);actions.appendChild(rem);row.appendChild(thumb);row.appendChild(info);row.appendChild(actions);list.appendChild(row);total+=(item.price||0)*(item.qty||1);plus.addEventListener('click',()=>changeQty(idx,(item.qty||1)+1));minus.addEventListener('click',()=>changeQty(idx,Math.max(1,(item.qty||1)-1)));rem.addEventListener('click',()=>removeItem(idx));});totalEl.textContent='₦'+Number(total).toFixed(2);}
function changeQty(idx,qty){var cart=getCart();cart[idx].qty=qty;saveCart(cart);renderCart();}
function removeItem(idx){var cart=getCart();cart.splice(idx,1);saveCart(cart);renderCart();}
function toggleCartModal(){document.getElementById('cart-modal').classList.toggle('visible');renderCart();}
document.addEventListener('DOMContentLoaded',()=>{updateBadge();document.querySelectorAll('.add-to-cart').forEach(el=>el.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();addItem(el.dataset.id,el.dataset.name,parseFloat(el.dataset.price))}));document.querySelector('#cart-close').addEventListener('click',()=>document.getElementById('cart-modal').classList.remove('visible'));document.querySelector('#clear-cart').addEventListener('click',e=>{e.preventDefault();localStorage.removeItem('cart');renderCart();updateBadge();});});
})();
</script>

<!-- Cart Modal -->
<div class="cart-modal" id="cart-modal">
<header>
<h3>Your cart</h3>
<button id="cart-close">✕</button>
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

</body>
</html>
