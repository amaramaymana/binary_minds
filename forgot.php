<?php
$message = "";
$conn = new mysqli("localhost", "root", "", "binary_minds");

if ($conn->connect_error) {
    die("Connexion échouée");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $message = "Veuillez entrer votre email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide";
    } else {
        $check = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $newPassword = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);
            $hashedPassword = md5($newPassword);

            $update = $conn->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE email = ?");
            $update->bind_param("ss", $hashedPassword, $email);
            $update->execute();

            require __DIR__ . '/PHPMailer/src/Exception.php';
            require __DIR__ . '/PHPMailer/src/PHPMailer.php';
            require __DIR__ . '/PHPMailer/src/SMTP.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'tonemail@gmail.com';
                $mail->Password = 'mot_de_passe_application';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('tonemail@gmail.com', 'Binary Minds');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Nouveau mot de passe - Binary Minds';
                $mail->Body = "
                    <h3>Bonjour,</h3>
                    <p>Voici ton nouveau mot de passe :</p>
                    <p><b>$newPassword</b></p>
                    <p>Tu peux maintenant te connecter avec ce mot de passe.</p>
                ";

                $mail->send();
                $message = "Un nouveau mot de passe a été envoyé par email ✅";
            } catch (Exception $e) {
                $message = "Erreur lors de l'envoi de l'email";
            }
        } else {
            $message = "Aucun compte n'est associé à cet email";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mot de passe oublié | Binary Minds</title>

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
    max-width: 900px;
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
    max-width: 420px;
  }

  .right {
    padding: 34px 28px;
    background: #101223;
    display: flex;
    flex-direction: column;
    justify-content: center;
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
    line-height: 1.6;
  }

  form {
    display: grid;
    gap: 16px;
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

  input {
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

  input:focus {
    border-color: var(--pixel-cyan);
    box-shadow: 4px 4px 0 var(--pixel-pink);
    transform: translate(-1px, -1px);
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
    margin-top: 8px;
    color: var(--pixel-green);
    line-height: 1.5;
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
      Pas de panique. Entre ton adresse email pour recevoir un nouveau mot de passe.
    </p>
  </div>

  <div class="right">
    <h2 class="form-title">Mot de passe oublié</h2>
    <p class="form-subtitle">
      Saisis ton email pour recevoir un nouveau mot de passe par email.
    </p>

    <form method="POST">
      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="exemple@email.com" required>
      </div>

      <button type="submit" class="btn">Envoyer le mot de passe</button>
    </form>

    <p class="message"><?php echo $message; ?></p>
  </div>
</section>

</body>
</html>