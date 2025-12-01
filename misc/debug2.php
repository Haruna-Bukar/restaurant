<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Horizontal Scrolling Restaurant Menu</title>
  <link rel="stylesheet" href="menu.css">
</head>

<body>
  <header>
    <div>
      <h1>Pitti Restaurant — Menu</h1>
      <p class="lead">Scroll horizontally to explore both rows of dishes.</p>
    </div>
    <div class="controls">
      <button class="btn" id="prevBtn">‹</button>
      <button class="btn" id="nextBtn">›</button>
      <button class="btn ghost" id="toggleAuto">Auto-scroll: Off</button>
    </div>
  </header>

  <section class="menu-wrap">
    <div class="menu" id="menu">

      <div class="menu-column">
        <article class="menu-item">
          <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1600891964599-f61ba0e24092?auto=format&fit=crop&w=800&q=60');"></div>
          <div class="title-row">
            <div class="name">Grilled Salmon Bowl</div>
            <div class="price">₦4,200</div>
          </div>
          <div class="desc">Seared salmon with lemon rice and roasted vegetables.</div>
        </article>
        <article class="menu-item">
          <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1603048297172-3cc79a6c3cb9?auto=format&fit=crop&w=800&q=60');"></div>
          <div class="title-row">
            <div class="name">Pepper Chicken Wrap</div>
            <div class="price">₦2,500</div>
          </div>
          <div class="desc">Juicy peppered chicken with veggies and spicy sauce in soft wrap.</div>
        </article>
      </div>

      <div class="menu-column">
        <article class="menu-item">
          <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1604908177522-5b5bba1ef3f9?auto=format&fit=crop&w=800&q=60');"></div>
          <div class="title-row">
            <div class="name">Spicy Jollof Pasta</div>
            <div class="price">₦2,800</div>
          </div>
          <div class="desc">Creamy pasta cooked with our signature jollof-spice blend.</div>
        </article>
        <article class="menu-item">
          <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1601924576920-6a409b1b52e8?auto=format&fit=crop&w=800&q=60');"></div>
          <div class="title-row">
            <div class="name">Seafood Fried Rice</div>
            <div class="price">₦3,800</div>
          </div>
          <div class="desc">Fried rice with shrimp, squid, and pepper sauce blend.</div>
        </article>
      </div>

      <div class="menu-column">
        <article class="menu-item">
          <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1543353071-087092ec393a?auto=format&fit=crop&w=800&q=60');"></div>
          <div class="title-row">
            <div class="name">Plantain & Bean Stack</div>
            <div class="price">₦1,900</div>
          </div>
          <div class="desc">Caramelized plantain, stewed beans, avocado, and greens.</div>
        </article>
        <article class="menu-item">
          <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=800&q=60');"></div>
          <div class="title-row">
            <div class="name">Avocado Toast Deluxe</div>
            <div class="price">₦1,700</div>
          </div>
          <div class="desc">Sourdough with smashed avocado, egg, and chili flakes.</div>
        </article>
      </div>

      <div class="menu-column">
        <article class="menu-item">
          <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1606756798455-4b5b1d8c8d54?auto=format&fit=crop&w=800&q=60');"></div>
          <div class="title-row">
            <div class="name">Smoky Suya Burger</div>
            <div class="price">₦3,400</div>
          </div>
          <div class="desc">Grilled beef patty seasoned with suya spices and pepper mayo.</div>
        </article>
        <article class="menu-item">
          <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1617196039390-61ef6d3f7fa7?auto=format&fit=crop&w=800&q=60');"></div>
          <div class="title-row">
            <div class="name">Chocolate Lava Cake</div>
            <div class="price">₦2,200</div>
          </div>
          <div class="desc">Warm molten chocolate cake with vanilla ice cream.</div>
        </article>
      </div>

      <div class="menu-column">
        <article class="menu-item">
          <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1604908177520-6c0f3f7a9a4c?auto=format&fit=crop&w=800&q=60');"></div>
          <div class="title-row">
            <div class="name">Coconut Panna Cotta</div>
            <div class="price">₦1,200</div>
          </div>
          <div class="desc">Silky coconut cream with mango coulis.</div>
        </article>
        <article class="menu-item">
          <div class="thumb" style="background-image:url('https://images.unsplash.com/photo-1617191513004-73ff6f0e77b3?auto=format&fit=crop&w=800&q=60');"></div>
          <div class="title-row">
            <div class="name">Lemon Herb Chicken</div>
            <div class="price">₦3,200</div>
          </div>
          <div class="desc">Grilled chicken marinated with lemon and herbs.</div>
        </article>
      </div>

    </div>
  </section>

  <script>
    const menu = document.getElementById('menu');
    const prev = document.getElementById('prevBtn');
    const next = document.getElementById('nextBtn');
    const toggle = document.getElementById('toggleAuto');
    let auto = false;
    let autoTimer = null;

    function scrollByCard(dir = 1) {
      const cardWidth = document.querySelector('.menu-column').getBoundingClientRect().width + 18;
      menu.scrollBy({
        left: cardWidth * dir,
        behavior: 'smooth'
      });
    }

    prev.addEventListener('click', () => scrollByCard(-1));
    next.addEventListener('click', () => scrollByCard(1));

    toggle.addEventListener('click', () => {
      auto = !auto;
      toggle.textContent = `Auto-scroll: ${auto?'On':'Off'}`;
      if (auto) {
        autoTimer = setInterval(() => scrollByCard(1), 2800);
      } else {
        clearInterval(autoTimer);
      }
    });
  </script>
</body>

</html>