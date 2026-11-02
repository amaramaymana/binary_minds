<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_SESSION['nom'])) {
    $nomUtilisateur = $_SESSION['nom'];
} elseif (isset($_SESSION['username'])) {
    $nomUtilisateur = $_SESSION['username'];
} elseif (isset($_SESSION['email'])) {
    $nomUtilisateur = $_SESSION['email'];
} else {
    $nomUtilisateur = 'Utilisateur';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Binary Minds - Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Inter:wght@400;500;600;700;800&display=swap');

:root{
  --bg:#050716;
  --bg2:#0b1028;
  --panel:#0f1634;
  --panel2:#151d44;
  --glass:rgba(12,18,45,.72);
  --txt:#f7f8ff;
  --muted:#b8c2e6;
  --muted2:#7f8bb6;
  --line:#3e4d78;
  --blue:#29b6ff;
  --cyan:#62e6ff;
  --mauve:#9b5cff;
  --pink:#ff7bd5;
  --yellow:#ffd43b;
  --green:#7cffc4;
  --max:1240px;
}
*{margin:0;padding:0;box-sizing:border-box}
html{background:var(--bg);scroll-behavior:smooth}
body{
  min-height:100vh;
  color:var(--txt);
  font-family:'Inter',system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
  background:var(--bg);
  overflow-x:hidden;
  -webkit-font-smoothing:antialiased;
}
a{text-decoration:none;color:inherit}.pixel-font{font-family:'Press Start 2P',cursive;letter-spacing:.8px;line-height:1.6}
::selection{background:rgba(98,230,255,.25);color:white}

.app{
  position:relative;
  min-height:100vh;
  overflow:hidden;
  isolation:isolate;
  background:
    radial-gradient(circle at 8% 16%, rgba(155,92,255,.38), transparent 24%),
    radial-gradient(circle at 88% 13%, rgba(41,182,255,.31), transparent 25%),
    radial-gradient(circle at 74% 86%, rgba(255,123,213,.18), transparent 24%),
    linear-gradient(180deg,#060819 0%,#0a102a 48%,#120b2a 100%);
}
.app::before{
  content:"";position:absolute;inset:0;z-index:0;pointer-events:none;opacity:.22;
  background-image:
    linear-gradient(rgba(255,255,255,.065) 1px,transparent 1px),
    linear-gradient(90deg,rgba(255,255,255,.065) 1px,transparent 1px);
  background-size:34px 34px;
  mask-image:linear-gradient(to bottom,transparent 0%,#000 14%,#000 84%,transparent 100%);
}
.app::after{
  content:"";position:absolute;left:0;right:0;bottom:0;height:210px;z-index:0;pointer-events:none;
  background:
    linear-gradient(135deg, transparent 0 18px, rgba(41,182,255,.15) 19px 26px, transparent 27px) 0 72px/72px 72px repeat-x,
    linear-gradient(225deg, transparent 0 18px, rgba(155,92,255,.19) 19px 26px, transparent 27px) 0 95px/72px 72px repeat-x,
    linear-gradient(180deg, transparent, rgba(3,4,16,.95));
}

.orb{position:absolute;border-radius:50%;filter:blur(15px);pointer-events:none;z-index:0;animation:floaty 9s ease-in-out infinite}
.orb.one{width:250px;height:250px;left:-45px;top:92px;background:radial-gradient(circle,rgba(155,92,255,.48),transparent 68%)}
.orb.two{width:310px;height:310px;right:-70px;top:145px;background:radial-gradient(circle,rgba(41,182,255,.26),transparent 68%);animation-delay:1s}
.orb.three{width:260px;height:260px;right:18%;bottom:0;background:radial-gradient(circle,rgba(255,123,213,.20),transparent 66%);animation-delay:.4s}

.stars span{position:absolute;z-index:1;background:var(--cyan);box-shadow:0 0 16px var(--cyan);border-radius:2px;animation:twinkle 3s infinite ease-in-out}
.stars span:nth-child(1){width:6px;height:6px;left:8%;top:18%}.stars span:nth-child(2){width:10px;height:10px;left:26%;top:31%;animation-delay:.5s;background:var(--mauve);box-shadow:0 0 16px var(--mauve)}
.stars span:nth-child(3){width:5px;height:5px;right:16%;top:24%;animation-delay:.3s}.stars span:nth-child(4){width:13px;height:4px;right:31%;top:38%;animation-delay:.8s;background:var(--pink);box-shadow:0 0 16px var(--pink)}
.stars span:nth-child(5){width:7px;height:7px;left:18%;bottom:21%;background:var(--pink);box-shadow:0 0 16px var(--pink)}.stars span:nth-child(6){width:4px;height:4px;right:8%;bottom:30%;background:var(--mauve);box-shadow:0 0 16px var(--mauve);animation-delay:.6s}

.topbar{
  position:sticky;top:14px;z-index:50;width:min(var(--max),calc(100% - 38px));height:74px;margin:14px auto 0;
  display:flex;align-items:center;justify-content:space-between;gap:22px;
  padding:0 22px 0 24px;background:rgba(5,7,22,.76);
  border:1px solid rgba(98,230,255,.24);border-bottom:3px solid rgba(98,230,255,.24);
  box-shadow:0 18px 45px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.08);
  backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);
}
.brand{display:flex;align-items:center;gap:14px;min-width:max-content}.brand h1{font-size:15px;text-shadow:3px 3px 0 #000;white-space:nowrap}
.coin{width:30px;height:30px;border-radius:50%;position:relative;flex:0 0 auto;background:linear-gradient(135deg,#fff179,#ffb703 70%);border:3px solid #8a5b00;box-shadow:0 0 0 3px #2d2000,0 0 24px rgba(255,212,59,.46)}
.coin::after{content:"";position:absolute;inset:6px 10px;border-left:4px solid #8a5b00;border-right:4px solid rgba(255,255,255,.72)}
.menu{display:flex;align-items:center;justify-content:center;gap:6px;flex:1}.menu a{font-family:'Press Start 2P',cursive;font-size:8.5px;letter-spacing:.4px;padding:13px 12px;border:1px solid transparent;color:#eaf1ff;transition:.22s ease;position:relative}.menu a::after{content:"";position:absolute;left:12px;right:12px;bottom:6px;height:2px;background:linear-gradient(90deg,var(--pink),var(--cyan));opacity:0;transform:scaleX(.3);transition:.22s ease}.menu a:hover,.menu a.active{color:var(--cyan);text-shadow:0 0 10px rgba(98,230,255,.6);background:rgba(98,230,255,.06)}.menu a:hover::after,.menu a.active::after{opacity:1;transform:scaleX(1)}

.yellow-btn,.blue-btn,.dark-btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:44px;padding:12px 19px;font-family:'Press Start 2P',cursive;font-size:9px;line-height:1.35;transition:transform .22s ease,filter .22s ease,box-shadow .22s ease;cursor:pointer;white-space:nowrap}
.yellow-btn{color:#1d1600;background:linear-gradient(180deg,#ffe066,var(--yellow));border:3px solid #5f4300;box-shadow:inset -4px -4px 0 #d48a00,inset 4px 4px 0 #fff3a2,0 5px 0 #3f2a00,0 0 22px rgba(255,212,59,.16)}
.blue-btn{background:linear-gradient(180deg,var(--cyan),var(--blue));color:#001426;border:3px solid #0a4f78;box-shadow:inset -4px -4px 0 #0b7bc4,inset 4px 4px 0 #baf6ff,0 5px 0 #06334f,0 0 20px rgba(41,182,255,.14)}
.dark-btn{background:rgba(12,18,45,.72);color:var(--txt);border:2px solid rgba(98,230,255,.28);box-shadow:inset 0 1px 0 rgba(255,255,255,.05),0 0 0 2px rgba(0,0,0,.2)}
.yellow-btn:hover,.blue-btn:hover,.dark-btn:hover{transform:translateY(-4px);filter:brightness(1.06)}

.shell{position:relative;z-index:2;width:min(var(--max),92vw);margin:34px auto 60px}.pixel-card{background:linear-gradient(180deg,rgba(20,29,68,.88),rgba(11,16,40,.88));border:1px solid rgba(164,181,255,.28);box-shadow:0 0 0 4px rgba(0,0,0,.35),10px 10px 0 rgba(0,0,0,.32),inset 0 1px 0 rgba(255,255,255,.075),0 22px 55px rgba(0,0,0,.24);position:relative;overflow:hidden}.pixel-card::before{content:"";position:absolute;left:14px;right:14px;top:0;height:3px;background:linear-gradient(90deg,var(--pink),var(--mauve),var(--cyan));opacity:.95}.pixel-card::after{content:"";position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.06),transparent 32%);pointer-events:none;opacity:.85}
.corner{position:absolute;width:58px;height:58px;z-index:3;pointer-events:none}.corner.tl{left:0;top:0;border-left:3px solid rgba(98,230,255,.34);border-top:3px solid rgba(98,230,255,.34)}.corner.tr{right:0;top:0;border-right:3px solid rgba(98,230,255,.34);border-top:3px solid rgba(98,230,255,.34)}.corner.bl{left:0;bottom:0;border-left:3px solid rgba(98,230,255,.34);border-bottom:3px solid rgba(98,230,255,.34)}.corner.br{right:0;bottom:0;border-right:3px solid rgba(98,230,255,.34);border-bottom:3px solid rgba(98,230,255,.34)}

.hero{padding:42px;min-height:420px}.hero-grid{display:grid;grid-template-columns:1.12fr .88fr;gap:28px;align-items:stretch}.kicker{font-family:'Press Start 2P',cursive;font-size:10px;color:var(--cyan);text-transform:uppercase;letter-spacing:3px;margin-bottom:17px;text-shadow:0 0 14px rgba(98,230,255,.55)}.title{font-family:'Press Start 2P',cursive;font-size:clamp(28px,4.5vw,56px);line-height:1.22;letter-spacing:.6px;text-shadow:5px 5px 0 #050516,0 0 26px rgba(155,92,255,.42)}.title .grad{background:linear-gradient(90deg,var(--pink),var(--mauve),var(--cyan));-webkit-background-clip:text;background-clip:text;color:transparent}.lead{margin-top:22px;color:#d9dfff;font-size:clamp(15px,1.5vw,18px);line-height:1.85;max-width:760px;font-weight:500}.cta-row{display:flex;flex-wrap:wrap;gap:14px;margin-top:26px}.mini-note{margin-top:18px;color:var(--muted);font-size:13px;display:flex;gap:12px;align-items:center;font-weight:600}.mini-note i{color:var(--pink);font-size:19px}

.hud-panel{position:relative;padding:26px;border:1px solid rgba(98,230,255,.18);background:linear-gradient(180deg,rgba(15,22,52,.86),rgba(9,14,35,.92));overflow:hidden}.hud-panel::before{content:"";position:absolute;inset:0;pointer-events:none;background:linear-gradient(180deg,transparent 0%,rgba(98,230,255,.07) 48%,transparent 100%);animation:scan 4.5s linear infinite}.hero-right{display:grid;grid-template-rows:auto auto;gap:18px}.profile-card{display:grid;grid-template-columns:112px 1fr;gap:18px;align-items:center;position:relative;z-index:2}.avatar{width:112px;height:112px;position:relative;display:grid;place-items:center;border:4px solid rgba(98,230,255,.32);background:linear-gradient(135deg,rgba(155,92,255,.18),rgba(41,182,255,.12)),linear-gradient(180deg,#10183a,#0b1028);box-shadow:0 0 24px rgba(98,230,255,.16),inset 0 0 0 4px rgba(255,255,255,.03)}.avatar::before{content:"< >";font-family:'Press Start 2P';font-size:20px;color:var(--cyan);text-shadow:0 0 12px rgba(98,230,255,.7)}.avatar::after{content:"";position:absolute;inset:10px;border:2px dashed rgba(255,123,213,.25)}.profile-lines .tag{font-family:'Press Start 2P';font-size:8px;color:var(--pink)}.profile-lines h3{font-family:'Press Start 2P';font-size:15px;line-height:1.7;margin:8px 0}.profile-lines p{color:var(--muted);line-height:1.75;font-size:14px}
.quick-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:18px;position:relative;z-index:2}.stat-box{padding:14px 12px;border:1px solid rgba(98,230,255,.14);background:rgba(5,7,22,.45);box-shadow:inset 0 1px 0 rgba(255,255,255,.04)}.stat-box .label{font-family:'Press Start 2P';font-size:7px;color:var(--muted2);line-height:1.6;margin-bottom:10px;display:block}.stat-box strong{font-family:'Press Start 2P';font-size:14px;color:#fff;line-height:1.7}.stat-box small{display:block;margin-top:6px;color:var(--cyan);font-weight:700;font-size:11px}

.dashboard-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:26px;margin-top:26px}.stack{display:grid;gap:26px}.panel{padding:26px}.panel-title{font-family:'Press Start 2P';font-size:12px;line-height:1.7;margin-bottom:18px;color:#fff;position:relative;z-index:2}.panel-sub{font-size:13px;color:var(--muted2);margin-top:-4px;margin-bottom:18px;position:relative;z-index:2}
.progress-layout{display:grid;grid-template-columns:240px 1fr;gap:22px;align-items:center}.progress-ring-wrap{display:grid;place-items:center;position:relative;z-index:2}.progress-ring{--val:72;width:205px;height:205px;border-radius:50%;position:relative;background:radial-gradient(circle at center,rgba(5,7,22,1) 58%,transparent 58%),conic-gradient(var(--cyan) calc(var(--val) * 1%),rgba(255,255,255,.06) 0);box-shadow:0 0 0 6px rgba(255,255,255,.03),0 0 26px rgba(98,230,255,.20)}.progress-ring::before{content:"";position:absolute;inset:18px;border-radius:50%;border:2px solid rgba(255,123,213,.20)}.progress-center{position:absolute;text-align:center;padding:0 20px}.progress-center strong{font-family:'Press Start 2P';font-size:30px;line-height:1.2;color:#fff}.progress-center span{display:block;font-family:'Press Start 2P';font-size:8px;color:var(--pink);line-height:1.7;margin-top:8px}.progress-details{display:grid;gap:14px;position:relative;z-index:2}.line-metric{display:grid;gap:8px}.line-head{display:flex;justify-content:space-between;gap:12px;align-items:center}.line-head strong{font-family:'Press Start 2P';font-size:8px;line-height:1.7;color:#fff}.line-head span{font-size:13px;color:var(--muted);font-weight:700}.bar{height:14px;background:#080b1f;border:2px solid #384767;position:relative;overflow:hidden}.bar span{display:block;height:100%;width:0;background:linear-gradient(90deg,var(--pink),var(--mauve),var(--cyan));box-shadow:0 0 18px rgba(98,230,255,.35);transition:width 1.3s cubic-bezier(.22,1,.36,1)}.bar::after{content:"";position:absolute;inset:0;background:repeating-linear-gradient(90deg,rgba(255,255,255,.12) 0 8px,transparent 8px 16px);opacity:.2}
.graph{display:grid;grid-template-columns:repeat(6,1fr);align-items:end;gap:10px;margin-top:10px}.column{display:grid;gap:10px;justify-items:center}.column .stick{width:100%;max-width:42px;height:132px;background:#080b1f;border:2px solid #384767;display:flex;align-items:flex-end;padding:4px}.column .fill{width:100%;height:0;background:linear-gradient(180deg,var(--cyan),var(--mauve),var(--pink));box-shadow:0 0 18px rgba(155,92,255,.22);transition:height 1.2s ease}.column b{font-family:'Press Start 2P';font-size:7px;color:var(--muted2)}

.activity-list{display:grid;gap:14px;position:relative;z-index:2}.activity-item{display:grid;grid-template-columns:56px 1fr auto;gap:14px;align-items:center;padding:16px;border:1px solid rgba(98,230,255,.12);background:rgba(6,12,27,.52)}.activity-item .icon{width:56px;height:56px;display:grid;place-items:center;border:3px solid rgba(98,230,255,.26);background:linear-gradient(135deg,rgba(155,92,255,.16),rgba(41,182,255,.12));box-shadow:inset 0 0 0 4px rgba(255,255,255,.03)}.activity-item .icon i{font-size:24px;color:var(--cyan)}.activity-item h4{font-family:'Press Start 2P';font-size:9px;line-height:1.8;margin-bottom:6px}.activity-item p{font-size:13px;color:var(--muted);line-height:1.7}.activity-item .time{font-family:'Press Start 2P';font-size:8px;line-height:1.7;color:var(--pink);text-align:right}
.quest-card{padding:18px;border:1px solid rgba(98,230,255,.14);background:rgba(5,10,22,.56);display:grid;gap:14px;position:relative;z-index:2}.quest-head{display:flex;justify-content:space-between;gap:16px;align-items:center}.quest-head h4{font-family:'Press Start 2P';font-size:11px;line-height:1.8}.quest-head span{font-family:'Press Start 2P';font-size:8px;color:var(--cyan)}.quest-card p{font-size:14px;line-height:1.8;color:var(--muted)}.mission-metrics{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;position:relative;z-index:2}.metric-chip{padding:14px 12px;background:rgba(255,255,255,.02);border:1px solid rgba(98,230,255,.12)}.metric-chip small{display:block;font-family:'Press Start 2P';font-size:7px;line-height:1.7;color:var(--muted2);margin-bottom:10px}.metric-chip strong{font-family:'Press Start 2P';font-size:14px;line-height:1.7;color:#fff}.launch-panel{padding:26px;display:grid;gap:18px}.launch-panel p{font-size:15px;line-height:1.85;color:var(--muted);position:relative;z-index:2}.launch-box{padding:18px;border:1px solid rgba(98,230,255,.18);background:linear-gradient(180deg,rgba(5,11,22,.74),rgba(8,18,37,.82));display:grid;gap:12px;position:relative;z-index:2}.launch-box strong{font-family:'Press Start 2P';font-size:10px;line-height:1.8;color:var(--pink)}.launch-box span{font-size:13px;color:var(--muted);line-height:1.7}.footer-note{margin-top:4px;padding:14px 18px;border:1px dashed rgba(98,230,255,.18);font-size:13px;color:var(--muted2);line-height:1.8;position:relative;z-index:2}

.reveal{opacity:0;transform:translateY(18px);animation:revealUp .75s cubic-bezier(.22,1,.36,1) forwards}.d1{animation-delay:.08s}.d2{animation-delay:.16s}.d3{animation-delay:.24s}.d4{animation-delay:.34s}
@keyframes revealUp{to{opacity:1;transform:translateY(0)}}@keyframes twinkle{0%,100%{opacity:.3;transform:scale(1)}50%{opacity:1;transform:scale(1.7)}}@keyframes floaty{0%,100%{transform:translateY(0)}50%{transform:translateY(-16px)}}@keyframes scan{0%{transform:translateY(-100%)}100%{transform:translateY(100%)}}
@media(max-width:1120px){.menu a{font-size:7px;padding:12px 8px}.hero-grid,.dashboard-grid,.progress-layout{grid-template-columns:1fr}.hero-right{grid-template-columns:1fr}.quick-stats{grid-template-columns:repeat(3,1fr)}}
@media(max-width:820px){.topbar{top:0;margin-top:0;width:100%;height:auto;min-height:70px;border-left:0;border-right:0}.menu{display:none}.shell{width:92vw;margin-top:22px}.hero{padding:28px}.quick-stats,.mission-metrics{grid-template-columns:1fr}.profile-card{grid-template-columns:1fr}.activity-item{grid-template-columns:56px 1fr}.activity-item .time{grid-column:2;text-align:left}.title{font-size:clamp(24px,8.8vw,34px)}}
@media(max-width:640px){.brand h1{font-size:11px}.yellow-btn,.blue-btn,.dark-btn{font-size:8px;padding:10px 13px}.panel,.launch-panel,.hero{padding:22px}.progress-ring{width:180px;height:180px}.column .stick{height:112px}}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important;scroll-behavior:auto!important}}
</style>
</head>
<body>
<div class="app">
  <div class="orb one"></div><div class="orb two"></div><div class="orb three"></div>
  <div class="stars"><span></span><span></span><span></span><span></span><span></span><span></span></div>

  <header class="topbar">
    <a href="index.php" class="brand">
      <span class="coin"></span>
      <h1 class="pixel-font">Binary Minds</h1>
    </a>
    <nav class="menu">
      <a class="active" href="dashboard_player.php">Dashboard</a>
      <a href="page.php">Acceuil</a>
      <a href="pagecour.html">Cours</a>
      <a href="ques.php">Test</a>
    </nav>
    <a class="yellow-btn" href="pagecour.html">Start</a>
  </header>

  <main class="shell">
    <section class="hero pixel-card">
      <span class="corner tl"></span><span class="corner tr"></span><span class="corner bl"></span><span class="corner br"></span>
      <div class="hero-grid">
        <div>
          <p class="kicker reveal">Player connected</p>
          <h2 class="title reveal d1">Bonjour <span class="grad"><?php echo htmlspecialchars($nomUtilisateur); ?></span></h2>
          <p class="lead reveal d2">Voici ton espace personnel. Suis ton avancement, retrouve ta dernière activité et lance directement ta prochaine session d’apprentissage.</p>
          <div class="cta-row reveal d3">
            <a class="blue-btn" href="pagecour.html"><i class="bi bi-controller"></i> Start</a>
            <a class="dark-btn" href="#progress-section"><i class="bi bi-bar-chart-line"></i> Progress</a>
          </div>
          <div class="mini-note reveal d4"><i class="bi bi-stars"></i><span>Dernière action enregistrée : <strong>Quiz Java terminé</strong></span></div>
        </div>

        <div class="hero-right">
          <div class="hud-panel">
            <div class="profile-card">
              <div class="avatar"></div>
              <div class="profile-lines">
                <span class="tag">USER PROFILE</span>
                <h3><?php echo htmlspecialchars($nomUtilisateur); ?></h3>
                <p>Connecté et prêt à continuer son parcours. Ton tableau de bord affiche tes progrès et tes dernières actions.</p>
              </div>
            </div>
            <div class="quick-stats">
              <div class="stat-box"><span class="label">LEVEL</span><strong>07</strong><small>Pixel Runner</small></div>
              <div class="stat-box"><span class="label">XP TOTAL</span><strong>1280</strong><small>+140 cette semaine</small></div>
              <div class="stat-box"><span class="label">STREAK</span><strong>05J</strong><small>Continue</small></div>
            </div>
          </div>

          <div class="hud-panel">
            <div class="line-metric">
              <div class="line-head"><strong>Current focus</strong><span>Python - Fonctions</span></div>
              <div class="bar"><span id="focusBar"></span></div>
            </div>
            <div class="line-metric" style="margin-top:16px">
              <div class="line-head"><strong>Goal of the week</strong><span>3 chapitres + 1 test</span></div>
              <div class="bar"><span id="goalBar"></span></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="dashboard-grid" id="progress-section">
      <div class="stack">
        <div class="panel pixel-card">
          <span class="corner tl"></span><span class="corner tr"></span><span class="corner bl"></span><span class="corner br"></span>
          <h3 class="panel-title">Global progress</h3>
          <p class="panel-sub">Suivi général de ton parcours et de ta progression récente.</p>
          <div class="progress-layout">
            <div class="progress-ring-wrap">
              <div class="progress-ring" id="progressRing"></div>
              <div class="progress-center"><strong>72%</strong><span>COURSE COMPLETION</span></div>
            </div>
            <div class="progress-details">
              <div class="line-metric"><div class="line-head"><strong>Progression totale</strong><span>18/25 modules</span></div><div class="bar"><span id="totalBar"></span></div></div>
              <div class="line-metric"><div class="line-head"><strong>Tests réussis</strong><span>09 / 12</span></div><div class="bar"><span id="testsBar"></span></div></div>
              <div class="line-metric"><div class="line-head"><strong>Temps investi</strong><span>24 h</span></div><div class="bar"><span id="hoursBar"></span></div></div>
              <div class="graph">
                <div class="column"><div class="stick"><div class="fill" data-height="44%"></div></div><b>LUN</b></div>
                <div class="column"><div class="stick"><div class="fill" data-height="58%"></div></div><b>MAR</b></div>
                <div class="column"><div class="stick"><div class="fill" data-height="72%"></div></div><b>MER</b></div>
                <div class="column"><div class="stick"><div class="fill" data-height="66%"></div></div><b>JEU</b></div>
                <div class="column"><div class="stick"><div class="fill" data-height="84%"></div></div><b>VEN</b></div>
                <div class="column"><div class="stick"><div class="fill" data-height="92%"></div></div><b>SAM</b></div>
              </div>
            </div>
          </div>
        </div>

        <div class="panel pixel-card">
          <span class="corner tl"></span><span class="corner tr"></span><span class="corner bl"></span><span class="corner br"></span>
          <h3 class="panel-title">Dernière activité</h3>
          <p class="panel-sub">Les dernières actions effectuées par l’utilisateur.</p>
          <div class="activity-list">
            <div class="activity-item"><div class="icon"><i class="bi bi-check2-square"></i></div><div><h4>Quiz Java terminé</h4><p>Tu as validé le quiz du chapitre « JDBC & DAO » avec un score de 84%.</p></div><div class="time">Aujourd’hui<br>18:42</div></div>
            <div class="activity-item"><div class="icon"><i class="bi bi-journal-code"></i></div><div><h4>Chapitre repris</h4><p>Python - Fonctions et modules consulté pendant 32 minutes.</p></div><div class="time">Hier<br>21:05</div></div>
            <div class="activity-item"><div class="icon"><i class="bi bi-trophy"></i></div><div><h4>Nouveau badge</h4><p>Badge « Code Survivor » débloqué après 5 jours de progression.</p></div><div class="time">Cette semaine</div></div>
          </div>
        </div>
      </div>

      <div class="stack">
        <div class="panel pixel-card">
          <span class="corner tl"></span><span class="corner tr"></span><span class="corner bl"></span><span class="corner br"></span>
          <h3 class="panel-title">Current mission</h3>
          <p class="panel-sub">Le travail en cours et l’objectif immédiat.</p>
          <div class="quest-card">
            <div class="quest-head"><h4>Python</h4><span>EN COURS</span></div>
            <p>Tu es actuellement sur le chapitre « Fonctions avancées ». Il te reste 2 leçons et 1 mini test à terminer.</p>
            <div class="bar"><span id="currentCourseBar"></span></div>
          </div>
          <div class="mission-metrics" style="margin-top:14px">
            <div class="metric-chip"><small>CHAPITRES</small><strong>08/10</strong></div>
            <div class="metric-chip"><small>TESTS</small><strong>04/05</strong></div>
            <div class="metric-chip"><small>SCORE</small><strong>84%</strong></div>
          </div>
        </div>

        <div class="launch-panel pixel-card">
          <span class="corner tl"></span><span class="corner tr"></span><span class="corner bl"></span><span class="corner br"></span>
          <h3 class="panel-title">Launch your course</h3>
          <p>Quand tu es prête, appuie sur <strong>Start</strong> pour accéder directement à la page des cours.</p>
          <div class="launch-box"><strong>DESTINATION</strong><span>pagecour.html — espace des langages et parcours disponibles</span></div>
          <a class="blue-btn" href="pagecour.html"><i class="bi bi-rocket-takeoff-fill"></i> Start</a>
          <div class="footer-note">Les données affichées peuvent ensuite être remplacées par les vraies données de la base.</div>
        </div>
      </div>
    </section>
  </main>
</div>

<script>
window.addEventListener('load',()=>{
  document.getElementById('progressRing').style.setProperty('--val',72);
  const bars = {focusBar:78, goalBar:64, totalBar:72, testsBar:75, hoursBar:80, currentCourseBar:82};
  setTimeout(()=>{
    Object.keys(bars).forEach(id=>{ const el=document.getElementById(id); if(el) el.style.width=bars[id]+'%'; });
    document.querySelectorAll('.fill').forEach(fill=> fill.style.height = fill.dataset.height);
  },180);
});
</script>
</body>
</html>