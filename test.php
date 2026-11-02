<?php
$message = "";
$conn = new mysqli("localhost", "root", "", "binary_minds");

if ($conn->connect_error) {
    die("Connexion échouée");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $niveau = trim($_POST['niveau']);
    $specialite = trim($_POST['specialite']);
    $langage = trim($_POST['motivation']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if (!isset($_POST['terms'])) {
        $message = "Tu dois accepter les conditions";
    } elseif (strlen($password) < 6) {
        $message = "Mot de passe trop court";
    } elseif ($password != $confirmPassword) {
        $message = "Les mots de passe ne correspondent pas";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide";
    } else {
        $check = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "Cet email existe déjà";
        } else {
            $passwordHash = md5($password);

            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, email, telephone, niveau, specialite, langage, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->bind_param("ssssssss", $nom, $prenom, $email, $telephone, $niveau, $specialite, $langage, $passwordHash);

            if ($stmt->execute()) {
    header("Location: login.php");
    exit();
} else {
    $message = "Erreur lors de l'inscription";
}
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Binary Minds | Inscription</title>
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
      --danger: #ff6b6b;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      min-height: 100vh;
      font-family: "Courier New", monospace;
      color: var(--text-main);
      background:
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px),
        radial-gradient(circle at top, #1f2250 0%, #0b0c17 60%);
      background-size: 28px 28px, 28px 28px, cover;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 30px 16px;
      overflow-x: hidden;
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
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 980px;
      display: grid;
      grid-template-columns: 1.05fr 1fr;
      background: var(--bg-card);
      border: 4px solid var(--text-main);
      box-shadow:
        8px 8px 0 var(--pixel-cyan),
        16px 16px 0 var(--pixel-pink);
      overflow: hidden;
    }

    .left {
      padding: 38px 32px;
      background:
        linear-gradient(135deg, rgba(57,213,255,0.12), rgba(255,79,216,0.12)),
        #15172b;
      border-right: 4px solid rgba(255,255,255,0.18);
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: 20px;
    }

    .logo {
      font-size: clamp(30px, 5vw, 56px);
      line-height: 0.95;
      font-weight: bold;
      text-transform: uppercase;
      text-shadow:
        3px 3px 0 var(--pixel-pink),
        6px 6px 0 var(--pixel-cyan);
    }

    .subtitle {
      color: var(--text-soft);
      font-size: 15px;
      line-height: 1.7;
      max-width: 440px;
    }

    .right {
      padding: 34px 28px;
      background: #101223;
    }

    .form-title {
      font-size: 28px;
      margin-bottom: 8px;
      text-transform: uppercase;
      color: var(--pixel-cyan);
    }

    .form-subtitle {
      color: var(--text-soft);
      font-size: 14px;
      margin-bottom: 24px;
    }

    form {
      display: grid;
      gap: 16px;
    }

    .row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }

    .field {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    label {
      font-size: 13px;
      color: var(--pixel-yellow);
      text-transform: uppercase;
    }

    input,
    select {
      width: 100%;
      padding: 14px 12px;
      border: 3px solid #fff;
      background: var(--bg-input);
      color: var(--text-main);
      outline: none;
      font-family: inherit;
      font-size: 14px;
      box-shadow: 4px 4px 0 rgba(255,255,255,0.08);
      transition: 0.15s steps(3);
    }

    input:focus,
    select:focus {
      border-color: var(--pixel-cyan);
      box-shadow: 4px 4px 0 var(--pixel-pink);
      transform: translate(-1px, -1px);
    }

    .checkbox {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 13px;
      color: var(--text-soft);
      line-height: 1.5;
      margin-top: 2px;
    }

    .checkbox input {
      width: 18px;
      height: 18px;
      margin-top: 2px;
      accent-color: var(--pixel-pink);
      box-shadow: none;
    }

    .btn {
      margin-top: 8px;
      padding: 15px 18px;
      border: 3px solid #111;
      background: var(--pixel-yellow);
      color: #111;
      font-family: inherit;
      font-size: 15px;
      font-weight: bold;
      text-transform: uppercase;
      cursor: pointer;
      box-shadow: 5px 5px 0 var(--pixel-pink);
      transition: 0.15s steps(2);
    }

    .btn:hover {
      transform: translate(-2px, -2px);
      box-shadow: 7px 7px 0 var(--pixel-cyan);
    }

    .btn:active {
      transform: translate(2px, 2px);
      box-shadow: 2px 2px 0 var(--pixel-cyan);
    }

    .message {
      min-height: 22px;
      font-size: 13px;
      margin-top: 2px;
      color: var(--pixel-green);
    }

    .message.error {
      color: var(--danger);
    }

    @media (max-width: 860px) {
      .container {
        grid-template-columns: 1fr;
      }

      .left {
        border-right: none;
        border-bottom: 4px solid rgba(255,255,255,0.18);
      }
    }

    @media (max-width: 560px) {
      .row {
        grid-template-columns: 1fr;
      }

      .left,
      .right {
        padding: 24px 18px;
      }

      .container {
        box-shadow:
          6px 6px 0 var(--pixel-cyan),
          10px 10px 0 var(--pixel-pink);
      }
    }
  </style>
</head>
<body>
  <div class="pixels"></div>

  <section class="container">
    <div class="left">
      <h1 class="logo">Binary<br>Minds</h1>
      <p class="subtitle">
        Rejoins l’univers <strong>Binary Minds</strong> et inscris-toi pour faire partie d’une équipe créative.
      </p>
    </div>

    <div class="right">
      <h2 class="form-title">Inscription</h2>
      <p class="form-subtitle">Complète le formulaire pour rejoindre Binary Minds.</p>

      <form method="POST" action="">
        <div class="row">
          <div class="field">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
          </div>
          <div class="field">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
          </div>
        </div>

        <div class="row">
          <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="exemple@email.com" required>
          </div>
          <div class="field">
            <label for="telephone">Téléphone</label>
            <input type="tel" id="telephone" name="telephone" placeholder="+216 xx xxx xxx" required>
          </div>
        </div>

        <div class="row">
          <div class="field">
            <label for="niveau">Niveau</label>
            <select id="niveau" name="niveau" required>
              <option value="">Choisir</option>
              <option>L1</option>
              <option>L2</option>
              <option>L3</option>
              <option>Master</option>
              <option>Autre</option>
            </select>
          </div>
          <div class="field">
            <label for="specialite">Spécialité</label>
            <input type="text" id="specialite" name="specialite" placeholder="Ex: Génie Logiciel" required>
          </div>
        </div>

        <div class="field">
          <label for="motivation">langage souhaité</label>
          <select id="motivation" name="motivation" required>
            <option value="">Choisir un langage de programmation</option>
            <option>python</option>
            <option>java</option>
            <option>c sharp </option>
          </select>
        </div>

        <div class="row">
          <div class="field">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="********" required>
          </div>
          <div class="field">
            <label for="confirmPassword">Confirmer</label>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="********" required>
          </div>
        </div>

        <label class="checkbox">
          <input type="checkbox" id="terms" name="terms" required>
          <span>J’accepte les conditions d’inscription et je confirme que mes informations sont correctes.</span>
        </label>

        <button type="submit" class="btn">Créer mon profil</button>
        
        <p class="message">
          <?php echo $message; ?>
        </p>

      </form>
    </div>
  </section>

</body>
</html>