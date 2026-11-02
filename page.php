<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Binary Minds – Pro Edition</title>
  
  <!-- Polices Pixel / Retro -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
  <!-- FontAwesome pour les icônes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* ── VARIABLES & RESET ── */
    :root {
      /* Palette Cyberpunk */
      --bg-dark: #050510; 
      --bg-card: rgba(20, 10, 40, 0.85);
      --cyan: #00f3ff; 
      --pink: #ff0055; 
      --purple: #bc13fe;
      
      --text-main: #ffffff;
      --text-muted: #a0a0c0;
      
      --font-head: 'Press Start 2P', cursive;
      --font-body: 'VT323', monospace;
      --ease-pro: cubic-bezier(0.25, 0.8, 0.25, 1);
      --ease-luxury: cubic-bezier(0.25, 1, 0.5, 1); /* Courbe style ELLE */
      --border-width: 4px;
    }

    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    
    body {
      background-color: var(--bg-dark);
      color: var(--text-main);
      font-family: var(--font-body);
      font-size: 20px;
      overflow: hidden; /* IMPORTANT : Empêche le scroll natif pour le slider JS */
      line-height: 1.5;
      height: 100vh;
      width: 100vw;
    }

    /* ── EFFET CRT SCANLINES ── */
    .scanlines {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: linear-gradient(
        to bottom,
        rgba(255,255,255,0),
        rgba(255,255,255,0) 50%,
        rgba(0,0,0,0.1) 50%,
        rgba(0,0,0,0.1)
      );
      background-size: 100% 4px;
      z-index: 9999;
      pointer-events: none;
      opacity: 0.3;
    }
    
    .crt-overlay {
      position: fixed;
      inset: 0;
      background: radial-gradient(circle, rgba(0,0,0,0) 50%, rgba(5, 5, 16, 0.8) 100%);
      z-index: 9998;
      pointer-events: none;
    }

    /* ── VIDEO BG ── */
    #bg-video {
      position: fixed;
      inset: 0;
      width: 100%; height: 100%;
      object-fit: cover;
      z-index: -2;
      filter: brightness(0.15) contrast(1.1);
    }

    /* ── NAV BAR ── */
    nav {
      position: fixed;
      top: 0; left: 0; width: 100%;
      z-index: 100;
      padding: 20px 5%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(5, 5, 16, 0.9);
      border-bottom: var(--border-width) solid var(--purple);
      box-shadow: 0 0 15px rgba(188, 19, 254, 0.4);
    }

    .nav-brand {
      font-family: var(--font-head);
      font-size: 1rem;
      color: #fff;
      text-shadow: 2px 2px 0px var(--purple);
      display: flex;
      align-items: center;
      gap: 15px;
      z-index: 101;
    }

    .brand-pixel-icon {
      width: 24px; height: 24px;
      background: var(--cyan);
      box-shadow: 4px 4px 0px var(--purple);
    }

    .nav-links { display: flex; gap: 40px; }
    
    .nav-links a {
      text-decoration: none;
      color: var(--text-muted);
      font-size: 1.2rem;
      text-transform: uppercase;
      transition: color 0.3s var(--ease-pro);
    }

    .nav-links a:hover { color: var(--cyan); text-shadow: 0 0 8px var(--cyan); }

    /* ── GLOBAL TYPOGRAPHY ── */
    h1, h2, h3 { font-family: var(--font-head); line-height: 1.4; text-transform: uppercase; }
    .pixel-text-cyan { color: var(--cyan); text-shadow: 2px 2px 0px #000, 0 0 10px rgba(0, 243, 255, 0.5); }
    .pixel-text-pink { color: var(--purple); text-shadow: 2px 2px 0px #000, 0 0 10px rgba(188, 19, 254, 0.5); }
    
    .cursor::after { content: '_'; animation: blink 1s step-end infinite; }
    @keyframes blink { 50% { opacity: 0; } }

    /* ── FULL PAGE SLIDER ENGINE (STYLE ELLE) ── */
    .main-wrapper {
      position: relative;
      height: 100vh;
      width: 100%;
    }

    section {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      display: flex; align-items: center; justify-content: center;
      overflow: hidden;
      padding: 100px 5%;
      border-bottom: 1px solid rgba(188, 19, 254, 0.2);
      
      /* État inactif */
      opacity: 0;
      visibility: hidden;
      z-index: 1;
      transition: all 1.2s var(--ease-luxury);
    }

    section.active {
      opacity: 1;
      visibility: visible;
      z-index: 2;
    }

    /* ── NAVIGATION DOTS (STYLE ELLE) ── */
    .nav-dots {
      position: fixed;
      right: 30px;
      top: 50%;
      transform: translateY(-50%);
      z-index: 100;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .dot {
      width: 10px; height: 10px;
      border: 1px solid rgba(255,255,255,0.3);
      background: transparent;
      cursor: pointer;
      transition: all 0.4s ease;
    }

    .dot.active {
      background: var(--cyan);
      border-color: var(--cyan);
      transform: scale(1.4);
      box-shadow: 0 0 10px rgba(0, 243, 255, 0.5);
    }

    /* ── ANIMATIONS DE REVELATION ── */
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s var(--ease-pro);
    }
    
    section.active .reveal { opacity: 1; transform: translateY(0); }
    section.active .reveal:nth-child(1) { transition-delay: 0.2s; }
    section.active .reveal:nth-child(2) { transition-delay: 0.4s; }
    section.active .reveal:nth-child(3) { transition-delay: 0.6s; }
    section.active .reveal:nth-child(4) { transition-delay: 0.8s; }

    /* ── HERO SECTION SPECIFIC ── */
    .hero-container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      max-width: 1400px;
      gap: 60px;
    }

    .hero-content { flex: 1; z-index: 2; }
    
    .badge {
      display: inline-block;
      padding: 10px 15px;
      background: #000;
      border: 2px solid var(--purple);
      color: var(--purple);
      font-family: var(--font-head);
      font-size: 0.6rem;
      margin-bottom: 30px;
      box-shadow: 4px 4px 0px var(--cyan);
    }

    h1.hero-title {
      font-size: clamp(1.8rem, 4vw, 3rem);
      margin-bottom: 30px;
      color: #fff;
      line-height: 1.5;
      text-shadow: 4px 4px 0px rgba(0,0,0,0.5);
    }

    .hero-desc {
      font-size: 1.4rem;
      color: #ccc;
      max-width: 600px;
      margin-bottom: 50px;
      background: rgba(0,0,0,0.5);
      padding: 20px;
      border-left: 4px solid var(--cyan);
      backdrop-filter: blur(5px);
    }

    .cta-group { display: flex; gap: 20px; }

    .btn {
      padding: 15px 30px;
      border: 2px solid #fff;
      font-family: var(--font-head);
      font-size: 0.7rem;
      text-transform: uppercase;
      cursor: pointer;
      transition: all 0.2s var(--ease-pro);
      text-decoration: none;
      display: inline-block;
      background: #000;
      color: #fff;
      position: relative;
    }

    .btn:active { transform: translate(4px, 4px); box-shadow: none !important; }

    .btn-primary {
      border-color: var(--cyan);
      color: var(--cyan);
      box-shadow: 6px 6px 0px rgba(0, 243, 255, 0.2);
    }

    .btn-primary:hover {
      background: var(--cyan);
      color: #000;
      box-shadow: 6px 6px 0px var(--purple);
      transform: translate(-2px, -2px);
    }

    .btn-secondary {
      border-color: var(--purple);
      color: var(--purple);
      box-shadow: 6px 6px 0px rgba(188, 19, 254, 0.2);
    }

    .btn-secondary:hover {
      background: var(--purple);
      color: #fff;
      box-shadow: 6px 6px 0px var(--cyan);
      transform: translate(-2px, -2px);
    }

    .hero-visual {
      flex: 1;
      position: relative;
      display: flex;
      justify-content: center;
      perspective: 1000px;
    }

    .visual-card {
      width: 100%;
      max-width: 600px;
      aspect-ratio: auto;
      border: var(--border-width) solid #fff;
      position: relative;
      box-shadow: 15px 15px 0px rgba(188, 19, 254, 0.4);
      background: #000;
      transition: transform 0.3s;
    }
    
    .visual-card:hover {
      transform: scale(1.02);
      box-shadow: -15px -15px 0px var(--cyan);
    }

    .visual-card img {
      width: 100%; height: auto;
      display: block;
      image-rendering: pixelated; 
      filter: contrast(1.1) saturate(1.1);
    }

    /* ── FEATURES SECTION ── */
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
      width: 100%;
      max-width: 1200px;
      z-index: 2;
    }

    .feature-card {
      background: var(--bg-card);
      border: 2px solid rgba(255, 255, 255, 0.1);
      padding: 30px;
      box-shadow: 8px 8px 0px rgba(0,0,0,0.5);
      transition: all 0.3s var(--ease-pro);
      cursor: default;
      position: relative;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      border-color: var(--cyan);
      background: rgba(30, 10, 60, 0.95);
      box-shadow: 0 0 20px rgba(0, 243, 255, 0.2);
    }
    
    .feature-card::before {
      content: ''; position: absolute; top: 0; left: 0; width: 0%; height: 4px;
      background: var(--purple); transition: width 0.3s;
    }
    .feature-card:hover::before { width: 100%; }

    .feature-icon {
      font-size: 2.5rem;
      margin-bottom: 20px;
      color: var(--purple);
      text-shadow: 2px 2px 0px var(--cyan);
    }

    .feature-card h3 { font-size: 1rem; margin-bottom: 15px; color: #fff; }
    .feature-card p { color: #ccc; font-size: 1.1rem; }

    /* ── GALLERY SECTION (Horizontal Slider) ── */
    
    .slider-content-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
      height: 100%;
      justify-content: center;
    }

    .slider-container {
      position: relative;
      width: 100%;
      max-width: 1000px;
      height: 60vh;
      margin-top: 40px;
      border: 4px solid #fff;
      background: #000;
      box-shadow: 0 0 30px rgba(0, 243, 255, 0.1);
    }

    .slider-track {
      display: flex;
      height: 100%;
      transition: transform 0.6s var(--ease-pro);
    }

    .slide {
      min-width: 100%;
      height: 100%;
      position: relative;
      overflow: hidden;
    }

    .slide img {
      width: 100%; height: 100%; object-fit: cover;
      image-rendering: pixelated; 
      filter: contrast(1.1) brightness(0.9); 
      transition: transform 0.5s var(--ease-pro);
    }
    
    .slide.active img { transform: scale(1.05); }

    .slide-caption {
      position: absolute;
      bottom: 0; left: 0; width: 100%;
      padding: 20px;
      background: linear-gradient(to top, rgba(0,0,0,0.95), transparent);
      /* MODIFICATION ICI : Bordure mauve supprimée */
      border-top: none; 
      text-align: left;
      transform: translateY(20px);
      opacity: 0;
      transition: all 0.4s var(--ease-pro);
    }

    .slide.active .slide-caption { transform: translateY(0); opacity: 1; }
    .slide-caption h3 { color: var(--cyan); font-size: 0.8rem; margin-bottom: 5px; text-shadow: 0 0 5px var(--cyan); }

    /* Slider Controls */
    .slider-btn {
      position: absolute;
      top: 50%; transform: translateY(-50%);
      width: 60px; height: 60px;
      background: #000;
      border: 2px solid var(--cyan);
      color: var(--cyan);
      font-size: 1.5rem;
      cursor: pointer;
      z-index: 10;
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-head);
      transition: all 0.2s;
    }

    .slider-btn:hover { background: var(--cyan); color: #000; box-shadow: 0 0 15px var(--cyan); }
    .prev-btn { left: -80px; }
    .next-btn { right: -80px; }

    .slider-dots {
      display: flex; justify-content: center; gap: 15px; margin-top: 20px;
    }

    .dot-mini {
      width: 12px; height: 12px;
      background: transparent;
      border: 2px solid #fff;
      cursor: pointer;
      transition: all 0.3s;
    }

    .dot-mini.active { 
      background: var(--cyan); 
      border-color: var(--cyan); 
      width: 30px; 
      box-shadow: 0 0 10px var(--cyan);
    }

    /* ── PRIVACY SECTION ── */
    .privacy-container {
      width: 100%; max-width: 800px;
      background: rgba(0,0,0,0.8);
      border: 2px solid var(--purple);
      padding: 40px;
      box-shadow: 0 0 20px rgba(188, 19, 254, 0.2);
    }
    .privacy-container h2 { color: var(--purple); margin-bottom: 20px; font-size: 1.5rem; }
    .privacy-container p { 
      color: #ccc; margin-bottom: 20px; font-size: 1.2rem;
      border-bottom: 1px dashed rgba(255,255,255,0.2); padding-bottom: 20px;
    }
    .privacy-container p:last-child { border: none; }

    /* ── FOOTER SECTION ── */
    footer {
      border-top: 2px solid var(--cyan);
      background: #000;
    }
    .footer-content {
      display: flex; flex-direction: column; align-items: center; gap: 30px;
      z-index: 2;
    }
    .footer-logo {
      font-family: var(--font-head); font-size: 1.5rem;
      color: #fff; text-shadow: 0 0 10px var(--cyan);
    }
    .socials { display: flex; gap: 20px; }
    .social-link {
      width: 50px; height: 50px;
      background: rgba(255,255,255,0.05);
      border: 2px solid var(--purple);
      display: flex; align-items: center; justify-content: center;
      transition: all 0.3s;
      text-decoration: none;
      color: #fff;
      font-family: var(--font-head);
      font-size: 1.2rem;
    }
    .social-link:hover {
      background: var(--purple); color: #fff;
      box-shadow: 0 0 15px var(--purple);
      transform: translateY(-3px);
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      section { padding: 100px 5% 20px; }
      .hero-container { flex-direction: column; text-align: center; padding-top: 60px; }
      h1.hero-title { font-size: 1.5rem; }
      .nav-links { display: none; } 
      .nav-dots { display: none; } 
      
      body, .main-wrapper, section {
        position: relative;
        height: auto;
        min-height: 100vh;
        opacity: 1;
        visibility: visible;
        overflow: visible;
      }
      
      .slider-container { height: 40vh; margin-bottom: 40px; }
      .slider-btn { display: none; }
      .prev-btn, .next-btn { display: none; }
      .privacy-container { padding: 20px; }
    }
  </style>
</head>
<body>

<!-- EFFETS CRT / SCANLINES -->
<div class="scanlines"></div>
<div class="crt-overlay"></div>

<!-- BACKGROUND VIDEO -->
<video autoplay muted loop id="bg-video" playsinline>
  <source src="cyberpunk-gaming-pixel-moewalls-com.mp4" type="video/mp4">
</video>

<!-- NAVIGATION -->
<nav>
  <div class="nav-brand">
    <div class="brand-pixel-icon"></div> BINARY_MINDS
  </div>
  <div class="nav-links">
    <a href="dashboard_player.php">Dashboard</a>
    <a href="#" onclick="goToSlide(0); return false;">Démarrer</a>
    <a href="#" onclick="goToSlide(1); return false;">Modules</a>
    <a href="#" onclick="goToSlide(2); return false;">Screens</a>
    <a href="#" onclick="goToSlide(3); return false;">Système</a>
  </div>
  <a href="#" onclick="goToSlide(4); return false;" class="btn btn-primary">JOUER</a>
</nav>

<!-- NAVIGATION DOTS (Style ELLE) -->
<div class="nav-dots" id="navDots"></div>

<!-- MAIN FULLSCREEN SLIDER WRAPPER -->
<div class="main-wrapper">

  <!-- 1. HERO SECTION -->
  <section class="section-slide active">
    <div class="hero-container">
      <div class="hero-content reveal">
        <span class="badge">[ SYSTEM ONLINE ]</span>
        <h1 class="hero-title">
          Apprendre à coder.<br>
          <span class="pixel-text-cyan cursor">are you ready...</span>
        </h1>
        <p class="hero-desc">
          Initialisation de la séquence d'apprentissage... Maîtrisez C, C#, Java et Python dans un environnement rétro-immersif.
        </p>
        <div class="cta-group">
          <a href="#" onclick="goToSlide(2); return false;" class="btn btn-primary">VOIR LE CODE</a>
          <a href="#" onclick="goToSlide(1); return false;" class="btn btn-secondary">INFOS</a>
        </div>
      </div>
      
      <div class="hero-visual reveal">
        <div class="visual-card">
          <!-- Image STABLE -->
          <img src="imgh.png" 
               alt="Code Editor Retro" 
               onerror="this.src='https://picsum.photos/seed/tech/600/450';">
        </div>
      </div>
    </div>
  </section>

  <!-- 2. FEATURES SECTION -->
  <section class="section-slide">
    <div style="width: 100%; max-width: 1200px;">
      <div style="margin-bottom: 60px;" class="reveal">
        <span class="badge" style="border-color: var(--purple); color: var(--purple); background: #000;">MODULES DISPONIBLES</span>
        <h2 style="font-size: 2rem; color: #fff;">Sélectionnez <span class="pixel-text-pink">VOTRE CLASSE</span></h2>
      </div>

      <div class="features-grid">
        <div class="feature-card reveal">
          <div class="feature-icon"><i class="fa-solid fa-gamepad"></i></div>
          <h3>INTERACTIF</h3>
          <p>Compilateur intégré. Tapez du code, obtenez du résultat. Pas de thèmes ennuyeux.</p>
        </div>
        <div class="feature-card reveal">
          <div class="feature-icon"><i class="fa-solid fa-code"></i></div>
          <h3>GAME DEV</h3>
          <p>Apprenez la logique par le jeu. Créez vos propres moteurs 2D.</p>
        </div>
        <div class="feature-card reveal">
          <div class="feature-icon"><i class="fa-brands fa-python"></i></div>
          <h3>LANGAGES</h3>
          <p>C / C++ / JAVA / PYTHON. Toutes les syntaxes sont débloquées.</p>
        </div>
        <div class="feature-card reveal">
          <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
          <h3>SÉCURISÉ</h3>
          <p>Serveur privé. Pas de chat public. Focus total sur la syntaxe.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. GALLERY SECTION (Horizontal Slider) -->
  <section class="section-slide">
    <div class="slider-content-wrapper">
      <div class="reveal">
        <h2 style="font-size: 2rem; margin-bottom: 10px; color: var(--cyan);">VISUALISATION DATA</h2>
        <p style="color: var(--text-muted);">[ UTILISEZ LES FLÈCHES CI-DESSOUS ]</p>
      </div>

      <div class="slider-container reveal">
        <button class="slider-btn prev-btn" id="prevBtn">&lt;</button>
        
        <div class="slider-track" id="track">
          <div class="slide active">
            <img src="1.png" alt="Dashboard" onerror="this.src='https://picsum.photos/seed/neoncity/1000/600'">
            <div class="slide-caption"><h3>MAIN_DASHBOARD.EXE</h3></div>
          </div>
          <div class="slide">
            <img src="2.png">
            <div class="slide-caption"><h3>CODE_EDITOR_V2</h3></div>
          </div>
          <div class="slide">
            <img src="3.png">
            <div class="slide-caption"><h3>SKILL_TREE.RPG</h3></div>
          </div>
          <div class="slide">
            <img src="4.png">
            <div class="slide-caption"><h3>PYTHON_SCRIPTS</h3></div>
          </div>
          <div class="slide">
            <img src="5.png">
            <div class="slide-caption"><h3>CSHARP_MODULES</h3></div>
          </div>
        </div>

        <button class="slider-btn next-btn" id="nextBtn">&gt;</button>
        
        <div class="slider-dots" id="dotsContainer"></div>
      </div>
    </div>
  </section>

  <!-- 4. PRIVACY SECTION -->
  <section class="section-slide">
    <div class="privacy-container reveal">
      <h2>PROTOCOLE CONFIDENTIALITÉ</h2>
      <p>
        > ACCÈS ROOT : Interdit.<br>
        > Données utilisateurs : Chiffrées (AES-256).<br>
        > Aucune transmission de données vers des serveurs tiers.
      </p>
      <p>
        <strong>MODE FOCUS :</strong> Toutes les fonctions sociales sont désactivées. L'utilisateur est seul face au terminal.
      </p>
      <div style="margin-top: 30px; padding: 15px; border: 2px dashed var(--purple); color: var(--cyan); font-family: var(--font-head); font-size: 0.7rem;">
        [ SYSTEM ALERT ] : VOTRE PROGRESSION EST LOCALE.
      </div>
    </div>
  </section>

  <!-- 5. FOOTER SECTION -->
  <section class="section-slide">
    <footer class="footer-content reveal">
      <div class="footer-logo">BINARY_MINDS</div>
      <div class="socials">
        <a href="#" class="social-link">f</a>
        <a href="#" class="social-link">in</a>
        <a href="#" class="social-link">@</a>
      </div>
      <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 20px;">
        © 2026 BINARY_MINDS_TEAM // END_OF_LINE
      </p>
    </footer>
  </section>

</div>

<script>
  /* ── 1. FULL PAGE SLIDER LOGIC (STYLE ELLE) ── */
  const slides = document.querySelectorAll('.section-slide');
  const dotsContainer = document.getElementById('navDots');
  let currentSlide = 0;
  let isAnimating = false;

  // Création des dots de navigation principale
  slides.forEach((_, index) => {
    const dot = document.createElement('div');
    dot.classList.add('dot');
    if(index === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(index));
    dotsContainer.appendChild(dot);
  });
  
  const dots = document.querySelectorAll('.dot');

  function goToSlide(index) {
    if (isAnimating || index === currentSlide) return;
    if (index < 0 || index >= slides.length) return;
    
    isAnimating = true;
    
    // Retirer la classe active de la slide actuelle
    slides[currentSlide].classList.remove('active');
    dots[currentSlide].classList.remove('active');

    // Mettre à jour l'index
    currentSlide = index;

    // Ajouter la classe active à la nouvelle slide
    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');

    // Libérer le verrou d'animation après la durée de la transition CSS (1.2s)
    setTimeout(() => { isAnimating = false; }, 1200);
  }

  function nextSlide() {
    if (currentSlide < slides.length - 1) {
      goToSlide(currentSlide + 1);
    }
  }

  function prevSlide() {
    if (currentSlide > 0) {
      goToSlide(currentSlide - 1);
    }
  }

  // ── EVENT LISTENERS (Navigation Verticale) ──
  
  // Wheel (Souris)
  window.addEventListener('wheel', (e) => {
    // Petite protection pour éviter de sauter si on scrolle vite
    if (Math.abs(e.deltaY) < 20) return;
    
    if (e.deltaY > 0) nextSlide();
    else prevSlide();
  });

  // Clavier
  window.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowDown') nextSlide();
    if (e.key === 'ArrowUp') prevSlide();
  });

  // Touch (Swipe Mobile)
  let touchStartY = 0;
  let touchEndY = 0;
  
  window.addEventListener('touchstart', e => touchStartY = e.changedTouches[0].screenY);
  window.addEventListener('touchend', e => {
    touchEndY = e.changedTouches[0].screenY;
    handleSwipe();
  });

  function handleSwipe() {
    if (touchStartY - touchEndY > 50) nextSlide();
    if (touchEndY - touchStartY > 50) prevSlide();
  }

  /* ── 2. INTERNAL GALLERY SLIDER LOGIC (Horizontal) ── */
  const track = document.getElementById('track');
  const gallerySlides = Array.from(track.children);
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');
  const dotsContainerMini = document.getElementById('dotsContainer');
  
  let currentGalleryIndex = 0;
  let autoPlayInterval;

  // Création des points miniatures
  gallerySlides.forEach((_, index) => {
    const dot = document.createElement('div');
    dot.classList.add('dot-mini');
    if (index === 0) dot.classList.add('active');
    dot.addEventListener('click', () => {
      resetAutoPlay();
      moveGallerySlide(index);
    });
    dotsContainerMini.appendChild(dot);
  });

  const dotsMini = Array.from(dotsContainerMini.children);

  function updateDotsMini(index) {
    dotsMini.forEach(d => d.classList.remove('active'));
    dotsMini[index].classList.add('active');
  }

  function moveGallerySlide(index) {
    if (index < 0) index = gallerySlides.length - 1;
    if (index >= gallerySlides.length) index = 0;
    
    currentGalleryIndex = index;
    const amountToMove = -100 * currentGalleryIndex;
    track.style.transform = `translateX(${amountToMove}%)`;
    
    gallerySlides.forEach(s => s.classList.remove('active'));
    gallerySlides[currentGalleryIndex].classList.add('active');
    
    updateDotsMini(currentGalleryIndex);
  }
  
  // Gestion de l'auto-play
  function startAutoPlay() {
    autoPlayInterval = setInterval(() => {
      moveGallerySlide(currentGalleryIndex + 1);
    }, 5000);
  }
  
  function resetAutoPlay() {
    clearInterval(autoPlayInterval);
    startAutoPlay();
  }

  // Boutons Slider Interne
  if(nextBtn) nextBtn.addEventListener('click', (e) => {
    e.stopPropagation(); // Empêche de changer de section principale
    resetAutoPlay();
    moveGallerySlide(currentGalleryIndex + 1);
  });
  
  if(prevBtn) prevBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    resetAutoPlay();
    moveGallerySlide(currentGalleryIndex - 1);
  });

  // Démarrage initial
  startAutoPlay();

</script>
</body>
</html>