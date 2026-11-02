<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ready - Universe Loading</title>
    
    <!-- Importation des polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">

    <style>
        /* --- VARIABLES & RESET --- */
        :root {
            --accent-pink: #ff00ff;
            --accent-purple: #bd00ff;
            --accent-cyan: #00ffff;
            --text-color: #ffffff;
            --pixel-font-title: 'Press Start 2P', cursive;
            --pixel-font-body: 'VT323', monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-image: url('mg.png'); 
            background-size: cover;       
            background-position: center;   
            background-repeat: no-repeat;  
            background-color: #1a0b2e;      
            
            color: var(--text-color);
            font-family: var(--pixel-font-body);
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Assombrissement léger global */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); 
            z-index: 1;
        }

        /* --- CONTENEUR PRINCIPAL --- */
        .main-container {
            z-index: 10; 
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 80%;
            max-width: 1200px;
            height: 60vh;
            position: relative;
        }

        /* --- SECTION GAUCHE : CHARGEMENT --- */
        .loading-section {
            flex: 1;
            padding-right: 50px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        h1 {
            font-family: var(--pixel-font-title);
            font-size: 2.5rem;
            color: var(--accent-purple);
            text-shadow: 4px 4px 0px #000;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .loading-text {
            font-size: 2rem;
            color: var(--text-color);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .percentage {
            color: var(--accent-pink);
            font-weight: bold;
        }

        .progress-container {
            width: 100%;
            height: 30px;
            background-color: #000;
            border: 4px solid var(--text-color);
            position: relative;
            box-shadow: 4px 4px 0px var(--accent-purple);
        }

        .progress-bar {
            height: 100%;
            width: 0%;
            background-color: var(--accent-pink);
            background-image: linear-gradient(
                45deg, 
                rgba(0,0,0,0.25) 25%, 
                transparent 25%, 
                transparent 50%, 
                rgba(0,0,0,0.25) 50%, 
                rgba(0,0,0,0.25) 75%, 
                transparent 75%, 
                transparent
            );
            background-size: 20px 20px;
            transition: width 0.1s linear;
        }

        /* --- SECTION DROITE : WIZARD --- */
        .wizard-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .wizard-img {
            width: 300px; 
            height: auto;
            animation: none; 
            
            /* Pas de néon */
            object-fit: contain; 
        }

        /* --- MODALE --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Transparence pour voir l'image uni derrière */
            background: rgba(0, 0, 0, 0.3); 
            
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: rgba(26, 11, 46, 0.95); 
            border: 4px solid var(--accent-cyan);
            padding: 40px;
            text-align: center;
            box-shadow: 10px 10px 0px #000;
            max-width: 500px;
            width: 90%;
            transform: scale(0.8);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-content::before {
            content: '';
            position: absolute;
            top: -4px; left: -4px; right: -4px; bottom: -4px;
            box-shadow: inset 0 0 20px var(--accent-purple);
            z-index: -1;
        }

        .modal-title {
            font-family: var(--pixel-font-title);
            color: var(--accent-pink);
            font-size: 1.5rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        .btn {
            font-family: var(--pixel-font-title);
            font-size: 1rem;
            padding: 15px 25px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            position: relative;
        }

        .btn-yes {
            background-color: var(--accent-cyan);
            color: #000;
            box-shadow: 4px 4px 0px #008888;
        }

        .btn-no {
            background-color: #ff3333;
            color: #fff;
            box-shadow: 4px 4px 0px #880000;
        }

        .btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.2);
        }

        .btn:active {
            transform: translateY(2px);
            box-shadow: 2px 2px 0px;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column-reverse;
                justify-content: center;
                gap: 40px;
            }

            .loading-section {
                padding-right: 0;
                text-align: center;
                align-items: center;
                width: 100%;
            }

            .wizard-img {
                width: 200px;
            }

            h1 {
                font-size: 1.5rem;
            }
            
            .modal-content {
                padding: 20px;
            }

            .button-group {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>

    <main class="main-container">
        
        <!-- Partie Gauche : Chargement -->
        <section class="loading-section">
            <h1>GAME FESTIVAL<br><span style="font-size: 0.6em; color: var(--text-color);">START</span></h1>
            
            <div class="loading-text">
                Loading... <span class="percentage" id="percent-display">0%</span>
            </div>
            
            <div class="progress-container">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
            
            <p style="margin-top: 20px; color: #ddd; font-size: 1.2rem; max-width: 400px; text-shadow: 1px 1px 2px black;">
                Initializing universe graphics...<br>
                Loading assets...<br>
                Preparing wizard magic...
            </p>
        </section>

        <!-- Partie Droite : Wizard Stable, Sans Neon -->
        <section class="wizard-section">
            <img 
                src="uni1.gif" 
                onerror="this.onerror=null; this.src='https://picsum.photos/seed/wizardmagic/300/300.jpg';" 
                alt="Le Wizard Univers" 
                class="wizard-img"
            >
        </section>

    </main>

    <!-- Fenêtre Modale -->
    <div class="modal-overlay" id="modal-overlay">
        <div class="modal-content">
            <h2 class="modal-title">ARE YOU READY TO<br>ENTER OUR UNIVERSE?</h2>
            <div class="button-group">
                <button class="btn btn-yes" onclick="handleYes()">OUI</button>
                <button class="btn btn-no" onclick="handleNo()">NON</button>
            </div>
        </div>
    </div>

    <script>
        const progressBar = document.getElementById('progress-bar');
        const percentDisplay = document.getElementById('percent-display');
        const modalOverlay = document.getElementById('modal-overlay');
        
        let progress = 0;
        const loadingSpeed = 30; 

        // Simulation du chargement
        const loadingInterval = setInterval(() => {
            progress += Math.floor(Math.random() * 3) + 1;

            if (progress >= 100) {
                progress = 100;
                clearInterval(loadingInterval);
                setTimeout(showModal, 500);
            }

            progressBar.style.width = `${progress}%`;
            percentDisplay.textContent = `${progress}%`;

        }, loadingSpeed);

        // Afficher la modale
        function showModal() {
            modalOverlay.classList.add('active');
        }

        // Action Bouton OUI -> REDIRECTION VERS LOGIN.PHP
        function handleYes() {
            const btn = document.querySelector('.btn-yes');
            btn.innerHTML = "LET'S GO!";
            btn.style.backgroundColor = "#00ff00"; 
            
            // Délai court pour l'effet visuel, puis redirection
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 500);
        }

        // Action Bouton NON
        function handleNo() {
            const modalContent = document.querySelector('.modal-content');
            
            modalContent.style.borderColor = "red";
            document.querySelector('.modal-title').innerHTML = "TOO BAD...<br>LOADING CANCELED.";
            document.querySelector('.modal-title').style.color = "red";
            document.querySelector('.button-group').style.display = 'none';

            setTimeout(() => {
                alert("Maybe next time! (Click OK to reload)");
                location.reload();
            }, 1500);
        }
    </script>
</body>
</html>