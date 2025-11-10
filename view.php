
<?php include "db.php"; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Product View</title>
  <style>
    :root{
      --bg: #f5f7fb;
      --card: #ffffff;
      --accent: #0b76ef;
      --muted: #6b7280;
      --radius: 12px;
      --shadow: 0 6px 20px rgba(12, 14, 20, 0.06);
    }

    *{box-sizing: border-box}
    body{
      margin:0;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: linear-gradient(180deg,var(--bg),#eef4ff);
      color: #111827;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:24px;
    }

    .product-card{
      width:100%;
      max-width:900px;
      background:var(--card);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      padding:24px;
      display:grid;
      grid-template-columns: 300px 1fr 260px;
      gap:20px;
      align-items:start;
    }

    @media (max-width:900px){
      .product-card{grid-template-columns: 1fr;}
      .product-image img{width:100%; height:auto;}
    }

    .product-image img{
      width:100%;
      height:280px;
      object-fit:cover;
      border-radius:var(--radius);
      background:#e5e7eb;
    }

    .product-info h1{
      margin:0 0 8px 0;
      font-size:1.6rem;
      letter-spacing: -0.2px;
    }

    .price{
      font-weight:700;
      font-size:1.4rem;
      color:var(--accent);
    }

    .meta{
      display:flex;
      gap:12px;
      align-items:center;
      margin:12px 0 18px 0;
      color:var(--muted);
      font-size:0.95rem;
    }

    .description{
      color:#374151;
      line-height:1.5;
      margin-bottom:18px;
      font-size:1rem;
    }

    .actions{
      display:flex;
      gap:12px;
      align-items:center;
    }

    .btn{
      appearance:none;
      border:0;
      background:var(--accent);
      color:white;
      padding:12px 16px;
      border-radius:10px;
      font-weight:600;
      cursor:pointer;
      box-shadow: 0 6px 18px rgba(11,118,239,0.18);
      transition: transform .12s ease, box-shadow .12s ease, opacity .12s ease;
    }

    .btn:active{transform: translateY(1px)}
    .btn:disabled{opacity:.6; cursor:not-allowed}

    .secondary{
      background:transparent;
      border:1px solid #e6e9ef;
      color:#111827;
      padding:10px 14px;
      border-radius:10px;
    }

    .summary{
      background: linear-gradient(180deg,#ffffff,#fbfdff);
      border-radius:10px;
      padding:18px;
      border:1px solid #f0f4ff;
    }

    .summary .label{font-size:0.85rem; color:var(--muted);}
    .summary .value{font-weight:700; font-size:1.1rem; margin-top:6px}

    .sku{font-size:0.85rem; color:var(--muted)}
  </style>
</head>
<body>

  <article class="product-card" aria-labelledby="product-title">
    
    <?php 
    if(isset($_GET['id']) && $_GET['id'] !== ""){
        $menuId = $_GET['id'];
    }else{
        header("Location: viewpage.php");
        exit();
    }

    $sql = "SELECT * FROM tbl_menu WHERE menu_id = $menuId";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {
        while($row = mysqli_fetch_assoc($result)){
            ?>

<div class="product-image">
      <img src="photos/<?= $row['image'] ?>" alt="Product Image" />
    </div>

    <div class="product-info">
      <h1 id="product-title"><?= $row['name']??""; ?></h1>
      <div class="meta">
        <div class="price">#<?= $row['price']??""; ?></div>
        <div class="sku">• SKU: PTS-449</div>
      </div>

      <p class="description"><?= $row['description']??""; ?></p>

      <div class="actions">
        <button class="btn" type="button" id="buy-btn">Buy now</button>
        <button class="secondary" type="button" id="wishlist-btn">Add to wishlist</button>
      </div>
    </div>

    <!-- <aside class="summary" aria-label="Product summary">
      <div class="label">Price</div>
      <div class="value">$<?= $row['price']??""; ?></div>

      <div style="height:12px"></div>

      <div class="label">Availability</div>
      <div class="value">In stock</div>

      <div style="height:12px"></div>

      <div class="label">Shipping</div>
      <div class="value">Delivery in 3–5 business days</div>

      <div style="height:16px"></div>
      <div class="sku">Category: Electronics</div>
    </aside> -->

    <?php
            }
        }
        ?>
  </article>

</body>
</html>
