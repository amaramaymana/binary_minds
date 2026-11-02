<?php
session_start();

// Valeurs reçues depuis la page test : conseil.php?score=14&total=20&lang=java
$score = isset($_GET['score']) ? intval($_GET['score']) : 1;
$total = isset($_GET['total']) ? intval($_GET['total']) : 100;
$lang  = isset($_GET['lang']) ? htmlspecialchars($_GET['lang']) : 'Programmation';

if ($total <= 0) { $total = 100; }
if ($score < 0) { $score = 0; }
if ($score > $total) { $score = $total; }

$percent = round(($score / $total) * 100);

if ($percent >= 85) {
    $level = 'MASTER MIND';
    $rank = 'Top 5%';
    $message = 'Excellent niveau. Tu maîtrises bien les bases, maintenant il faut viser la rapidité et les exercices avancés.';
    $mainAdvice = 'Travaille les questions difficiles, les pièges et les mini-projets pour renforcer ton niveau.';
} elseif ($percent >= 65) {
    $level = 'ADVANCED PLAYER';
    $rank = 'Top 20%';
    $message = 'Bon niveau. Tu comprends la majorité des notions, mais il reste quelques erreurs à corriger.';
    $mainAdvice = 'Refais les questions fausses, note les notions répétées et révise-les avec des exemples simples.';
} elseif ($percent >= 45) {
    $level = 'RISING CODER';
    $rank = 'Top 50%';
    $message = 'Niveau moyen. Tu as compris une partie du cours, mais il faut organiser la révision.';
    $mainAdvice = 'Reprends les bases étape par étape : syntaxe, conditions, boucles, fonctions et tableaux.';
} else {
    $level = 'NEWBIE DEV';
    $rank = 'Débutant';
    $message = 'Ce n’est pas grave. Le score montre seulement que tu dois reprendre les bases calmement.';
    $mainAdvice = 'Commence par les notions fondamentales et pratique chaque jour avec des petits exercices.';
}

// Données simulées pour le graphe d’avancement. Tu peux les remplacer plus tard par la base de données.
$progressData = [35, 48, 52, 61, $percent];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Binary Minds — Conseil</title>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
<style>
:root{
    --bg:#0d0730;
    --bg2:#170b4d;
    --panel:rgba(255,255,255,.075);
    --panel2:rgba(255,255,255,.12);
    --border:rgba(249,168,212,.28);
    --pink:#f9a8d4;
    --purple:#c084fc;
    --violet:#7c3aed;
    --cyan:#67e8f9;
    --gold:#fde68a;
    --white:#fff7ff;
    --muted:#d8c7ff;
    --darkText:#2e1065;
}
*{box-sizing:border-box;margin:0;padding:0;image-rendering:pixelated}
html,body{width:100%;height:100%;overflow:hidden}
body{
    font-family:'VT323', monospace;
    color:var(--white);
    background:var(--bg);
}

/* BACKGROUND */
.sky-bg{
    position:fixed;inset:0;z-index:0;
    background:
        radial-gradient(circle at 20% 20%, rgba(249,168,212,.18), transparent 28%),
        radial-gradient(circle at 82% 65%, rgba(103,232,249,.14), transparent 25%),
        linear-gradient(180deg,#080420 0%,#170b4d 50%,#0d0730 100%);
}
.sky-bg::after{
    content:'';position:absolute;inset:0;opacity:.38;
    background-image:
        linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.06) 1px, transparent 1px);
    background-size:42px 42px;
    mask-image:linear-gradient(to bottom,transparent 0%,#000 35%,#000 100%);
}
.stars{position:fixed;inset:0;z-index:1;pointer-events:none}
.star{position:absolute;width:4px;height:4px;background:var(--pink);box-shadow:0 0 12px var(--pink);animation:twinkle 2.5s infinite ease-in-out}
.star:nth-child(2n){background:var(--gold);box-shadow:0 0 12px var(--gold);animation-delay:.6s}
.star:nth-child(3n){background:var(--cyan);box-shadow:0 0 12px var(--cyan);animation-delay:1.1s}
@keyframes twinkle{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.35;transform:scale(.65)}}

/* SLIDER ENGINE - adapté depuis ton exemple */
.slider-container{
    position:relative;
    height:100vh;
    width:100%;
    z-index:5;
}
.section-slide{
    position:absolute;
    inset:0;
    width:100%;height:100%;
    opacity:0;visibility:hidden;
    transition:all 1.2s cubic-bezier(0.25,1,0.5,1);
    display:flex;align-items:center;justify-content:center;
    padding:40px 70px;
    overflow:hidden;
    z-index:1;
}
.section-slide.active{opacity:1;visibility:visible;z-index:2}
.section-slide::before{
    content:'';position:absolute;inset:0;
    background:linear-gradient(180deg,rgba(8,4,32,.62),rgba(8,4,32,.28),rgba(8,4,32,.78));
    z-index:1;
}
.content-wrapper{
    position:relative;z-index:5;
    width:92%;max-width:1180px;
}

/* NAV DOTS */
.nav-dots{
    position:fixed;right:28px;top:50%;transform:translateY(-50%);
    z-index:100;display:flex;flex-direction:column;gap:14px;
}
.dot{
    width:12px;height:12px;border-radius:50%;
    border:1px solid rgba(255,255,255,.35);
    cursor:pointer;transition:.35s ease;background:rgba(255,255,255,.04);
}
.dot.active{
    background:var(--pink);border-color:var(--pink);
    transform:scale(1.35);box-shadow:0 0 18px rgba(249,168,212,.75);
}

/* TYPO */
.kicker{
    font-family:'Press Start 2P';font-size:.62rem;letter-spacing:3px;
    color:var(--pink);text-transform:uppercase;margin-bottom:20px;
    opacity:0;transform:translateY(22px);transition:all .8s ease;
}
.title-main{
    font-family:'Press Start 2P';font-size:clamp(1.8rem,5vw,4.4rem);
    line-height:1.22;letter-spacing:2px;text-transform:uppercase;
    text-shadow:0 0 28px rgba(192,132,252,.75),4px 4px 0 rgba(0,0,0,.75);
    margin-bottom:22px;opacity:0;transform:translateY(34px);transition:all .8s ease;
}
.slide-text{
    font-size:1.55rem;line-height:1.55;color:var(--muted);
    max-width:720px;opacity:0;transform:translateY(24px);transition:all .8s ease;
}
.active .kicker{opacity:1;transform:translateY(0);transition-delay:.2s}
.active .title-main{opacity:1;transform:translateY(0);transition-delay:.35s}
.active .slide-text{opacity:1;transform:translateY(0);transition-delay:.5s}
.text-pink{color:var(--pink)}
.text-gold{color:var(--gold)}

/* BUTTON */
.pixel-btn{
    display:inline-block;text-decoration:none;border:none;cursor:pointer;
    margin-top:32px;padding:16px 28px;border-radius:999px;
    font-family:'Press Start 2P';font-size:.64rem;letter-spacing:1px;
    background:linear-gradient(135deg,var(--pink),var(--purple));
    color:#2e1065;box-shadow:0 6px 0 rgba(0,0,0,.55),0 0 24px rgba(249,168,212,.35);
    opacity:0;transform:translateY(24px);transition:.22s ease, opacity .8s ease, transform .8s ease;
}
.active .pixel-btn{opacity:1;transform:translateY(0);transition-delay:.68s}
.pixel-btn:hover{transform:translateY(-4px);box-shadow:0 10px 0 rgba(0,0,0,.55),0 0 30px rgba(249,168,212,.55)}

/* COMMON CARDS */
.glass-panel{
    background:linear-gradient(180deg,rgba(255,255,255,.12),rgba(255,255,255,.055));
    border:1px solid var(--border);
    border-radius:28px;
    backdrop-filter:blur(14px);
    box-shadow:0 24px 70px rgba(0,0,0,.38),inset 0 0 0 1px rgba(255,255,255,.04);
}

/* SLIDE 1 HERO */
.hero{text-align:center}
.hero .slide-text{margin:0 auto}
.score-badge{
    display:inline-flex;align-items:center;gap:14px;
    padding:16px 22px;border-radius:999px;margin-bottom:22px;
    background:rgba(255,255,255,.09);border:1px solid rgba(249,168,212,.28);
    font-family:'Press Start 2P';font-size:.65rem;color:var(--gold);
    opacity:0;transform:translateY(22px);transition:all .8s ease;
}
.active .score-badge{opacity:1;transform:translateY(0);transition-delay:.15s}

/* SLIDE 2 RANK */
.rank-layout{display:grid;grid-template-columns:.85fr 1.15fr;gap:36px;align-items:center}
.rank-card{padding:34px;text-align:center;opacity:0;transform:scale(.94);transition:all .9s ease}
.active .rank-card{opacity:1;transform:scale(1);transition-delay:.45s}
.rank-circle{
    width:250px;height:250px;margin:0 auto 24px;border-radius:50%;
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    background:conic-gradient(var(--pink) <?php echo $percent; ?>%, rgba(255,255,255,.12) 0);
    box-shadow:0 0 40px rgba(249,168,212,.24);
    position:relative;
}
.rank-circle::before{content:'';position:absolute;inset:18px;border-radius:50%;background:#14093d;border:1px solid rgba(255,255,255,.1)}
.rank-circle strong,.rank-circle span{position:relative;z-index:2}
.rank-circle strong{font-family:'Press Start 2P';font-size:2rem;color:#fff}
.rank-circle span{font-size:1.3rem;color:var(--muted);margin-top:8px}
.level-name{font-family:'Press Start 2P';font-size:1rem;color:var(--pink);margin-bottom:12px}
.rank-mini{font-size:1.4rem;color:var(--gold)}

/* SLIDE 3 PROGRESS GRAPH */
.graph-panel{padding:34px;opacity:0;transform:translateY(45px);transition:all .9s ease}
.active .graph-panel{opacity:1;transform:translateY(0);transition-delay:.45s}
.chart{
    height:330px;display:flex;align-items:flex-end;gap:22px;
    padding:24px;border-radius:22px;background:rgba(255,255,255,.055);
    border:1px solid rgba(255,255,255,.08);
}
.bar-wrap{flex:1;display:flex;flex-direction:column;align-items:center;gap:12px;height:100%;justify-content:flex-end}
.bar{
    width:100%;max-width:90px;min-height:12px;border-radius:16px 16px 6px 6px;
    background:linear-gradient(180deg,var(--pink),var(--violet));
    box-shadow:0 0 18px rgba(249,168,212,.28);
    transform:scaleY(0);transform-origin:bottom;transition:transform 1s cubic-bezier(.25,1,.5,1);
}
.active .bar{transform:scaleY(1)}
.bar-wrap:nth-child(1) .bar{transition-delay:.35s}
.bar-wrap:nth-child(2) .bar{transition-delay:.48s}
.bar-wrap:nth-child(3) .bar{transition-delay:.61s}
.bar-wrap:nth-child(4) .bar{transition-delay:.74s}
.bar-wrap:nth-child(5) .bar{transition-delay:.87s}
.bar-label{font-family:'Press Start 2P';font-size:.52rem;color:var(--muted)}
.bar-value{font-size:1.3rem;color:#fff}

/* SLIDE 4 ADVICE */
.cards-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:42px}
.advice-card{
    padding:26px;border-radius:24px;background:rgba(255,255,255,.09);
    border:1px solid rgba(249,168,212,.18);
    opacity:0;transform:translateY(44px);transition:all .85s ease;
}
.active .advice-card:nth-child(1){opacity:1;transform:translateY(0);transition-delay:.35s}
.active .advice-card:nth-child(2){opacity:1;transform:translateY(0);transition-delay:.52s}
.active .advice-card:nth-child(3){opacity:1;transform:translateY(0);transition-delay:.69s}
.advice-card h3{font-family:'Press Start 2P';font-size:.75rem;color:var(--gold);margin-bottom:16px;line-height:1.5}
.advice-card p,.advice-card li{font-size:1.25rem;color:var(--muted);line-height:1.5}
.advice-card ul{padding-left:18px}

/* SLIDE 5 POMODORO */
.pomodoro-layout{display:grid;grid-template-columns:1fr 1fr;gap:34px;align-items:center}
.timer-box{padding:38px;text-align:center;opacity:0;transform:translateY(42px);transition:all .9s ease}
.active .timer-box{opacity:1;transform:translateY(0);transition-delay:.45s}
.timer-display{font-family:'Press Start 2P';font-size:clamp(2.1rem,6vw,4.5rem);color:#fff;margin:24px 0;text-shadow:0 0 28px rgba(249,168,212,.6)}
.timer-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.small-btn{
    border:none;border-radius:999px;padding:13px 18px;cursor:pointer;
    font-family:'Press Start 2P';font-size:.55rem;background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(249,168,212,.26);
}
.small-btn.primary{background:linear-gradient(135deg,var(--pink),var(--purple));color:#2e1065}

/* SLIDE 6 FINAL */
.final-box{text-align:center}
.check-list{max-width:720px;margin:32px auto 0;display:grid;gap:14px}
.check-item{
    padding:15px 20px;border-radius:18px;background:rgba(255,255,255,.09);border:1px solid rgba(249,168,212,.18);
    font-size:1.35rem;color:var(--muted);opacity:0;transform:translateX(-35px);transition:all .75s ease;
}
.active .check-item:nth-child(1){opacity:1;transform:translateX(0);transition-delay:.35s}
.active .check-item:nth-child(2){opacity:1;transform:translateX(0);transition-delay:.5s}
.active .check-item:nth-child(3){opacity:1;transform:translateX(0);transition-delay:.65s}
.active .check-item:nth-child(4){opacity:1;transform:translateX(0);transition-delay:.8s}

/* TOP NAV */
.top-link{position:fixed;top:22px;left:24px;z-index:200;text-decoration:none;color:var(--muted);font-family:'Press Start 2P';font-size:.56rem;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);padding:12px 14px;border-radius:999px}
.slide-hint{position:fixed;bottom:20px;left:50%;transform:translateX(-50%);z-index:200;font-size:1.1rem;color:rgba(255,255,255,.55)}

@media(max-width:900px){
    .section-slide{padding:34px 24px;overflow-y:auto}
    .nav-dots{display:none}
    .rank-layout,.pomodoro-layout{grid-template-columns:1fr}
    .cards-grid{grid-template-columns:1fr}
    .chart{height:270px;gap:12px;padding:16px}
    .rank-circle{width:210px;height:210px}
    html,body{overflow:hidden}
}
</style>
</head>
<body>
<div class="sky-bg"></div>
<div class="stars" id="stars"></div>
<a href="dashboard_player.php" class="top-link">← DASHBOARD</a>
<div class="slide-hint">Scroll / ↑ ↓ / Swipe</div>

<div class="nav-dots" id="navDots"></div>

<div class="slider-container" id="slider">

    <section class="section-slide active">
        <div class="content-wrapper hero">
            <div class="score-badge">RESULTAT TEST • <?php echo strtoupper($lang); ?></div>
            <p class="kicker">Binary Minds Advice Center</p>
            <h1 class="title-main">Analyse de ton <span class="text-pink">niveau</span></h1>
            <p class="slide-text">
                Tu as obtenu <span class="text-gold"><?php echo $score; ?>/<?php echo $total; ?></span>.
                Cette page te donne des conseils selon tes erreurs, une motivation claire, une méthode de révision et un Pomodoro pour travailler efficacement.
            </p>
            <button class="pixel-btn" onclick="nextSlide()">COMMENCER ▶</button>
        </div>
    </section>

    <section class="section-slide">
        <div class="content-wrapper rank-layout">
            <div class="rank-card glass-panel">
                <div class="rank-circle">
                    <strong><?php echo $percent; ?>%</strong>
                    <span>Score</span>
                </div>
                <div class="level-name"><?php echo $level; ?></div>
                <div class="rank-mini">Classement : <?php echo $rank; ?></div>
            </div>
            <div>
                <p class="kicker">Rank Classification</p>
                <h2 class="title-main">Ta position dans <span class="text-pink">Binary Minds</span></h2>
                <p class="slide-text"><?php echo $message; ?> Ce classement est une estimation par rapport aux autres utilisateurs de la plateforme.</p>
            </div>
        </div>
    </section>

    <section class="section-slide">
        <div class="content-wrapper">
            <p class="kicker">Progress Tracking</p>
            <h2 class="title-main">Graphe d’<span class="text-pink">avancement</span></h2>
            <p class="slide-text">Voici une vision simple de ton évolution sur les derniers tests. Le dernier résultat correspond au score actuel.</p>
            <div class="graph-panel glass-panel">
                <div class="chart">
                    <?php foreach($progressData as $i => $v): ?>
                    <div class="bar-wrap">
                        <div class="bar-value"><?php echo $v; ?>%</div>
                        <div class="bar" style="height:<?php echo max(8,$v); ?>%"></div>
                        <div class="bar-label">T<?php echo $i+1; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="section-slide">
        <div class="content-wrapper">
            <p class="kicker">Conseils personnalisés</p>
            <h2 class="title-main">Selon tes <span class="text-pink">erreurs</span></h2>
            <p class="slide-text"><?php echo $mainAdvice; ?></p>
            <div class="cards-grid">
                <div class="advice-card">
                    <h3>1. Corriger les erreurs</h3>
                    <ul>
                        <li>Relis chaque question fausse.</li>
                        <li>Note la notion qui revient souvent.</li>
                        <li>Refais un exemple simple.</li>
                    </ul>
                </div>
                <div class="advice-card">
                    <h3>2. Réviser mieux</h3>
                    <ul>
                        <li>20 minutes de cours.</li>
                        <li>30 minutes d’exercices.</li>
                        <li>10 minutes de résumé.</li>
                    </ul>
                </div>
                <div class="advice-card">
                    <h3>3. Monter le score</h3>
                    <ul>
                        <li>Repasser le test après révision.</li>
                        <li>Comparer l’ancien score au nouveau.</li>
                        <li>Travailler les thèmes faibles.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="section-slide">
        <div class="content-wrapper pomodoro-layout">
            <div>
                <p class="kicker">Pomodoro Focus</p>
                <h2 class="title-main">Révise avec une <span class="text-pink">méthode</span></h2>
                <p class="slide-text">Utilise 25 minutes de concentration puis 5 minutes de pause. Après 4 cycles, prends une pause plus longue.</p>
            </div>
            <div class="timer-box glass-panel">
                <div class="level-name">POMODORO TIMER</div>
                <div class="timer-display" id="timerDisplay">25:00</div>
                <div class="timer-actions">
                    <button class="small-btn primary" onclick="startPomodoro()">START</button>
                    <button class="small-btn" onclick="pausePomodoro()">PAUSE</button>
                    <button class="small-btn" onclick="resetPomodoro()">RESET</button>
                </div>
            </div>
        </div>
    </section>

    <section class="section-slide">
        <div class="content-wrapper final-box">
            <p class="kicker">Motivation</p>
            <h2 class="title-main">Tu peux <span class="text-pink">progresser</span></h2>
            <p class="slide-text" style="margin:0 auto">Un mauvais score n’est pas un échec. C’est juste une indication claire de ce qu’il faut améliorer.</p>
            <div class="check-list">
                <div class="check-item">Refais les questions fausses aujourd’hui.</div>
                <div class="check-item">Révise les bases avant les notions avancées.</div>
                <div class="check-item">Utilise Pomodoro pour éviter la fatigue.</div>
                <div class="check-item">Reviens demain et compare ton score.</div>
            </div>
            <a class="pixel-btn" href="dashboard_player.php">RETOUR DASHBOARD</a>
        </div>
    </section>

</div>

<script>
// Stars
const starsBox = document.getElementById('stars');
for(let i=0;i<75;i++){
    const s=document.createElement('span');
    s.className='star';
    s.style.left=Math.random()*100+'%';
    s.style.top=Math.random()*100+'%';
    s.style.animationDelay=(Math.random()*2.8)+'s';
    starsBox.appendChild(s);
}

// Slide logic adaptée du code fourni
const slides = document.querySelectorAll('.section-slide');
const dotsContainer = document.getElementById('navDots');
let currentSlide = 0;
let isAnimating = false;

slides.forEach((_, index) => {
    const dot = document.createElement('div');
    dot.classList.add('dot');
    if(index === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(index));
    dotsContainer.appendChild(dot);
});
const dots = document.querySelectorAll('.dot');

function goToSlide(index){
    if(isAnimating || index === currentSlide) return;
    if(index < 0 || index >= slides.length) return;
    isAnimating = true;
    slides[currentSlide].classList.remove('active');
    dots[currentSlide].classList.remove('active');
    currentSlide = index;
    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');
    setTimeout(() => { isAnimating = false; }, 1200);
}
function nextSlide(){ if(currentSlide < slides.length - 1) goToSlide(currentSlide + 1); }
function prevSlide(){ if(currentSlide > 0) goToSlide(currentSlide - 1); }

window.addEventListener('wheel', (e) => {
    if(e.deltaY > 0) nextSlide();
    else prevSlide();
});
window.addEventListener('keydown', (e) => {
    if(e.key === 'ArrowDown' || e.key === 'PageDown') nextSlide();
    if(e.key === 'ArrowUp' || e.key === 'PageUp') prevSlide();
});
let touchStartY = 0;
let touchEndY = 0;
window.addEventListener('touchstart', e => touchStartY = e.changedTouches[0].screenY);
window.addEventListener('touchend', e => {
    touchEndY = e.changedTouches[0].screenY;
    if(touchStartY - touchEndY > 50) nextSlide();
    if(touchEndY - touchStartY > 50) prevSlide();
});

// Pomodoro
let pomodoroSeconds = 25 * 60;
let pomodoroInterval = null;
let running = false;
function renderTimer(){
    const m = Math.floor(pomodoroSeconds / 60);
    const s = pomodoroSeconds % 60;
    document.getElementById('timerDisplay').textContent = `${m}:${s < 10 ? '0' : ''}${s}`;
}
function startPomodoro(){
    if(running) return;
    running = true;
    pomodoroInterval = setInterval(() => {
        if(pomodoroSeconds > 0){
            pomodoroSeconds--;
            renderTimer();
        }else{
            clearInterval(pomodoroInterval);
            running = false;
            alert('Pomodoro terminé ! Prends une pause de 5 minutes.');
        }
    }, 1000);
}
function pausePomodoro(){
    clearInterval(pomodoroInterval);
    running = false;
}
function resetPomodoro(){
    clearInterval(pomodoroInterval);
    running = false;
    pomodoroSeconds = 25 * 60;
    renderTimer();
}
renderTimer();
</script>
</body>
</html>
