<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Binary Minds – À propos</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Share+Tech+Mono&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --cyan:   #00f0ff;
      --pink:   #ff00fe;
      --blue:   #005eff;
      --dark:   rgba(0,0,0,0.55);
    }

    /* ── SCROLLBAR ── */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #000; }
    ::-webkit-scrollbar-thumb { background: var(--pink); border-radius: 3px; }

    body {
      font-family: 'Press Start 2P', cursive;
      font-size: 11px;
      overflow-x: hidden;
      overflow-y: auto;
      color: #fff;
    }

    /* ── VIDEO BG ── */
    #bg-video {
      position: fixed;
      inset: 0;
      width: 100%; height: 100%;
      object-fit: cover;
      z-index: -2;
      filter: brightness(0.45) saturate(1.3);
    }

    .overlay {
      position: fixed;
      inset: 0;
      background: linear-gradient(160deg, rgba(0,0,0,0.55) 0%, rgba(10,0,30,0.50) 100%);
      z-index: -1;
    }

    /* ── SHARED GLOW HELPERS ── */
    .glow-pink  { text-shadow: 0 0 8px var(--pink),  0 0 20px var(--pink); }
    .glow-cyan  { text-shadow: 0 0 8px var(--cyan),  0 0 20px var(--cyan); }

    /* ── NAV BAR ── */
    nav {
      position: sticky;
      top: 0;
      z-index: 50;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 5%;
      background: rgba(0,0,0,0.70);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(0,240,255,0.15);
    }

    .nav-brand {
      font-size: 13px;
      color: var(--cyan);
      text-shadow: 0 0 10px var(--cyan);
      letter-spacing: 1px;
    }

    .nav-links { display: flex; gap: 24px; }

    .nav-links a {
      font-size: 9px;
      color: rgba(255,255,255,0.65);
      text-decoration: none;
      letter-spacing: 0.5px;
      transition: color 0.2s;
    }

    .nav-links a:hover { color: var(--cyan); text-shadow: 0 0 8px var(--cyan); }

    /* ── HERO ── */
    .hero {
      display: flex;
      justify-content: space-between;
      align-items: center;
      min-height: 100vh;
      padding: 80px 5% 60px;
      gap: 40px;
    }

    .left-content { width: 50%; }

    /* scanline decoration above h1 */
    .pre-label {
      font-size: 9px;
      color: var(--cyan);
      letter-spacing: 3px;
      margin-bottom: 14px;
      opacity: 0.8;
    }

    .left-content h1 {
      font-size: clamp(14px, 2vw, 22px);
      color: var(--pink);
      text-shadow: 0 0 10px var(--pink), 0 0 30px var(--pink);
      line-height: 1.6;
      margin-bottom: 18px;
    }

    .tagline {
      font-size: 10px;
      color: var(--cyan);
      text-shadow: 0 0 6px var(--cyan);
      margin-bottom: 20px;
      line-height: 1.8;
    }

    .body-text {
      font-family: 'Share Tech Mono', monospace;
      font-size: 13px;
      color: rgba(255,255,255,0.72);
      line-height: 1.9;
      margin-bottom: 24px;
    }

    .section-title {
      font-size: 11px;
      color: var(--pink);
      text-shadow: 0 0 8px var(--pink);
      margin: 22px 0 10px;
    }

    /* ── FEATURE PILLS ── */
    .features {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin: 16px 0 24px;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: 'Share Tech Mono', monospace;
      font-size: 13px;
      color: var(--cyan);
    }

    .feature-item::before {
      content: '▸';
      color: var(--pink);
      font-size: 12px;
    }

    /* ── ICON ROW ── */
    .icon-row {
      display: flex;
      gap: 16px;
      margin-top: 28px;
      flex-wrap: wrap;
    }

    .icon-wrap {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
    }

    .icon-wrap img {
      width: 56px; height: 56px;
      object-fit: contain;
      border-radius: 10px;
      border: 1px solid rgba(0,240,255,0.25);
      background: rgba(0,0,0,0.40);
      padding: 6px;
      box-shadow: 0 0 10px rgba(0,240,255,0.25);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .icon-wrap img:hover {
      transform: scale(1.2) translateY(-4px);
      box-shadow: 0 0 20px var(--blue), 0 0 50px rgba(0,240,255,0.6);
    }

    .icon-wrap span {
      font-size: 7px;
      color: rgba(255,255,255,0.55);
      letter-spacing: 0.5px;
    }

    /* ── RIGHT IMAGE ── */
    .right-image {
      width: 42%;
      display: flex;
      justify-content: center;
      position: relative;
    }

    /* glow ring behind image */
    .right-image::before {
      content: '';
      position: absolute;
      width: 85%;
      aspect-ratio: 1;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(0,94,255,0.25) 0%, transparent 70%);
      top: 50%; left: 50%;
      transform: translate(-50%,-50%);
      pointer-events: none;
    }

    .right-image img {
      width: 85%;
      max-width: 420px;
      border-radius: 18px;
      border: 1px solid rgba(0,240,255,0.25);
      box-shadow: 0 0 20px rgba(0,240,255,0.30);
      transition: transform 0.4s, box-shadow 0.4s;
      position: relative;
    }

    .right-image img:hover {
      transform: scale(1.05) translateY(-6px);
      box-shadow: 0 0 30px var(--blue), 0 0 70px rgba(0,240,255,0.55);
    }

    /* ── SECTION WRAPPER ── */
    .section {
      padding: 80px 5%;
    }

    .section-heading {
      font-size: 16px;
      color: var(--pink);
      text-shadow: 0 0 10px var(--pink);
      margin-bottom: 30px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .section-heading::after {
      content: '';
      flex: 1;
      height: 1px;
      background: linear-gradient(90deg, rgba(255,0,254,0.40), transparent);
    }

    /* ── GALLERY ── */
    .gallery {
      display: flex;
      gap: 18px;
      overflow-x: auto;
      scroll-snap-type: x mandatory;
      padding-bottom: 12px;
    }

    .gallery::-webkit-scrollbar { height: 3px; }
    .gallery::-webkit-scrollbar-thumb { background: var(--pink); border-radius: 2px; }

    .gallery-item {
      flex: 0 0 auto;
      scroll-snap-align: start;
      position: relative;
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid rgba(0,240,255,0.20);
      box-shadow: 0 0 12px rgba(0,240,255,0.20);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .gallery-item:hover {
      transform: scale(1.05) translateY(-5px);
      box-shadow: 0 0 28px var(--blue), 0 0 60px rgba(0,240,255,0.55);
    }

    .gallery-item img {
      width: 300px;
      height: 200px;
      object-fit: cover;
      display: block;
    }

    /* ── PRIVACY ── */
    .policy-box {
      background: rgba(0,0,0,0.45);
      border-left: 3px solid var(--pink);
      border-radius: 12px;
      padding: 28px;
      position: relative;
      overflow: hidden;
    }

    /* scanline effect */
    .policy-box::before {
      content: '';
      position: absolute;
      inset: 0;
      background: repeating-linear-gradient(
        0deg,
        transparent,
        transparent 3px,
        rgba(0,240,255,0.02) 3px,
        rgba(0,240,255,0.02) 4px
      );
      pointer-events: none;
    }

    .policy-box p {
      font-family: 'Share Tech Mono', monospace;
      font-size: 13px;
      color: rgba(255,255,255,0.72);
      line-height: 2;
      position: relative;
      z-index: 1;
    }

    /* ── FOOTER ── */
    footer {
      padding: 40px 5%;
      text-align: center;
      border-top: 1px solid rgba(0,240,255,0.12);
      background: rgba(0,0,0,0.50);
    }

    .footer-brand {
      font-size: 10px;
      color: var(--cyan);
      text-shadow: 0 0 8px var(--cyan);
      margin-bottom: 24px;
      letter-spacing: 2px;
    }

    .social-icons {
      display: flex;
      justify-content: center;
      gap: 28px;
      margin-bottom: 24px;
    }

    .social-icons a {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      text-decoration: none;
    }

    .social-icons img {
      width: 52px; height: 52px;
      object-fit: contain;
      filter: drop-shadow(0 0 6px rgba(0,240,255,0.40));
      transition: transform 0.3s, filter 0.3s;
      opacity: 0.85;
    }

    .social-icons a:hover img {
      transform: scale(1.25) translateY(-3px);
      opacity: 1;
      filter: drop-shadow(0 0 12px var(--blue)) drop-shadow(0 0 24px var(--cyan));
    }

    .footer-copy {
      font-size: 8px;
      color: rgba(255,255,255,0.30);
      letter-spacing: 1px;
    }

    /* ── PIXEL CURSOR BLINK ── */
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }
    .cursor { display: inline-block; width: 8px; height: 14px; background: var(--cyan); vertical-align: middle; margin-left: 4px; animation: blink 1s step-end infinite; }

    /* ── FADE IN ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .hero .left-content { animation: fadeUp 0.7s ease both; }
    .hero .right-image  { animation: fadeUp 0.7s 0.15s ease both; }
  </style>
</head>
<body>

<!-- VIDEO BG -->
<video autoplay muted loop id="bg-video" playsinline>
  <source src="cyberpunk-gaming-pixel-moewalls-com.mp4" type="video/mp4">
</video>
<div class="overlay"></div>

<!-- NAV -->
<nav>
  <span class="nav-brand">BINARY_MINDS<span class="cursor"></span></span>
</nav>

<!-- HERO -->
<section class="hero">

  <div class="left-content">
    <p class="pre-label">// binary_minds</p>
    <h1>POURQUOI<br>BINARY MINDS ?</h1>
    <p class="tagline">Apprendre à coder, évoluer et créer sans limites.</p>

    <p class="body-text">
      Binary Minds est une plateforme d'apprentissage innovante dédiée à la programmation,
      offrant un parcours structuré autour de quatre langages clés.
    </p>

    <p class="section-title">// Notre mission</p>
    <p class="body-text">Rendre l'apprentissage accessible et motivant pour tous les niveaux.</p>

    <div class="features">
      <div class="feature-item">Apprentissage interactif pixel art</div>
      <div class="feature-item">C, C#, Java et Python</div>
      <div class="feature-item">Progression débutant → avancé</div>
    </div>

    <p class="section-title">// Qui sommes-nous ?</p>
    <p class="body-text">Un projet porté par des passionnés de technologie et d'innovation.</p>

    <div class="icon-row">
      <div class="icon-wrap">
        <img src="WhatsApp_Image_2026-03-26_at_9.50.43_PM-removebg-preview.png" alt="Python">
        <span>PYTHON</span>
      </div>
      <div class="icon-wrap">
        <img src="WhatsApp_Image_2026-03-26_at_9.50.43_PM__1_-removebg-preview.png" alt="C#">
        <span>C#</span>
      </div>
      <div class="icon-wrap">
        <img src="C_Pixel_Art-removebg-preview.png" alt="C">
        <span>C</span>
      </div>
      <div class="icon-wrap">
        <img src="bit1_java-removebg-preview.png" alt="Java">
        <span>JAVA</span>
      </div>
    </div>
  </div>

  <div class="right-image">
    <img src="ChatGPT Image Apr 17, 2026, 04_40_48 PM.png" alt="Binary Minds mascot">
  </div>

</section>

<!-- GALLERY -->
<section class="section">
  <h2 class="section-heading">Aperçu de la plateforme</h2>
  <div class="gallery">
    <div class="gallery-item"><img src="WhatsApp Image 2026-04-25 at 1.21.22 PM (1).jpeg" alt="Screenshot 1"></div>
    <div class="gallery-item"><img src="WhatsApp Image 2026-04-25 at 1.21.22 PM (4).jpeg" alt="Screenshot 2"></div>
    <div class="gallery-item"><img src="WhatsApp Image 2026-04-25 at 1.21.22 PM (3).jpeg" alt="Screenshot 3"></div>
    <div class="gallery-item"><img src="WhatsApp Image 2026-04-25 at 1.21.22 PM (2).jpeg" alt="Screenshot 4"></div>
    <div class="gallery-item"><img src="WhatsApp Image 2026-04-25 at 1.21.22 PM.jpeg"    alt="Screenshot 5"></div>
    <div class="gallery-item"><img src="WhatsApp Image 2026-04-21 at 8.15.25 PM.jpeg"    alt="Screenshot 6"></div>
    <div class="gallery-item"><img src="WhatsApp Image 2026-04-21 at 8.16.13 PM.jpeg  "    alt="Screenshot 7"></div>
    <div class="gallery-item"><img src="WhatsApp Image 2026-04-18 at 2.04.10 PM.jpeg  "    alt="Screenshot 8"></div>
    
  
    
  </div>
</section>

<!-- PRIVACY -->
<section class="section">
  <h2 class="section-heading">Politique de confidentialité</h2>
  <div class="policy-box">
    <p>
      La protection des données et la sécurité de nos utilisateurs sont une priorité chez Binary Minds.
      Aucune information personnelle sensible n'est partagée avec des tiers.<br><br>
      Afin de garantir un environnement d'apprentissage sécurisé et concentré,
      notre plateforme ne permet pas la communication entre utilisateurs.
      Les apprenants ne peuvent ni discuter, ni envoyer de messages entre eux.<br><br>
      L'objectif est de maintenir une expérience sans distraction,
      entièrement dédiée à l'apprentissage de la programmation.
    </p>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <p class="footer-brand">BINARY_MINDS © 2026</p>
  <div class="social-icons">
    <a href="https://www.facebook.com" target="_blank" rel="noopener">
      <img src="Pixel_Facebook_Icon_Vector_Free_Download___Logowik-removebg-preview.png" alt="Facebook">
    </a>
    <a href="mailto:yourmail@gmail.com">
      <img src="download__7_-removebg-preview.png" alt="Email">
    </a>
    <a href="https://www.instagram.com" target="_blank" rel="noopener">
      <img src="ig-removebg-preview.png" alt="Instagram">
    </a>
  </div>
  <p class="footer-copy">MADE WITH &lt;3 BY BINARY MINDS TEAM</p>
</footer>

</body>
</html>
