<?php
session_start();

$conn = new mysqli("localhost", "root", "", "binary_minds");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ========== GESTION DE L'UTILISATEUR CONNECTÉ ==========
$current_user_id = 1; // CHANGE ICI SELON L'UTILISATEUR CONNECTÉ

if(isset($_GET['user_id']) && $_GET['user_id'] != '') {
    $current_user_id = intval($_GET['user_id']);
}

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Compter le nombre total d'utilisateurs
$count_sql = "SELECT COUNT(DISTINCT u.id) as total FROM users u";
$count_result = $conn->query($count_sql);
$total_users = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_users / $per_page);

// Requête classement principal avec pagination
$sql = "SELECT u.id, u.name, COALESCE(s.total_score, 0) as total_score
        FROM users u
        LEFT JOIN scores s ON u.id = s.user_id
        ORDER BY total_score DESC
        LIMIT $offset, $per_page";

$result = $conn->query($sql);

if (!$result) {
    die("Erreur requête: " . $conn->error);
}

$players = array();
while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}

// Statistiques globales
$stats_sql = "SELECT 
                COALESCE(AVG(s.total_score), 0) as avg_score, 
                COALESCE(MAX(s.total_score), 0) as max_score,
                COALESCE(MIN(s.total_score), 0) as min_score,
                COUNT(DISTINCT u.id) as total_users,
                SUM(COALESCE(s.total_score, 0)) as total_xp
              FROM users u
              LEFT JOIN scores s ON u.id = s.user_id";
$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();

// Récupérer le TOP 3
$top3_sql = "SELECT u.id, u.name, COALESCE(s.total_score, 0) as total_score
             FROM users u
             LEFT JOIN scores s ON u.id = s.user_id
             ORDER BY total_score DESC
             LIMIT 3";
$top3_result = $conn->query($top3_sql);
$top3 = array();
while ($row = $top3_result->fetch_assoc()) {
    $top3[] = $row;
}

// Compléter le top3 si moins de 3 joueurs
while(count($top3) < 3) {
    $top3[] = array('id' => 0, 'name' => '???', 'total_score' => 0);
}

// Calcul du rang de l'utilisateur connecté
$rank_sql = "SELECT 
                u.id, u.name, COALESCE(s.total_score, 0) as total_score,
                (SELECT COUNT(*) + 1 
                 FROM scores s2 
                 WHERE s2.total_score > COALESCE(s.total_score, 0)) as rank
             FROM users u
             LEFT JOIN scores s ON u.id = s.user_id
             WHERE u.id = $current_user_id";
$rank_result = $conn->query($rank_sql);
$current_rank = 0;
$current_user_score = 0;
$current_user_name = "";

if ($rank_result && $rank_result->num_rows > 0) {
    $rank_data = $rank_result->fetch_assoc();
    $current_rank = isset($rank_data['rank']) ? $rank_data['rank'] : 1;
    $current_user_score = isset($rank_data['total_score']) ? $rank_data['total_score'] : 0;
    $current_user_name = isset($rank_data['name']) ? $rank_data['name'] : "";
}

// Calcul du score suivant pour l'objectif
$next_score = 0;
foreach($players as $p) {
    if($p['total_score'] > $current_user_score && ($next_score == 0 || $p['total_score'] < $next_score)) {
        $next_score = $p['total_score'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Binary Minds - Mon Classement</title>
<style>
:root {
  --bg-dark: #0f1020;
  --bg-card: #1a1c33;
  --bg-input: #101223;
  --pixel-pink: #ff4fd8;
  --pixel-cyan: #39d5ff;
  --pixel-yellow: #ffe66d;
  --pixel-green: #8dff8a;
  --text-main: #f5f7ff;
  --text-soft: #b9c0ff;
  --gold: #ffd700;
  --silver: #c0c0c0;
  --bronze: #cd7f32;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Courier New", monospace;
  min-height: 100vh;
  color: var(--text-main);
  background:
    linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px),
    radial-gradient(circle at top, #1f2250 0%, #0b0c17 60%);
  background-size: 28px 28px, 28px 28px, cover;
  padding: 30px 16px;
  position: relative;
}

.pixels,
.pixels::before,
.pixels::after {
  position: fixed;
  content: "";
  width: 14px;
  height: 14px;
  background: var(--pixel-cyan);
  box-shadow:
    80px 120px var(--pixel-pink),
    200px 70px var(--pixel-yellow),
    320px 180px var(--pixel-green),
    500px 90px var(--pixel-cyan),
    700px 220px var(--pixel-pink),
    900px 130px var(--pixel-yellow),
    1100px 280px var(--pixel-green),
    150px 420px var(--pixel-cyan),
    380px 520px var(--pixel-pink),
    620px 480px var(--pixel-yellow),
    860px 400px var(--pixel-green),
    1080px 560px var(--pixel-cyan);
  opacity: 0.35;
  z-index: 0;
  animation: floatPixel 6s steps(6) infinite alternate;
  pointer-events: none;
}

.pixels::before {
  transform: translate(40px, 260px);
  animation-duration: 8s;
}

.pixels::after {
  transform: translate(-20px, 120px);
  animation-duration: 10s;
}

@keyframes floatPixel {
  from { transform: translateY(0px); }
  to { transform: translateY(-20px); }
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  position: relative;
  z-index: 1;
}

/* PODIUM */
.podium {
  display: flex;
  justify-content: center;
  align-items: flex-end;
  gap: 20px;
  margin-bottom: 40px;
  flex-wrap: wrap;
}

.podium-card {
  text-align: center;
  padding: 25px 20px;
  background: var(--bg-card);
  border: 4px solid;
  min-width: 200px;
  transition: all 0.3s ease;
  position: relative;
  box-shadow: 6px 6px 0 rgba(0,0,0,0.3);
}

.podium-card:hover {
  transform: translateY(-10px);
}

.podium-card.first {
  border-color: var(--gold);
  background: linear-gradient(135deg, #1a1c33, #2a1f3a);
  box-shadow: 0 0 30px rgba(255, 215, 0, 0.3), 6px 6px 0 rgba(0,0,0,0.3);
}

.podium-card.second {
  border-color: var(--silver);
}

.podium-card.third {
  border-color: var(--bronze);
}

.podium-icon {
  font-size: 56px;
  margin-bottom: 10px;
}

.podium-rank-badge {
  position: absolute;
  top: -15px;
  left: 50%;
  transform: translateX(-50%);
  background: var(--bg-dark);
  padding: 5px 15px;
  border: 2px solid var(--pixel-yellow);
  font-size: 14px;
  font-weight: bold;
  white-space: nowrap;
}

.podium-name {
  font-size: 22px;
  font-weight: bold;
  color: var(--text-main);
  margin: 15px 0 10px;
}

.podium-score {
  font-size: 28px;
  font-weight: bold;
  color: var(--pixel-yellow);
}

.podium-title {
  font-size: 12px;
  color: var(--text-soft);
  margin-top: 8px;
  text-transform: uppercase;
}

/* Grille principale */
.main-grid {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 25px;
}

/* Cartes */
.card {
  background: var(--bg-card);
  border: 4px solid var(--text-main);
  box-shadow: 8px 8px 0 var(--pixel-cyan), 12px 12px 0 var(--pixel-pink);
  overflow: hidden;
}

.card-header {
  background: linear-gradient(135deg, #1a1c33, #0f1020);
  padding: 20px;
  border-bottom: 3px solid var(--pixel-cyan);
}

.card-header h2 {
  color: var(--pixel-yellow);
  font-size: 22px;
  display: flex;
  align-items: center;
  gap: 10px;
  text-transform: uppercase;
}

.card-body {
  padding: 25px;
}

/* Stats */
.stat-item {
  display: flex;
  justify-content: space-between;
  padding: 12px 0;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

.stat-label {
  color: var(--pixel-cyan);
  font-weight: bold;
}

.stat-value {
  color: var(--pixel-yellow);
  font-weight: bold;
  font-size: 18px;
}

/* Barre de progression */
.progress-bar {
  background: var(--bg-input);
  border: 2px solid var(--pixel-cyan);
  height: 30px;
  overflow: hidden;
  margin: 20px 0;
}

.progress-fill {
  background: linear-gradient(90deg, var(--pixel-pink), var(--pixel-cyan));
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding-right: 10px;
  color: var(--bg-dark);
  font-weight: bold;
  font-size: 12px;
  transition: width 1s ease;
}

/* Rang actuel */
.current-rank-card {
  background: linear-gradient(135deg, rgba(255, 79, 216, 0.2), rgba(57, 213, 255, 0.2));
  border: 3px solid var(--pixel-yellow);
  padding: 25px;
  text-align: center;
  margin: 20px 0;
  box-shadow: 4px 4px 0 var(--pixel-pink);
  animation: glow 2s ease-in-out infinite;
}

@keyframes glow {
  0%, 100% { box-shadow: 4px 4px 0 var(--pixel-pink); }
  50% { box-shadow: 8px 8px 0 var(--pixel-cyan); }
}

.current-rank-label {
  color: var(--pixel-yellow);
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 3px;
}

.current-rank-value {
  font-size: 64px;
  font-weight: bold;
  color: var(--pixel-yellow);
  margin: 10px 0;
  text-shadow: 3px 3px 0 var(--pixel-pink);
}

.current-user-name {
  font-size: 24px;
  font-weight: bold;
  color: var(--text-main);
  margin: 10px 0;
}

.current-user-score {
  font-size: 20px;
  color: var(--pixel-green);
  font-weight: bold;
}

.next-milestone {
  font-size: 12px;
  color: var(--text-soft);
  margin-top: 15px;
  padding: 10px;
  background: rgba(0,0,0,0.3);
  border: 1px solid var(--pixel-yellow);
}

/* Tableau */
.table-wrapper {
  max-height: 500px;
  overflow-y: auto;
  border: 2px solid var(--pixel-cyan);
}

table {
  width: 100%;
  border-collapse: collapse;
}

th {
  color: var(--pixel-yellow);
  text-align: left;
  padding: 14px 12px;
  background: rgba(57, 213, 255, 0.2);
  position: sticky;
  top: 0;
  text-transform: uppercase;
}

td {
  padding: 12px;
  border-bottom: 1px solid rgba(255,255,255,0.1);
  transition: all 0.2s;
}

tr:hover {
  background: rgba(57, 213, 255, 0.1);
  transform: translateX(5px);
}

.top1 { background: linear-gradient(90deg, rgba(255,215,0,0.2), transparent); border-left: 4px solid var(--gold); }
.top2 { background: linear-gradient(90deg, rgba(192,192,192,0.15), transparent); border-left: 4px solid var(--silver); }
.top3 { background: linear-gradient(90deg, rgba(205,127,50,0.15), transparent); border-left: 4px solid var(--bronze); }

.current-user-row { 
  background: linear-gradient(90deg, rgba(255,79,216,0.4), rgba(57,213,255,0.2));
  border: 2px solid var(--pixel-yellow);
  animation: pulse 1.5s ease-in-out infinite;
  font-weight: bold;
}

@keyframes pulse {
  0%, 100% { background: linear-gradient(90deg, rgba(255,79,216,0.4), rgba(57,213,255,0.2)); }
  50% { background: linear-gradient(90deg, rgba(255,79,216,0.6), rgba(57,213,255,0.4)); }
}

.rank-medal { font-size: 18px; font-weight: bold; }
.score-value { font-weight: bold; color: var(--pixel-green); font-size: 16px; }

.user-badge {
  display: inline-block;
  background: var(--pixel-pink);
  color: var(--bg-dark);
  padding: 2px 8px;
  font-size: 10px;
  margin-left: 8px;
  font-weight: bold;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 20px;
  flex-wrap: wrap;
}

.page-btn {
  padding: 8px 16px;
  background: var(--bg-input);
  border: 2px solid var(--pixel-cyan);
  color: var(--pixel-cyan);
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  font-family: monospace;
  font-weight: bold;
}

.page-btn:hover, .page-btn.active {
  background: var(--pixel-cyan);
  color: var(--bg-dark);
  transform: translate(-2px, -2px);
  box-shadow: 3px 3px 0 var(--pixel-pink);
}

/* Boutons */
.action-buttons {
  display: flex;
  gap: 15px;
  margin-top: 20px;
  flex-wrap: wrap;
}

.btn {
  flex: 1;
  padding: 12px;
  background: transparent;
  border: 2px solid var(--pixel-cyan);
  color: var(--pixel-cyan);
  text-decoration: none;
  text-align: center;
  font-weight: bold;
  text-transform: uppercase;
  font-size: 12px;
  transition: all 0.2s;
  cursor: pointer;
  font-family: monospace;
}

.btn-primary {
  background: var(--pixel-yellow);
  border-color: var(--pixel-yellow);
  color: var(--bg-dark);
}

.btn:hover {
  transform: translate(-2px, -2px);
  box-shadow: 4px 4px 0 var(--pixel-pink);
  background: var(--pixel-cyan);
  color: var(--bg-dark);
}

/* Responsive */
@media (max-width: 900px) {
  .main-grid {
    grid-template-columns: 1fr;
  }
  .podium-card {
    min-width: 140px;
    padding: 15px;
  }
  .podium-name {
    font-size: 16px;
  }
  .podium-score {
    font-size: 20px;
  }
  .current-rank-value {
    font-size: 48px;
  }
}

@media (max-width: 560px) {
  .podium-card {
    min-width: 100px;
    padding: 10px;
  }
  .podium-name {
    font-size: 12px;
  }
  .podium-score {
    font-size: 14px;
  }
  .podium-rank-badge {
    font-size: 10px;
    padding: 2px 8px;
    white-space: nowrap;
  }
}
</style>
</head>
<body>

<div class="pixels"></div>

<div class="container">
  
  <!-- PODIUM TOP 3 -->
  <div class="podium">
    <div class="podium-card second">
      <div class="podium-rank-badge">2</div>
      <div class="podium-icon"></div>
      <div class="podium-name"><?php echo htmlspecialchars($top3[1]['name']); ?></div>
      <div class="podium-score"><?php echo number_format($top3[1]['total_score']); ?> XP</div>
      <div class="podium-title">Vice-Champion</div>
    </div>
    
    <div class="podium-card first">
      <div class="podium-rank-badge">CHAMPION</div>
      <div class="podium-icon"></div>
      <div class="podium-name"><?php echo htmlspecialchars($top3[0]['name']); ?></div>
      <div class="podium-score"><?php echo number_format($top3[0]['total_score']); ?> XP</div>
      <div class="podium-title">LEGENDARY</div>
    </div>
    
    <div class="podium-card third">
      <div class="podium-rank-badge">3</div>
      <div class="podium-icon"></div>
      <div class="podium-name"><?php echo htmlspecialchars($top3[2]['name']); ?></div>
      <div class="podium-score"><?php echo number_format($top3[2]['total_score']); ?> XP</div>
      <div class="podium-title">Bronze Warrior</div>
    </div>
  </div>

  <div class="main-grid">
    
    <!-- COLONNE GAUCHE -->
    <div class="card">
      <div class="card-header">
        <h2>STATISTIQUES</h2>
      </div>
      <div class="card-body">
        <div class="stat-item">
          <span class="stat-label">Total Joueurs</span>
          <span class="stat-value"><?php echo $stats['total_users']; ?></span>
        </div>
        <div class="stat-item">
          <span class="stat-label"> Score Max</span>
          <span class="stat-value"><?php echo number_format($stats['max_score']); ?> XP</span>
        </div>
        <div class="stat-item">
          <span class="stat-label">Score Moyen</span>
          <span class="stat-value"><?php echo round($stats['avg_score']); ?> XP</span>
        </div>
        <div class="stat-item">
          <span class="stat-label"> Total XP Généré</span>
          <span class="stat-value"><?php echo number_format($stats['total_xp']); ?> XP</span>
        </div>
        
        <!-- MON RANG -->
        <div class="current-rank-card">
          <div class="current-rank-label">MON CLASSEMENT</div>
          <div class="current-rank-value">
            <?php 
            if ($current_rank == 1) echo "";
            elseif ($current_rank == 2) echo "";
            elseif ($current_rank == 3) echo "";
            echo "" . $current_rank;
            ?>
          </div>
          <div class="current-user-name">
            <?php echo htmlspecialchars($current_user_name); ?>
            <span class="user-badge">MOI</span>
          </div>
          <div class="current-user-score">
             <?php echo number_format($current_user_score); ?> XP
          </div>
          
          <?php if($current_user_score > 0 && $stats['max_score'] > 0 && $current_rank > 1 && $next_score > 0): ?>
          <div class="next-milestone">
             Objectif : Dépasser le #<?php echo $current_rank - 1; ?><br>
            Plus que <?php echo number_format($next_score - $current_user_score); ?> XP pour grimper !
          </div>
          <?php elseif($current_rank == 1 && $current_user_score > 0): ?>
          <div class="next-milestone">
             Tu es le champion ! Garde ta couronne ! 
          </div>
          <?php elseif($current_user_score == 0): ?>
          <div class="next-milestone">
             Commence un quiz pour gagner ton premier XP !
          </div>
          <?php endif; ?>
        </div>
        
        <div class="progress-bar">
          <div class="progress-fill" style="width: <?php echo ($stats['max_score'] > 0 ? min(100, ($current_user_score / $stats['max_score']) * 100) : 0); ?>%;">
            <?php echo round(($current_user_score / max(1, $stats['max_score'])) * 100); ?>% du champion
          </div>
        </div>
      </div>
    </div>

    <!-- COLONNE DROITE - CLASSEMENT -->
    <div class="card">
      <div class="card-header">
        <h2> CLASSEMENT GÉNÉRAL</h2>
      </div>
      <div class="card-body">
        <div class="table-wrapper">
          <table>
            <thead>
              <tr><th></th><th> JOUEUR</th><th> SCORE</th><th>TITRE</th></tr>
            </thead>
            <tbody>
              <?php
              $rank = $offset + 1;
              foreach($players as $player):
                $row_class = "";
                $medal = "";
                $title = "Guerrier";
                
                if($rank == 1){ $row_class = "top1"; $medal = ""; $title = "LÉGENDE"; }
                elseif($rank == 2){ $row_class = "top2"; $medal = ""; $title = "MAÎTRE"; }
                elseif($rank == 3){ $row_class = "top3"; $medal = ""; $title = "EXPERT"; }
                elseif($player['total_score'] >= 80) $title = "ÉLITE";
                elseif($player['total_score'] >= 60) $title = "AVANCÉ";
                elseif($player['total_score'] >= 40) $title = "INTERMÉDIAIRE";
                elseif($player['total_score'] > 0) $title = "DÉBUTANT";
                else $title = "NOVICE";
                
                if($player['id'] == $current_user_id) {
                    $row_class .= " current-user-row";
                    $title .= "";
                }
              ?>
              <tr class="<?php echo $row_class; ?>">
                <td class="rank-medal"><?php echo $medal . " " . $rank; ?></td>
                <td>
                  <?php echo htmlspecialchars($player['name']); ?>
                  <?php if($player['id'] == $current_user_id): ?>
                    <span class="user-badge">MOI</span>
                  <?php endif; ?>
                </td>
                <td class="score-value"><?php echo number_format($player['total_score']); ?> XP</td>
                <td><?php echo $title; ?></td>
              </tr>
              <?php $rank++; endforeach; ?>
              
              <?php if(count($players) == 0): ?>
              <tr>
                <td colspan="4" style="text-align: center; padding: 40px;">
                  Aucun joueur trouvé
                </td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        
        <?php if($total_pages > 1): ?>
        <div class="pagination">
          <?php if($page > 1): ?>
            <a href="?page=<?php echo $page-1; ?>&user_id=<?php echo $current_user_id; ?>" class="page-btn">PRÉCÉDENT</a>
          <?php endif; ?>
          
          <?php 
          $start_page = max(1, $page - 2);
          $end_page = min($total_pages, $page + 2);
          for($i = $start_page; $i <= $end_page; $i++): 
          ?>
            <a href="?page=<?php echo $i; ?>&user_id=<?php echo $current_user_id; ?>" class="page-btn <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
          <?php endfor; ?>
          
          <?php if($page < $total_pages): ?>
            <a href="?page=<?php echo $page+1; ?>&user_id=<?php echo $current_user_id; ?>" class="page-btn">SUIVANT </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="action-buttons">
          <a href="quiz.php?user_id=<?php echo $current_user_id; ?>" class="btn btn-primary">PASSER UN QUIZ</a>
          <a href="conseil.php?user_id=<?php echo $current_user_id; ?>&score=<?php echo $current_user_score; ?>" class="btn">VOIR CONSEILS</a>
          <button onclick="location.reload()" class="btn">RAFRAÎCHIR</button>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>