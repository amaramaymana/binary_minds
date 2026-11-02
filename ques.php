<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> Quiz </title>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;image-rendering:pixelated}
:root{
  --bg:#1a1264;
  --bg2:#120e50;
  --cloud1:#4a2d8a;
  --cloud2:#3d2070;
  --cloud3:#5c3a9e;
  --star-pink:#f9a8d4;
  --star-gold:#fde68a;
  --star-white:#e0d7ff;
  --ghost:#e879f9;
  --neon-purple:#c026d3;
  --neon-pink:#ff2d78;
  --card-bg:rgba(23,14,72,0.84);
  --card-border:rgba(249,168,212,0.34);
  --text:#f7edff;
  --muted:#d8c1ff;
  --soft-beige:#f4dfb6;
  --soft-pink:#f9a8d4;
  --soft-purple:#c084fc;
  --deep:#120a3d;
  --btn-play:#f4dfb6;
  --btn-menu:#fde68a;
  --btn-exit:#c4b5fd;
  --green:#4ade80;
  --red:#f87171;
}

html,body{height:100%;overflow-x:hidden}

/* ===== PIXEL NIGHT SKY BACKGROUND ===== */
body{
  font-family:'VT323',monospace;
  color:var(--text);
  margin:0;
  min-height:100vh;
  background-color:var(--bg);
  position:relative;
  overflow-x:hidden;
}

/* Base sky gradient */
.sky-bg{
  position:fixed;inset:0;z-index:0;
  background:
    radial-gradient(ellipse 60% 40% at 30% 20%, #1e1580 0%, transparent 70%),
    radial-gradient(ellipse 50% 35% at 75% 60%, #160f6a 0%, transparent 65%),
    linear-gradient(180deg, #0e0a42 0%, #1a1264 35%, #150e58 70%, #0f0a40 100%);
}

/* ===== PIXEL STAR CANVAS ===== */
.stars-layer{
  position:fixed;inset:0;z-index:1;pointer-events:none;
  overflow:hidden;
}

/* Cross/sparkle stars using box-shadow */
.star-cross{
  position:absolute;
  width:4px;height:4px;
  background:transparent;
}
.star-cross::before,.star-cross::after{
  content:'';position:absolute;
  background:var(--star-pink);
}
.star-cross::before{width:4px;height:12px;top:-4px;left:0}
.star-cross::after{width:12px;height:4px;top:4px;left:-4px}

.star-sm{position:absolute;width:4px;height:4px;background:var(--star-pink)}
.star-sm::before,.star-sm::after{content:'';position:absolute;background:var(--star-pink)}
.star-sm::before{width:2px;height:8px;top:-2px;left:1px}
.star-sm::after{width:8px;height:2px;top:1px;left:-2px}

.star-gold-c{position:absolute;width:4px;height:4px;background:var(--star-gold)}
.star-gold-c::before,.star-gold-c::after{content:'';position:absolute;background:var(--star-gold)}
.star-gold-c::before{width:2px;height:10px;top:-3px;left:1px}
.star-gold-c::after{width:10px;height:2px;top:3px;left:-3px}

.star-dot{position:absolute;width:4px;height:4px;background:var(--star-white);opacity:.7}
.star-dot2{position:absolute;width:2px;height:2px;background:var(--star-pink);opacity:.6}

/* Twinkling animation */
@keyframes twinkle{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.3;transform:scale(.7)}}
@keyframes twinkle2{0%,100%{opacity:.6;transform:scale(1)}60%{opacity:1;transform:scale(1.3)}}
.tw1{animation:twinkle 2.4s ease-in-out infinite}
.tw2{animation:twinkle 3.1s ease-in-out infinite .6s}
.tw3{animation:twinkle 1.8s ease-in-out infinite 1.2s}
.tw4{animation:twinkle2 2.7s ease-in-out infinite .3s}
.tw5{animation:twinkle 4s ease-in-out infinite .9s}
.tw6{animation:twinkle2 3.5s ease-in-out infinite 1.5s}

/* ===== PIXEL CLOUDS ===== */
.cloud-layer{position:fixed;inset:0;z-index:2;pointer-events:none;overflow:hidden}

.px-cloud{position:absolute}
/* Cloud made of pixel blocks */
.px-cloud svg{display:block}

/* ===== GRID FLOOR ===== */
.grid-floor{
  position:fixed;bottom:0;left:0;right:0;height:40vh;
  perspective:280px;pointer-events:none;z-index:3;
}
.grid-inner{
  width:100%;height:100%;
  transform:rotateX(58deg);transform-origin:top center;
  background-image:
    linear-gradient(to right,rgba(160,50,255,.22) 1px,transparent 1px),
    linear-gradient(to bottom,rgba(220,50,200,.16) 1px,transparent 1px);
  background-size:55px 38px;
}

/* ===== SCENE ===== */
.scene{
  position:relative;z-index:10;
  min-height:100vh;
  display:flex;flex-direction:column;
  align-items:center;justify-content:center;
  padding:34px 18px 90px;
}

/* VIEWS */
.view{display:none}
.view.on{display:flex;flex-direction:column;align-items:center;width:100%;max-width:780px}

/* ===== GHOSTS ===== */
.ghosts-row{display:flex;justify-content:center;gap:20px;margin-bottom:14px}
.ghost{animation:ghost-float 1.8s ease-in-out infinite alternate}
.ghost:nth-child(2){animation-delay:.5s}
.ghost:nth-child(3){animation-delay:1s}
@keyframes ghost-float{from{transform:translateY(0)}to{transform:translateY(-10px)}}

/* ===== TITLE ===== */
.main-title{
  font-family:'Press Start 2P',cursive;
  font-size:clamp(1.8rem,5.5vw,3rem);
  color:#fff;
  text-shadow:0 0 24px rgba(200,50,255,.9),0 0 50px rgba(200,50,255,.4),4px 4px 0 rgba(0,0,0,.9);
  letter-spacing:3px;text-align:center;
  margin-bottom:6px;line-height:1.2;
}
.main-sub{
  font-family:'Press Start 2P';font-size:.52rem;
  color:var(--ghost);letter-spacing:3px;
  margin-bottom:26px;text-align:center;
  text-shadow:0 0 10px rgba(232,121,249,.6);
}

/* ===== GLASS CARD ===== */
.glass-card{
  background:
    linear-gradient(180deg,rgba(255,255,255,.095),rgba(255,255,255,.025)),
    var(--card-bg);
  border:1.5px solid var(--card-border);
  border-radius:24px;
  padding:32px;
  width:100%;max-width:640px;
  box-shadow:
    0 24px 65px rgba(0,0,0,.35),
    0 0 42px rgba(192,132,252,.16),
    inset 0 0 0 1px rgba(255,255,255,.045);
  backdrop-filter:blur(14px);
}

/* Progress bar */
.prog-wrap{
  width:100%;height:14px;
  background:rgba(255,255,255,.08);
  border:1px solid rgba(255,255,255,.12);
  border-radius:3px;overflow:hidden;margin-bottom:24px;
}
.prog-fill{
  height:100%;width:55%;
  background:linear-gradient(90deg,rgba(180,80,255,.5),var(--ghost));
  border-radius:3px;
  animation:prog-anim 3s ease-in-out infinite alternate;
}
@keyframes prog-anim{from{width:38%}to{width:70%}}

/* Lang buttons */
.lang-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.lang-btn{
  font-family:'Press Start 2P';font-size:.58rem;
  padding:18px 12px;border-radius:22px;border:1.5px solid rgba(255,255,255,.16);cursor:pointer;
  letter-spacing:1px;
  transition:transform .18s ease,box-shadow .18s ease,filter .18s ease;
  display:flex;flex-direction:column;align-items:center;gap:8px;
  box-shadow:0 7px 0 rgba(0,0,0,.62),0 18px 28px rgba(0,0,0,.22);
}
.lang-btn:hover{transform:translateY(-4px);filter:brightness(1.06);box-shadow:0 11px 0 rgba(0,0,0,.62),0 0 24px rgba(249,168,212,.25)}
.lang-btn:active{transform:translateY(3px);box-shadow:0 3px 0 rgba(0,0,0,.62)}

/* === MODIFICATION COULEURS BOUTONS === */
.lb-py{background:linear-gradient(135deg,#f4dfb6,#f9a8d4);color:#3b145f}
.lb-java{background:linear-gradient(135deg,#f9a8d4,#c084fc);color:#2e1065}
.lb-cs{background:linear-gradient(135deg,#c4b5fd,#f4dfb6);color:#2e1065}
.lb-c{background:linear-gradient(135deg,#99f6e4,#c4b5fd);color:#1e1b4b}
/* ===================================== */

.lang-btn .licon{font-size:.72rem;background:rgba(255,255,255,.22);border:1px solid rgba(255,255,255,.24);border-radius:10px;padding:7px 9px;min-width:38px}
.lang-btn .lcount{font-size:.4rem;opacity:.65}

/* ===== HUD ===== */
.hud{
  width:100%;max-width:720px;
  display:flex;align-items:center;gap:14px;
  background:linear-gradient(180deg,rgba(255,255,255,.08),rgba(255,255,255,.025)),rgba(10,5,40,.78);
  border:1.5px solid rgba(249,168,212,.28);
  border-radius:20px;padding:14px 18px;margin-bottom:18px;
  backdrop-filter:blur(12px);
  box-shadow:0 18px 40px rgba(0,0,0,.28);
}
.hud-timer{flex:1;display:flex;flex-direction:column;gap:5px}
.hud-time-row{display:flex;justify-content:space-between;font-size:1.1rem;color:var(--muted)}
.timer-track{background:rgba(0,0,0,.5);border:1px solid rgba(180,80,255,.3);border-radius:3px;height:12px;overflow:hidden}
.timer-fill{height:100%;background:linear-gradient(90deg,#7c3aed,#e879f9);border-radius:3px;transition:width 1s linear,background .3s}
.score-pill{
  font-family:'Press Start 2P';font-size:.58rem;
  background:rgba(180,50,255,.2);border:1.5px solid rgba(200,80,255,.4);
  border-radius:8px;padding:6px 11px;color:var(--ghost);white-space:nowrap;
}
.lang-pill{
  font-family:'Press Start 2P';font-size:.48rem;
  background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.14);
  border-radius:6px;padding:4px 9px;color:var(--muted);
}

/* ===== QUESTION CARD - PRO VERSION ===== */
.q-section{width:100%;max-width:720px}
.q-card{
  position:relative;
  background:
    linear-gradient(180deg,rgba(255,255,255,.085),rgba(255,255,255,.025)),
    rgba(17,9,56,.86);
  border:1px solid rgba(249,168,212,.25);
  border-radius:24px;
  padding:22px;
  margin-bottom:24px;
  backdrop-filter:blur(14px);
  box-shadow:
    0 20px 50px rgba(0,0,0,.32),
    0 0 26px rgba(192,132,252,.10),
    inset 0 0 0 1px rgba(255,255,255,.04);
}
.q-card::before{
  content:'';
  position:absolute;
  inset:0;
  border-radius:24px;
  pointer-events:none;
  background:linear-gradient(90deg,rgba(249,168,212,.32),transparent 35%,rgba(196,181,253,.18));
  opacity:.22;
}
.q-head{display:flex;align-items:flex-start;gap:14px;margin-bottom:18px;position:relative;z-index:1}
.q-num{
  font-family:'Press Start 2P';font-size:.56rem;
  background:linear-gradient(135deg,var(--soft-beige),var(--soft-pink),var(--soft-purple));
  color:#2e1065;border-radius:12px;padding:9px 10px;flex-shrink:0;margin-top:2px;
  box-shadow:0 0 18px rgba(249,168,212,.28);
}
.q-text{font-size:1.36rem;line-height:1.48;color:#fff5ff;text-shadow:0 1px 0 rgba(0,0,0,.3)}
.opts{display:grid;grid-template-columns:1fr 1fr;gap:14px;position:relative;z-index:1}
@media(max-width:620px){.opts{grid-template-columns:1fr}.q-text{font-size:1.14rem}.q-card{padding:18px;border-radius:20px}.q-card::before{border-radius:20px}}
.opt-radio{display:none}
.opt-label{
  min-height:56px;
  display:flex;align-items:center;gap:12px;
  background:rgba(255,255,255,.065);
  border:1px solid rgba(255,255,255,.13);
  border-radius:17px;padding:13px 15px;
  cursor:pointer;font-size:1.17rem;color:#eadcff;
  transition:transform .18s ease,background .18s ease,border-color .18s ease,box-shadow .18s ease;
  box-shadow:0 9px 22px rgba(0,0,0,.18);
}
.opt-label:hover{
  transform:translateY(-2px);
  background:rgba(249,168,212,.15);
  border-color:rgba(249,168,212,.48);
  box-shadow:0 15px 30px rgba(0,0,0,.25),0 0 20px rgba(249,168,212,.12);
}
.opt-radio:checked+.opt-label{
  background:linear-gradient(135deg,rgba(249,168,212,.28),rgba(192,132,252,.23));
  border-color:#f9a8d4;color:#fff;
  box-shadow:0 0 24px rgba(249,168,212,.22),0 14px 30px rgba(0,0,0,.25);
}
.opt-dot{
  width:16px;height:16px;border-radius:50%;
  border:2px solid rgba(249,168,212,.68);
  flex-shrink:0;background:transparent;transition:.18s;
}
.opt-radio:checked+.opt-label .opt-dot{background:#f9a8d4;border-color:#f9a8d4;box-shadow:0 0 13px #f9a8d4}
.feedback{
  display:none;margin-top:14px;padding:12px 14px;
  font-family:'Press Start 2P';font-size:.52rem;border-radius:13px;
  position:relative;z-index:1;
}
.feedback.correct{display:block;background:rgba(74,222,128,.13);border:1px solid rgba(74,222,128,.38);color:var(--green)}
.feedback.wrong{display:block;background:rgba(248,113,113,.13);border:1px solid rgba(248,113,113,.38);color:var(--red)}

/* ===== CONTROLS ===== */
.action-row{
  display:flex;justify-content:center;gap:16px;
  padding:26px 0 10px;
  border-top:1px solid rgba(249,168,212,.18);margin-top:8px;
}
.arc-btn{
  font-family:'Press Start 2P';font-size:.6rem;
  padding:13px 24px;border-radius:24px;border:1.5px solid rgba(255,255,255,.16);cursor:pointer;
  letter-spacing:1px;box-shadow:0 6px 0 rgba(0,0,0,.62),0 16px 26px rgba(0,0,0,.22);
  transition:transform .14s ease,box-shadow .14s ease,filter .14s ease;
}
.arc-btn:hover{transform:translateY(-3px);filter:brightness(1.05);box-shadow:0 9px 0 rgba(0,0,0,.62),0 0 20px rgba(249,168,212,.16)}
.arc-btn:active{transform:translateY(3px);box-shadow:0 3px 0 rgba(0,0,0,.62)}
.arc-play{background:linear-gradient(135deg,var(--soft-beige),var(--soft-pink));color:#4c1d95}
.arc-exit{background:rgba(255,255,255,.075);color:#eee0ff;border:1.5px solid rgba(249,168,212,.25);box-shadow:0 6px 0 rgba(0,0,0,.55)}
.arc-exit:hover{background:rgba(249,168,212,.15)}

/* ===== MODAL ===== */
.modal-wrap{display:none;position:fixed;inset:0;background:rgba(5,3,30,.9);z-index:999;align-items:center;justify-content:center}
.modal-wrap.open{display:flex}
.modal-box{
  background:rgba(12,7,50,.96);
  border:2px solid rgba(200,80,255,.45);
  border-radius:16px;padding:36px 32px;text-align:center;
  max-width:440px;width:90%;
  box-shadow:0 0 60px rgba(180,50,255,.35),inset 0 0 30px rgba(100,20,200,.1);
}
.modal-ghosts{display:flex;justify-content:center;gap:16px;margin-bottom:10px}
.modal-title{font-family:'Press Start 2P';font-size:1.25rem;color:var(--ghost);margin-bottom:8px;text-shadow:0 0 16px var(--neon-purple)}
.modal-score{font-size:3.2rem;color:#fff;margin:10px 0;text-shadow:0 0 20px rgba(220,100,255,.5)}
.modal-prog{width:100%;height:12px;background:rgba(255,255,255,.08);border-radius:3px;overflow:hidden;margin-bottom:16px}
.modal-prog-fill{height:100%;background:linear-gradient(90deg,#7c3aed,#e879f9);border-radius:3px;transition:width 1.3s ease}
.modal-rank{font-family:'Press Start 2P';font-size:.65rem;border:1.5px solid var(--neon-purple);border-radius:28px;display:inline-block;padding:5px 16px;margin-bottom:22px;color:var(--ghost)}
.modal-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}

/* SCROLL */
.quiz-scroll{width:100%;max-width:720px;max-height:72vh;overflow-y:auto;padding:10px 10px 22px}
.quiz-scroll::-webkit-scrollbar{width:4px}
.quiz-scroll::-webkit-scrollbar-track{background:transparent}
.quiz-scroll::-webkit-scrollbar-thumb{background:rgba(200,80,255,.5);border-radius:4px}

/* DECO BAR */
.deco-bar{position:fixed;bottom:10px;left:0;right:0;display:flex;justify-content:space-between;padding:0 18px;pointer-events:none;z-index:5}
.hearts,.diamonds{display:flex;gap:5px;align-items:flex-end}

.blink{animation:blk .75s step-end infinite}
@keyframes blk{50%{opacity:0}}
</style>
</head>
<body>

<!-- BG -->
<div class="sky-bg"></div>

<!-- PIXEL STARS -->
<div class="stars-layer" id="starsLayer"></div>

<!-- PIXEL CLOUDS SVG -->
<div class="cloud-layer" id="cloudLayer"></div>

<!-- GRID FLOOR -->
<div class="grid-floor"><div class="grid-inner"></div></div>

<!-- DECO BAR -->
<div class="deco-bar">
  <div class="hearts" id="heartsBar"></div>
  <div class="diamonds" id="diamondsBar"></div>
</div>

<!-- SCENE -->
<div class="scene">

  <!-- MENU -->
  <div id="menuView" class="view on">
    <div class="ghosts-row" id="ghostsMenu"></div>
    <div class="main-title">QUIZ</div>
    <div class="main-sub">✦  400 QUESTIONS ✦</div>
    <div class="glass-card">
      <div class="prog-wrap"><div class="prog-fill"></div></div>
      <div class="lang-grid">
        <button class="lang-btn lb-py" onclick="startGame('python')"><span class="licon">PY</span>PYTHON<span class="lcount">100 QUESTIONS</span></button>
        <button class="lang-btn lb-java" onclick="startGame('java')"><span class="licon">JV</span>JAVA<span class="lcount">100 QUESTIONS</span></button>
        <button class="lang-btn lb-cs" onclick="startGame('csharp')"><span class="licon">C#</span>C#<span class="lcount">100 QUESTIONS</span></button>
        <button class="lang-btn lb-c" onclick="startGame('clang')"><span class="licon">C</span>C LANG<span class="lcount">100 QUESTIONS</span></button>
      </div>
    </div>
  </div>

  <!-- QUIZ -->
  <div id="quizView" class="view">
    <div class="hud">
      <div class="hud-timer">
        <div class="hud-time-row"><span>✦ TIME LEFT</span><span id="timerText">0:00</span></div>
        <div class="timer-track"><div class="timer-fill" id="timerFill"></div></div>
      </div>
      <div class="lang-pill" id="langPill">—</div>
      <div class="score-pill" id="scoreEl">0 pts</div>
    </div>
    <div class="quiz-scroll q-section">
      <form id="quizForm"></form>
      <div class="action-row">
        <button class="arc-btn arc-exit" onclick="showMenu()">MENU</button>
        <button class="arc-btn arc-exit" onclick="window.location.href='conseil.php'">CONSEILS</button>
        <button class="arc-btn arc-play" onclick="submitQuiz()">FINISH</button>
      </div>
    </div>
  </div>

</div>

<!-- MODAL -->
<div class="modal-wrap" id="resultModal">
  <div class="modal-box">
    <div class="modal-ghosts" id="modalGhosts"></div>
    <div class="modal-title" id="modalTitle">GAME OVER</div>
    <div class="modal-score" id="modalScore">0/0</div>
    <div class="modal-prog"><div class="modal-prog-fill" id="modalProgFill" style="width:0%"></div></div>
    <div class="modal-rank" id="modalRank">NOVICE</div>
    <div class="modal-btns">
      <button class="arc-btn arc-exit" onclick="closeModal()">MENU</button>
      <button class="arc-btn arc-exit" onclick="window.location.href='conseil.php'">CONSEILS</button>
      <button class="arc-btn arc-play" onclick="replayGame()">RESPAWN</button>
    </div>
  </div>
</div>

<script>
/* ===== PIXEL ART GENERATION ===== */

function makeSVG(w,h,content){
  return `<svg width="${w}" height="${h}" viewBox="0 0 ${w} ${h}" xmlns="http://www.w3.org/2000/svg" style="display:block">${content}</svg>`;
}

/* Ghost SVG */
function ghostSVG(color,size=36){
  const s=size/16;
  return `<svg width="${size}" height="${Math.round(size*1.1)}" viewBox="0 0 16 18" xmlns="http://www.w3.org/2000/svg">
    <rect x="4" y="0" width="8" height="2" fill="${color}"/>
    <rect x="2" y="2" width="12" height="2" fill="${color}"/>
    <rect x="0" y="4" width="16" height="10" fill="${color}"/>
    <rect x="0" y="14" width="3" height="4" fill="${color}"/>
    <rect x="4" y="14" width="2" height="3" fill="${color}"/>
    <rect x="7" y="14" width="2" height="4" fill="${color}"/>
    <rect x="10" y="14" width="2" height="3" fill="${color}"/>
    <rect x="13" y="14" width="3" height="4" fill="${color}"/>
    <rect x="3" y="6" width="4" height="4" fill="#fff"/>
    <rect x="9" y="6" width="4" height="4" fill="#fff"/>
    <rect x="4" y="7" width="2" height="2" fill="#1e1b4b"/>
    <rect x="10" y="7" width="2" height="2" fill="#1e1b4b"/>
  </svg>`;
}

/* Build ghost rows */
const ghostColors=['#e879f9','#f0abfc','#d946ef'];
function buildGhosts(containerId){
  const c=document.getElementById(containerId);
  if(!c)return;
  c.innerHTML='';
  ghostColors.forEach((col,i)=>{
    const wrap=document.createElement('div');
    wrap.className='ghost';
    wrap.style.animationDelay=(i*.5)+'s';
    wrap.innerHTML=ghostSVG(col,34);
    c.appendChild(wrap);
  });
}
buildGhosts('ghostsMenu');
buildGhosts('modalGhosts');

/* ===== PIXEL CLOUDS ===== */
function pixelCloud(x,y,scale,opacity,colors){
  const [c1,c2,c3]=colors;
  // pixel cloud shape as rect blocks
  const blocks=[
    {x:20,y:24,w:60,h:20,c:c1},
    {x:10,y:16,w:30,h:16,c:c2},
    {x:30,y:8,w:40,h:16,c:c2},
    {x:60,y:12,w:24,h:16,c:c2},
    {x:4,y:20,w:16,h:12,c:c3},
    {x:76,y:18,w:14,h:14,c:c3},
    // spiral texture dots
    {x:34,y:12,w:4,h:4,c:c3},{x:42,y:10,w:4,h:4,c:c3},
    {x:38,y:16,w:4,h:4,c:c3},{x:46,y:14,w:4,h:4,c:c3},
    {x:52,y:10,w:4,h:4,c:c3},{x:56,y:16,w:4,h:4,c:c3},
    {x:14,y:20,w:4,h:4,c:c3},{x:22,y:18,w:4,h:4,c:c3},
  ];
  const rects=blocks.map(b=>`<rect x="${b.x}" y="${b.y}" width="${b.w}" height="${b.h}" fill="${b.c}"/>`).join('');
  const svg=`<svg viewBox="0 0 96 48" xmlns="http://www.w3.org/2000/svg">${rects}</svg>`;
  const el=document.createElement('div');
  el.className='px-cloud';
  el.style.cssText=`left:${x}%;top:${y}%;width:${Math.round(120*scale)}px;opacity:${opacity}`;
  el.innerHTML=svg;
  return el;
}

const cloudCfg=[
  // x%, y%, scale, opacity, [dark, mid, light]
  [5, 6, 1.4, .9, ['#2d1a5e','#3d2070','#4a2d8a']],
  [35, 5, 1.8, .85, ['#3a1e70','#4a2d8a','#5c3a9e']],
  [65, 2, 1.2, .8, ['#2d1a5e','#3d2070','#4a2d8a']],
  [80, 8, 1.0, .7, ['#251560','#342070','#4030a0']],
  [0,  22, 1.1, .75, ['#2d1a5e','#3d2070','#4a2d8a']],
  [20, 30, 1.3, .8, ['#3a1e70','#4a2d8a','#5c3a9e']],
  [60, 28, 1.0, .7, ['#2d1a5e','#3d2070','#4a2d8a']],
  [85, 25, 1.6, .85, ['#3a1e70','#4a2d8a','#5c3a9e']],
  [10, 45, 1.2, .65, ['#251560','#342070','#4030a0']],
  [45, 50, 1.0, .6, ['#2d1a5e','#3d2070','#4a2d8a']],
  [75, 48, 1.3, .7, ['#3a1e70','#4a2d8a','#5c3a9e']],
];
const cl=document.getElementById('cloudLayer');
cloudCfg.forEach(([x,y,s,o,cols])=>cl.appendChild(pixelCloud(x,y,s,o,cols)));

/* ===== PIXEL STARS ===== */
const starData=[
  // [x%, y%, type, twClass]
  // pink cross stars
  [8,4,'cross','tw1'],[22,9,'cross','tw3'],[38,3,'cross','tw2'],
  [55,7,'cross','tw4'],[72,2,'cross','tw1'],[88,10,'cross','tw5'],
  [15,18,'cross','tw3'],[48,12,'cross','tw2'],[82,15,'cross','tw6'],
  [30,22,'cross','tw4'],[65,20,'cross','tw1'],[5,35,'cross','tw2'],
  [92,32,'cross','tw3'],[43,40,'cross','tw5'],[78,38,'cross','tw4'],
  [12,50,'cross','tw6'],[58,55,'cross','tw1'],[25,58,'cross','tw3'],
  [70,60,'cross','tw2'],[90,52,'cross','tw5'],[35,65,'cross','tw4'],
  [10,70,'cross','tw6'],[60,68,'cross','tw1'],[85,72,'cross','tw3'],
  // small pink
  [18,6,'sm','tw4'],[44,8,'sm','tw1'],[67,5,'sm','tw2'],
  [33,16,'sm','tw5'],[75,18,'sm','tw3'],[50,25,'sm','tw6'],
  [20,38,'sm','tw2'],[80,42,'sm','tw4'],[40,55,'sm','tw1'],
  [95,60,'sm','tw5'],[15,62,'sm','tw3'],[68,75,'sm','tw6'],
  // gold cross stars
  [3,12,'gold','tw2'],[27,7,'gold','tw4'],[60,14,'gold','tw1'],
  [85,6,'gold','tw5'],[42,30,'gold','tw3'],[10,42,'gold','tw6'],
  [73,45,'gold','tw2'],[28,55,'gold','tw4'],[52,70,'gold','tw1'],
  [90,68,'gold','tw3'],[18,80,'gold','tw5'],[65,82,'gold','tw6'],
  // dots
  [6,8,'dot',''],[14,3,'dot',''],[26,15,'dot',''],[37,11,'dot',''],
  [49,4,'dot',''],[61,9,'dot',''],[76,6,'dot',''],[91,13,'dot',''],
  [4,28,'dot',''],[20,25,'dot',''],[36,32,'dot',''],[53,27,'dot',''],
  [68,33,'dot',''],[84,29,'dot',''],[7,48,'dot',''],[31,52,'dot',''],
  [57,44,'dot',''],[79,50,'dot',''],[95,46,'dot',''],[22,65,'dot',''],
  [47,72,'dot',''],[63,78,'dot',''],[88,80,'dot',''],[11,88,'dot',''],
  [38,85,'dot',''],[66,90,'dot',''],[82,88,'dot',''],[2,20,'dot2',''],
  [45,18,'dot2',''],[70,22,'dot2',''],[16,72,'dot2',''],[55,80,'dot2',''],
];

const sl=document.getElementById('starsLayer');
starData.forEach(([x,y,type,twClass])=>{
  const el=document.createElement('div');
  if(type==='cross')el.className='star-cross '+(twClass||'');
  else if(type==='sm')el.className='star-sm '+(twClass||'');
  else if(type==='gold')el.className='star-gold-c '+(twClass||'');
  else if(type==='dot')el.className='star-dot';
  else el.className='star-dot2';
  el.style.cssText=`left:${x}%;top:${y}%`;
  sl.appendChild(el);
});

/* ===== HEARTS & DIAMONDS ===== */
function heartSVG(filled){
  const c=filled?'#ef4444':'rgba(239,68,68,.3)';
  return `<svg width="22" height="20" viewBox="0 0 10 9" xmlns="http://www.w3.org/2000/svg"><rect x="1" y="1" width="3" height="2" fill="${c}"/><rect x="6" y="1" width="3" height="2" fill="${c}"/><rect x="0" y="2" width="5" height="3" fill="${c}"/><rect x="5" y="2" width="5" height="3" fill="${c}"/><rect x="1" y="5" width="8" height="2" fill="${c}"/><rect x="2" y="7" width="6" height="1" fill="${c}"/><rect x="3" y="8" width="4" height="1" fill="${c}"/></svg>`;
}
function diamondSVG(shade){
  const cols=['#a5f3fc','#67e8f9','#22d3ee','rgba(165,243,252,.4)'];
  const c=cols[shade]||cols[0];
  return `<svg width="18" height="20" viewBox="0 0 10 12" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="0" width="4" height="2" fill="${c}"/><rect x="1" y="2" width="8" height="2" fill="${c}"/><rect x="0" y="4" width="10" height="2" fill="${c}"/><rect x="1" y="6" width="8" height="2" fill="${c}"/><rect x="2" y="8" width="6" height="2" fill="${c}"/><rect x="3" y="10" width="4" height="1" fill="${c}"/><rect x="4" y="11" width="2" height="1" fill="${c}"/></svg>`;
}
const hb=document.getElementById('heartsBar');
[true,true,true,false].forEach(f=>{ const d=document.createElement('div');d.innerHTML=heartSVG(f);hb.appendChild(d); });
const db=document.getElementById('diamondsBar');
[0,1,2,3].forEach(s=>{ const d=document.createElement('div');d.innerHTML=diamondSVG(s);db.appendChild(d); });

/* ===== QUIZ DATA ===== */
const bq={
  python:[
    {q:"Quel mot-clé définit une fonction ?",o:["def","function","func","lambda"],a:"def"},
    {q:"Comment créer une liste ?",o:["[]","()","{}","<>"],a:"[]"},
    {q:"Comment commenter une ligne ?",o:["//","#","--","/* */"],a:"#"},
    {q:"Fonction pour afficher du texte ?",o:["echo","print","write","show"],a:"print"},
    {q:"Type de données immuable ?",o:["list","dict","tuple","set"],a:"tuple"},
    {q:"Import d'un module ?",o:["include","import","using","require"],a:"import"},
    {q:"Premier index d'une liste ?",o:["0","1","-1","2"],a:"0"},
    {q:"Créer un dictionnaire ?",o:["{}","[]","()","<>"],a:"{}"},
    {q:"Librairie calcul scientifique ?",o:["pandas","numpy","math","sys"],a:"numpy"},
    {q:"Méthode pour lire un fichier ?",o:["read()","get()","open()","load()"],a:"read()"},
    {q:"Comment définir une classe ?",o:["class","classy","struct","object"],a:"class"},
    {q:"Que signifie 'self' ?",o:["L'instance actuelle","Le parent","La classe statique","Un type"],a:"L'instance actuelle"},
    {q:"Boucle 'pour chaque' ?",o:["for x in y","foreach x in y","loop x in y","while x in y"],a:"for x in y"},
    {q:"Opérateur de puissance ?",o:["**","^","%","//"],a:"**"},
    {q:"Vérifier le type d'une variable ?",o:["type()","typeof()","class()","check()"],a:"type()"},
    {q:"Gestionnaire de contexte (fichier) ?",o:["with","open","use","using"],a:"with"},
    {q:"Créer une liste vide ?",o:["list()","[]","void","empty"],a:"list()"},
    {q:"Que retourne map() ?",o:["Un itérateur","Une liste","Un tuple","Un set"],a:"Un itérateur"},
    {q:"Comment arrêter une boucle ?",o:["break","stop","exit","return"],a:"break"},
    {q:"Extension des fichiers Python ?",o:[".py",".python",".pi",".code"],a:".py"}
  ],
  java:[
    {q:"Point d'entrée d'un programme ?",o:["main","start","init","run"],a:"main"},
    {q:"Type pour une chaîne ?",o:["String","text","str","char[]"],a:"String"},
    {q:"Afficher à la console ?",o:["System.out.println","print","Console.log","echo"],a:"System.out.println"},
    {q:"Héritage de classe ?",o:["extends","inherits","using","implements"],a:"extends"},
    {q:"Implémenter une interface ?",o:["implements","extends","uses","interface"],a:"implements"},
    {q:"Lire une entrée (classe) ?",o:["Scanner","Input","Reader","Console"],a:"Scanner"},
    {q:"Type booléen ?",o:["boolean","bool","bit","flag"],a:"boolean"},
    {q:"Java est ?",o:["Compilé et Interprété","Juste interprété","Juste compilé","Assembleur"],a:"Compilé et Interprété"},
    {q:"Que signifie JVM ?",o:["Java Virtual Machine","Java Visual Module","Java Variable Memory","Rien"],a:"Java Virtual Machine"},
    {q:"Mot-clé pour constantes ?",o:["final","const","static","readonly"],a:"final"},
    {q:"Type pour nombres décimaux ?",o:["double","float","decimal","num"],a:"double"},
    {q:"Comment créer un objet ?",o:["new","create","object","make"],a:"new"},
    {q:"Structure de données indexée ?",o:["ArrayList","LinkedList","HashMap","TreeSet"],a:"ArrayList"},
    {q:"Opérateur d'égalité objet ?",o:["equals()","==","===","is()"],a:"equals()"},
    {q:"Classe parente de toutes ?",o:["Object","Class","Base","Main"],a:"Object"},
    {q:"Gestion des erreurs ?",o:["try-catch","try-catch-finally","throw-error","error-catch"],a:"try-catch"},
    {q:"Méthode statique ?",o:["static","shared","common","global"],a:"static"},
    {q:"Package par défaut ?",o:["java.lang","java.util","java.io","java.base"],a:"java.lang"},
    {q:"Interface fonctionnelle ?",o:["Runnable","Action","Task","Process"],a:"Runnable"},
    {q:"Extension des fichiers ?",o:[".java",".js",".class",".jv"],a:".java"}
  ],
  csharp:[
    {q:"Mot-clé pour une classe ?",o:["class","object","define","struct"],a:"class"},
    {q:"Type pour un nombre entier ?",o:["int","integer","num","float"],a:"int"},
    {q:"Afficher à la console ?",o:["Console.WriteLine","print","echo","show"],a:"Console.WriteLine"},
    {q:"Commentaire sur une ligne ?",o:["//","#","--","/* */"],a:"//"},
    {q:"Lire une entrée utilisateur ?",o:["Console.ReadLine","input","scan","read"],a:"Console.ReadLine"},
    {q:"Héritage de classe ?",o:["extends",":","using","implements"],a:":"},
    {q:"Type booléen ?",o:["bool","boolean","bit","flag"],a:"bool"},
    {q:"C# tourne sur ?",o:[".NET","JVM","Python VM","Browser"],a:".NET"},
    {q:"Propriété automatique ?",o:["{ get; set; }","public var","prop","property"],a:"{ get; set; }"},
    {q:"Interface fonctionnelle ?",o:["delegate","action","func","lambda"],a:"delegate"},
    {q:"Namespace racine ?",o:["System","Root","Base","Main"],a:"System"},
    {q:"Tableau dynamique ?",o:["List","ArrayList","Vector","Array"],a:"List"},
    {q:"Méthode asynchrone ?",o:["async","parallel","task","thread"],a:"async"},
    {q:"String est mutable ?",o:["Non","Oui","Parfois","Jamais"],a:"Non"},
    {q:"Linq sert à ?",o:["Requêtes sur données","Connexion SQL","Graphisme","Réseau"],a:"Requêtes sur données"},
    {q:"Structure vs Classe ?",o:["Value type vs Ref type","Private vs Public","Static vs Dynamic","None"],a:"Value type vs Ref type"},
    {q:"Null-coalescing ?",o:["??","||","&&","?:"],a:"??"},
    {q:'String interpolation ?',o:['$"{var}"',"&var","%var%","#var"],a:'$"{var}"'},
    {q:"Constante ?",o:["const","readonly","final","fixed"],a:"const"},
    {q:"Extension des fichiers ?",o:[".cs",".csharp",".net",".c#"],a:".cs"}
  ],
  clang:[
    {q:"Fonction d'affichage ?",o:["printf","print","echo","cout"],a:"printf"},
    {q:"Inclusion de fichier ?",o:["#include","import","using","require"],a:"#include"},
    {q:"Type pointeur ?",o:["int*","pointer","ref","&int"],a:"int*"},
    {q:"Allocation dynamique ?",o:["malloc","new","alloc","create"],a:"malloc"},
    {q:"Libérer la mémoire ?",o:["free","delete","remove","clear"],a:"free"},
    {q:"Opérateur adresse ?",o:["&","*","@","#"],a:"&"},
    {q:"Opérateur déréférencement ?",o:["*","&","@","->"],a:"*"},
    {q:"Structure ?",o:["struct","class","object","record"],a:"struct"},
    {q:"Boucle conditionnelle ?",o:["while","loop","repeat","for"],a:"while"},
    {q:"Entier court ?",o:["short","int16","small","byte"],a:"short"},
    {q:"Vrai type de chaîne ?",o:["char[]","String","string","text"],a:"char[]"},
    {q:"Préprocesseur ?",o:["#define","define","let","const"],a:"#define"},
    {q:"Passage par défaut ?",o:["Par valeur","Par référence","Par adresse","Par copie"],a:"Par valeur"},
    {q:"Accès membre pointeur ?",o:["->",".","::","=>"],a:"->"},
    {q:"Fichier header ?",o:[".h",".head",".c",".inc"],a:".h"},
    {q:"Bibliothèque standard ?",o:["stdio.h","stdlib","standard","io"],a:"stdio.h"},
    {q:"Taille de int ?",o:["sizeof(int)","len(int)","size(int)","length(int)"],a:"sizeof(int)"},
    {q:"Null en C ?",o:["NULL","nil","null","undefined"],a:"NULL"},
    {q:"Renvoyer une valeur ?",o:["return","exit","yield","give"],a:"return"},
    {q:"Pointeur sur fonction ?",o:["(*ptr)()","ptr->()","&ptr","func ptr"],a:"(*ptr)()"}
  ]
};

const LABELS={python:'PYTHON',java:'JAVA',csharp:'C#',clang:'C LANG'};
const TARGET=100,TPQ=45;
let lang='',questions=[],score=0,timeLeft=0,timer=null,active=false,lastLang='';

function shuffle(a){for(let i=a.length-1;i>0;i--){const j=Math.floor(Math.random()*(i+1));[a[i],a[j]]=[a[j],a[i]]}}

function genQ(l){
  const base=bq[l];let set=[...base];
  while(set.length<TARGET){const s=base[Math.floor(Math.random()*base.length)];set.push({q:s.q,o:[...s.o],a:s.a})}
  shuffle(set);return set.slice(0,TARGET);
}

function showMenu(){
  if(active&&!confirm('Quitter la partie en cours ?'))return;
  clearInterval(timer);active=false;
  document.getElementById('menuView').classList.add('on');
  document.getElementById('quizView').classList.remove('on');
  document.getElementById('resultModal').classList.remove('open');
}

function startGame(l){
  lang=l;lastLang=l;questions=genQ(l);
  document.getElementById('langPill').textContent=LABELS[l];
  document.getElementById('menuView').classList.remove('on');
  document.getElementById('quizView').classList.add('on');
  init();
}

function replayGame(){document.getElementById('resultModal').classList.remove('open');startGame(lastLang)}
function closeModal(){document.getElementById('resultModal').classList.remove('open');showMenu()}

function init(){
  score=0;active=true;
  document.getElementById('scoreEl').textContent='0 pts';
  document.getElementById('quizForm').innerHTML='';
  clearInterval(timer);
  render();
  timeLeft=questions.length*TPQ;
  startTimer();
  document.querySelector('.quiz-scroll').scrollTop=0;
}

function render(){
  const form=document.getElementById('quizForm');
  const frag=document.createDocumentFragment();
  questions.forEach((q,i)=>{
    const card=document.createElement('div');card.className='q-card';
    const head=document.createElement('div');head.className='q-head';
    head.innerHTML=`<span class="q-num">${i+1}</span><span class="q-text">${q.q}</span>`;
    card.appendChild(head);
    const opts=document.createElement('div');opts.className='opts';
    q.o.forEach(opt=>{
      const wrap=document.createElement('div');
      const inp=document.createElement('input');
      inp.type='radio';inp.name=`q${i}`;inp.value=opt;inp.className='opt-radio';
      inp.id=`q${i}_${opt.replace(/[^a-z0-9]/gi,'_')}`;
      const lbl=document.createElement('label');lbl.className='opt-label';lbl.htmlFor=inp.id;
      lbl.innerHTML=`<span class="opt-dot"></span>${opt}`;
      wrap.appendChild(inp);wrap.appendChild(lbl);opts.appendChild(wrap);
    });
    card.appendChild(opts);
    const fb=document.createElement('div');fb.id=`fb-${i}`;fb.className='feedback';
    card.appendChild(fb);frag.appendChild(card);
  });
  form.appendChild(frag);
}

function startTimer(){
  updateTimer();
  timer=setInterval(()=>{if(!active)return;timeLeft--;updateTimer();if(timeLeft<=0)endGame(true)},1000);
}

function updateTimer(){
  const total=questions.length*TPQ;
  const m=Math.floor(timeLeft/60),s=timeLeft%60;
  document.getElementById('timerText').textContent=`${m}:${s<10?'0':''}${s}`;
  const pct=(timeLeft/total)*100;
  const fill=document.getElementById('timerFill');
  fill.style.width=pct+'%';
  if(pct>50)fill.style.background='linear-gradient(90deg,#7c3aed,#e879f9)';
  else if(pct>20)fill.style.background='linear-gradient(90deg,#b45309,#fbbf24)';
  else{fill.style.background='linear-gradient(90deg,#991b1b,#f87171)';fill.classList.add('blink')}
}

function submitQuiz(){if(!confirm('Valider le quiz maintenant ?'))return;endGame(false)}

function endGame(to){
  active=false;clearInterval(timer);
  document.querySelectorAll('#quizForm input').forEach(i=>i.disabled=true);
  score=0;
  questions.forEach((q,i)=>{
    const inputs=document.getElementsByName(`q${i}`);
    let sel=null;inputs.forEach(inp=>{if(inp.checked)sel=inp.value});
    const fb=document.getElementById(`fb-${i}`);
    if(sel===q.a){score++;fb.textContent='✦ CORRECT !';fb.className='feedback correct'}
    else{fb.textContent=`✖ FAUX — Réponse : ${q.a}`;fb.className='feedback wrong'}
  });
  document.getElementById('scoreEl').textContent=score+' pts';
  setTimeout(showModal,900);
}

function showModal(){
  const pct=score/questions.length;
  let rank='NOVICE',color='#a78bfa';
  if(pct>=1){rank='LEGENDARY DEV';color='#fde68a'}
  else if(pct>=.8){rank='EXPERT';color='#4ade80'}
  else if(pct>=.5){rank='APPRENTI';color='#e879f9'}
  document.getElementById('modalTitle').textContent=`${LABELS[lang]} — GAME OVER`;
  document.getElementById('modalScore').textContent=`${score} / ${questions.length}`;
  const r=document.getElementById('modalRank');
  r.textContent=rank;r.style.color=color;r.style.borderColor=color;
  setTimeout(()=>{document.getElementById('modalProgFill').style.width=Math.round(pct*100)+'%'},120);
  document.getElementById('resultModal').classList.add('open');
}
</script>
</body>
</html>