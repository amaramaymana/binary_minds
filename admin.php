<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Binary Minds — Admin Mode Pixel (Mauve)</title>
<!-- Importation de polices Pixel Art -->
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
<style>
  /* --- CONFIGURATION & VARIABLES (THÈME MAUVE) --- */
  :root {
    /* Fond sombre violet */
    --bg-dark: #1a1025;      
    --bg-panel: #2b1d35;    
    --bg-input: #140a1c;    
    
    /* Bordures violettes */
    --border-color: #5c3a6e;
    --border-light: #9e76b5;
    
    /* Texte */
    --text-main: #f0e6f5;
    --text-muted: #a58eb0;
    
    /* Couleur Primaire (Mauve vif) */
    --primary: #9d4edd;     
    --primary-dark: #7b2cbf; 
    
    /* Couleur Accent (Rose Mauve pâle) */
    --accent: #e0aaff;      
    
    --success: #5da060; /* Le vert reste le même pour le succès */
    --danger: #a83f39;  /* Le rouge reste le même pour l'erreur */
    
    --font-title: 'Press Start 2P', cursive;
    --font-body: 'VT323', monospace;
    
    --pixel-size: 4px;
  }

  * {
    box-sizing: border-box;
    outline: none;
  }

  body {
    margin: 0;
    padding: 0;
    background-color: var(--bg-dark);
    color: var(--text-main);
    font-family: var(--font-body);
    font-size: 18px; 
    line-height: 1.4;
    image-rendering: pixelated;
    overflow: hidden;
  }

  /* --- UTILITAIRES PIXELS --- */
  .pixel-box {
    background-color: var(--bg-panel);
    border: 4px solid var(--border-color);
    /* Effet 3D pixel utilisant les nouvelles couleurs de bordure */
    box-shadow: 
      -4px 0 0 0 var(--border-light),
      4px 0 0 0 var(--border-light),
      0 -4px 0 0 var(--border-light),
      0 4px 0 0 var(--border-light);
    position: relative;
    margin-bottom: 20px;
  }

  .pixel-btn {
    background-color: var(--primary);
    color: white;
    border: none;
    padding: 10px 15px;
    font-family: var(--font-title);
    font-size: 10px;
    cursor: pointer;
    text-transform: uppercase;
    position: relative;
    box-shadow: inset -4px -4px 0px 0px rgba(0,0,0,0.3);
    transition: transform 0.1s;
  }

  .pixel-btn:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
  }

  .pixel-btn:active {
    transform: translateY(2px);
    box-shadow: inset 4px 4px 0px 0px rgba(0,0,0,0.3);
  }

  .pixel-btn.danger { background-color: var(--danger); }
  .pixel-btn.success { background-color: var(--success); }
  .pixel-btn.sm { font-size: 8px; padding: 6px 10px; }

  /* --- LAYOUT --- */
  .app-container {
    display: grid;
    grid-template-columns: 250px 1fr;
    height: 100vh;
  }

  /* --- SIDEBAR --- */
  .sidebar {
    background-color: var(--bg-panel);
    border-right: 4px solid var(--border-color);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .logo {
    font-family: var(--font-title);
    font-size: 14px;
    color: var(--primary);
    text-shadow: 2px 2px 0px #000;
    margin-bottom: 30px;
    text-align: center;
    line-height: 1.5;
  }

  .nav-item {
    padding: 15px;
    cursor: pointer;
    border: 2px solid transparent;
    font-family: var(--font-title);
    font-size: 10px;
    color: var(--text-muted);
    transition: all 0.2s;
  }

  .nav-item:hover {
    color: white;
    background: rgba(255,255,255,0.05);
    border-color: rgba(255,255,255,0.1);
  }

  .nav-item.active {
    color: var(--bg-dark);
    background-color: var(--primary);
    border-color: var(--primary);
    box-shadow: inset -4px -4px 0px 0px rgba(0,0,0,0.2);
  }

  /* --- MAIN CONTENT --- */
  .main-content {
    padding: 30px;
    overflow-y: auto;
    position: relative;
  }

  .header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }

  .page-title {
    font-family: var(--font-title);
    font-size: 20px;
    color: var(--text-main);
    margin: 0;
  }

  .db-badge {
    font-family: var(--font-body);
    font-size: 16px;
    color: var(--accent); /* Utilisation de l'accent mauve clair */
    border: 2px solid var(--accent);
    padding: 5px 10px;
  }

  /* --- TABLES STYLE --- */
  .pixel-table-container {
    width: 100%;
    overflow-x: auto;
  }

  .pixel-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 18px;
  }

  .pixel-table th {
    background-color: var(--bg-dark);
    color: var(--primary);
    text-align: left;
    padding: 12px;
    border-bottom: 4px solid var(--border-color);
    font-family: var(--font-title);
    font-size: 10px;
    text-transform: uppercase;
  }

  .pixel-table td {
    padding: 12px;
    border-bottom: 2px solid var(--border-color);
    color: var(--text-main);
  }

  .pixel-table tr:hover td {
    background-color: rgba(157, 78, 221, 0.1); /* Hover mauve transparent */
  }

  /* --- FORMULAIRES --- */
  .form-group {
    margin-bottom: 15px;
  }

  .form-label {
    display: block;
    margin-bottom: 5px;
    color: var(--text-muted);
    font-size: 16px;
  }

  .pixel-input, .pixel-select {
    width: 100%;
    background: var(--bg-input);
    border: 4px solid var(--border-color);
    color: white;
    padding: 10px;
    font-family: var(--font-body);
    font-size: 18px;
  }

  .pixel-input:focus, .pixel-select:focus {
    border-color: var(--primary);
  }
  
  input[type="file"] {
    background: var(--bg-input);
    border: 4px solid var(--border-color);
    color: var(--text-muted);
    padding: 8px;
    font-family: var(--font-body);
    width: 100%;
  }

  /* --- MODAL --- */
  .modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
  }

  .modal-overlay.open {
    opacity: 1;
    pointer-events: all;
  }

  .modal-box {
    background: var(--bg-panel);
    width: 500px;
    max-width: 90%;
    padding: 4px; 
    transform: translateY(20px);
    transition: transform 0.2s;
  }

  .modal-overlay.open .modal-box {
    transform: translateY(0);
  }

  .modal-inner {
    border: 2px solid var(--border-color);
    padding: 20px;
  }

  .modal-header {
    font-family: var(--font-title);
    font-size: 14px;
    margin-bottom: 20px;
    color: var(--primary);
    border-bottom: 4px solid var(--border-color);
    padding-bottom: 10px;
  }

  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
  }

  /* --- TOAST NOTIFICATION --- */
  .toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: var(--bg-panel);
    border: 4px solid var(--success);
    color: white;
    padding: 15px 20px;
    font-family: var(--font-title);
    font-size: 10px;
    transform: translateY(100px);
    transition: transform 0.3s;
    z-index: 2000;
  }
  .toast.show {
    transform: translateY(0);
  }

  /* --- PILLS & BADGES --- */
  .pill {
    padding: 2px 8px;
    font-size: 14px;
    border: 2px solid;
    display: inline-block;
  }
  .pill.admin { border-color: var(--accent); color: var(--accent); }
  .pill.user { border-color: var(--primary); color: var(--primary); }
  .pill.course { border-color: var(--success); color: var(--success); }
  .pill.test { border-color: #d6b84e; color: #d6b84e; }
  
  .lang-badge {
    font-size: 14px;
    color: var(--text-main);
    font-weight: bold;
  }

  .hidden { display: none !important; }

</style>
</head>
<body>

  <!-- LOGIN SIMULATION -->
  <div id="loginScreen" style="position:fixed;top:0;left:0;width:100%;height:100%;background:var(--bg-dark);z-index:5000;display:flex;justify-content:center;align-items:center;">
    <div class="login-box pixel-box">
      <div style="padding: 30px;">
        <h1 style="font-family: var(--font-title); color: var(--primary); font-size: 18px; margin-bottom: 20px;">BINARY_MINDS</h1>
        <p style="color: var(--text-muted); margin-bottom: 30px;">SYSTEM ADMIN ACCESS v2.0 (MAUVE)</p>
        <form onsubmit="handleLogin(event)">
          <div class="form-group">
            <input type="text" class="pixel-input" placeholder="USERNAME" value="admin" required>
          </div>
          <div class="form-group">
            <input type="password" class="pixel-input" placeholder="PASSWORD" value="admin" required>
          </div>
          <button type="submit" class="pixel-btn" style="width: 100%;">CONNEXION</button>
        </form>
      </div>
    </div>
  </div>

  <!-- APP PRINCIPALE -->
  <div class="app-container hidden" id="appInterface">
    <aside class="sidebar">
      <div class="logo">
        BINARY<br>MINDS
      </div>
      <nav>
        <div class="nav-item active" onclick="switchTab('users', this)">
          [ UTILISATEURS ]
        </div>
        <div class="nav-item" onclick="switchTab('courses', this)">
          [ COURS & TESTS ]
        </div>
      </nav>
      <div style="margin-top: auto; text-align: center; font-size: 14px; color: var(--text-muted);">
        DB: binary_minds
      </div>
    </aside>

    <main class="main-content">
      <header class="header-bar">
        <h2 id="pageTitle" class="page-title">UTILISATEURS</h2>
        <div class="db-badge">STATUS: ONLINE</div>
      </header>

      <!-- USERS VIEW -->
      <section id="view-users" class="view-section">
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between;">
          <div style="font-family: var(--font-title); font-size: 10px; color: var(--text-muted);">
            TABLE: users_records
          </div>
          <button class="pixel-btn success" onclick="openModal('user')">+ AJOUTER USER</button>
        </div>

        <div class="pixel-box pixel-table-container">
          <table class="pixel-table">
            <thead>
              <tr>
                <th>Field (Dossier)</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Type (Role)</th>
                <th>Null (Status)</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="usersTableBody">
              <!-- Rempli par JS -->
            </tbody>
          </table>
        </div>
      </section>

      <!-- COURSES & TESTS VIEW -->
      <section id="view-courses" class="view-section hidden">
        <div style="margin-bottom: 20px; display: flex; justify-content: space-between;">
          <div style="font-family: var(--font-title); font-size: 10px; color: var(--text-muted);">
            TABLE: content_library
          </div>
          <div>
            <button class="pixel-btn success" onclick="openModal('course')">+ AJOUTER COURS</button>
            <button class="pixel-btn" onclick="openModal('test')" style="margin-left: 10px;">+ AJOUTER TEST</button>
          </div>
        </div>

        <div class="pixel-box pixel-table-container">
          <table class="pixel-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Type</th>
                <th>Langage</th>
                <th>Fichier (PDF)</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="contentTableBody">
              <!-- Rempli par JS -->
            </tbody>
          </table>
        </div>
      </section>

    </main>
  </div>

  <!-- MODAL AJOUT -->
  <div class="modal-overlay" id="addModal">
    <div class="modal-box">
      <div class="modal-inner">
        <h3 class="modal-header" id="modalTitle">AJOUTER</h3>
        <form id="addForm" onsubmit="handleFormSubmit(event)">
          <input type="hidden" id="formType">
          
          <!-- Champs Users -->
          <div id="userFields" class="hidden">
            <div class="form-group">
              <label class="form-label">Nom</label>
              <input type="text" id="u_nom" class="pixel-input" required>
            </div>
            <div class="form-group">
              <label class="form-label">Prénom</label>
              <input type="text" id="u_prenom" class="pixel-input" required>
            </div>
            <div class="form-group">
              <label class="form-label">Rôle (Type)</label>
              <select id="u_role" class="pixel-select">
                <option value="user">User</option>
                <option value="admin">Admin</option>
                <option value="editor">Editor</option>
              </select>
            </div>
          </div>

          <!-- Champs Content -->
          <div id="contentFields" class="hidden">
            <div class="form-group">
              <label class="form-label">Titre du contenu</label>
              <input type="text" id="c_title" class="pixel-input" required>
            </div>
            <div class="form-group">
              <label class="form-label">Type</label>
              <select id="c_type" class="pixel-select">
                <option value="course">Cours</option>
                <option value="test">Test</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Langage</label>
              <select id="c_language" class="pixel-select">
                <option value="Java">Java</option>
                <option value="C">C</option>
                <option value="C#">C#</option>
                <option value="Python">Python</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Uploader le fichier (PDF)</label>
              <input type="file" id="c_file" accept=".pdf">
            </div>
          </div>

          <div class="modal-actions">
            <button type="button" class="pixel-btn danger" onclick="closeModal()">ANNULER</button>
            <button type="submit" class="pixel-btn success">VALIDER</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- TOAST -->
  <div id="toast" class="toast">Opération réussie !</div>

  <script>
    /**
     * BINARY_MINDS DATABASE SIMULATION
     */

    const initialUsers = [
      { id: 101, nom: 'Doe', prenom: 'John', role: 'admin', status: 'No' },
      { id: 102, nom: 'Smith', prenom: 'Jane', role: 'editor', status: 'No' },
      { id: 103, nom: 'Martin', prenom: 'Alice', role: 'user', status: 'Yes' },
      { id: 104, nom: 'Dubois', prenom: 'Paul', role: 'user', status: 'No' }
    ];

    const initialContent = [
      { id: 1, title: 'Introduction à Java', type: 'course', language: 'Java', filename: 'java_basics.pdf' },
      { id: 2, title: 'Pointeurs en C', type: 'test', language: 'C', filename: 'c_pointers_quiz.pdf' },
      { id: 3, title: 'Python Data Science', type: 'course', language: 'Python', filename: 'python_ds.pdf' }
    ];

    let dbUsers = JSON.parse(localStorage.getItem('bm_users')) || initialUsers;
    let dbContent = JSON.parse(localStorage.getItem('bm_content')) || initialContent;

    // --- AUTHENTIFICATION ---
    function handleLogin(e) {
      e.preventDefault();
      const screen = document.getElementById('loginScreen');
      const app = document.getElementById('appInterface');
      
      const btn = e.target.querySelector('button');
      const originalText = btn.innerText;
      btn.innerText = "DECRYPTAGE...";
      
      setTimeout(() => {
        screen.classList.add('hidden');
        app.classList.remove('hidden');
        renderUsers();
        renderContent();
      }, 800);
    }

    // --- NAVIGATION ---
    function switchTab(tabName, navEl) {
      document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
      navEl.classList.add('active');

      document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));
      document.getElementById('view-' + tabName).classList.remove('hidden');

      const titles = { 'users': 'UTILISATEURS', 'courses': 'COURS & TESTS' };
      document.getElementById('pageTitle').innerText = titles[tabName];
    }

    // --- RENDERING ---

    function renderUsers() {
      const tbody = document.getElementById('usersTableBody');
      tbody.innerHTML = '';

      dbUsers.forEach(user => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td style="font-family: monospace; color: var(--primary);">#${user.id}</td>
          <td>${user.nom}</td>
          <td>${user.prenom}</td>
          <td><span class="pill ${user.role}">${user.role.toUpperCase()}</span></td>
          <td>${user.status}</td>
          <td>
            <button class="pixel-btn danger sm" onclick="deleteUser(${user.id})">SUPPRIMER</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function renderContent() {
      const tbody = document.getElementById('contentTableBody');
      tbody.innerHTML = '';

      dbContent.forEach(item => {
        const tr = document.createElement('tr');
        const fileDisplay = item.filename ? 
            `<span style="color:var(--success)">[PDF] ${item.filename}</span>` : 
            `<span style="color:var(--text-muted)">Aucun fichier</span>`;

        tr.innerHTML = `
          <td style="font-family: monospace; color: var(--primary);">#${item.id}</td>
          <td>${item.title}</td>
          <td><span class="pill ${item.type}">${item.type.toUpperCase()}</span></td>
          <td><span class="lang-badge">${item.language}</span></td>
          <td>${fileDisplay}</td>
          <td>
            <button class="pixel-btn danger sm" onclick="deleteContent(${item.id})">SUPPRIMER</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    // --- MODAL & FORM ---

    function openModal(type) {
      const modal = document.getElementById('addModal');
      const title = document.getElementById('modalTitle');
      const userFields = document.getElementById('userFields');
      const contentFields = document.getElementById('contentFields');
      const formType = document.getElementById('formType');

      modal.classList.add('open');
      formType.value = type;

      if (type === 'user') {
        title.innerText = "AJOUTER UTILISATEUR";
        userFields.classList.remove('hidden');
        contentFields.classList.add('hidden');
      } else {
        title.innerText = type === 'course' ? "AJOUTER UN COURS" : "AJOUTER UN TEST";
        document.getElementById('c_file').value = ""; 
        
        if(type === 'course') document.getElementById('c_type').value = 'course';
        if(type === 'test') document.getElementById('c_type').value = 'test';

        userFields.classList.add('hidden');
        contentFields.classList.remove('hidden');
      }
    }

    function closeModal() {
      document.getElementById('addModal').classList.remove('open');
      document.getElementById('addForm').reset();
    }

    function handleFormSubmit(e) {
      e.preventDefault();
      const type = document.getElementById('formType').value;

      if (type === 'user') {
        const newUser = {
          id: Math.floor(Math.random() * 10000),
          nom: document.getElementById('u_nom').value,
          prenom: document.getElementById('u_prenom').value,
          role: document.getElementById('u_role').value,
          status: 'No'
        };
        dbUsers.push(newUser);
        localStorage.setItem('bm_users', JSON.stringify(dbUsers));
        renderUsers();
      } else {
        const fileInput = document.getElementById('c_file');
        let filename = "document.pdf"; 
        
        if (fileInput.files && fileInput.files.length > 0) {
            filename = fileInput.files[0].name;
        }

        const newContent = {
          id: Math.floor(Math.random() * 1000),
          title: document.getElementById('c_title').value,
          type: document.getElementById('c_type').value,
          language: document.getElementById('c_language').value,
          filename: filename
        };
        dbContent.push(newContent);
        localStorage.setItem('bm_content', JSON.stringify(dbContent));
        renderContent();
      }

      closeModal();
      showToast("Données ajoutées à binary_minds");
    }

    // --- DELETION ---
    function deleteUser(id) {
      if(confirm("Confirmer la suppression ?")) {
        dbUsers = dbUsers.filter(u => u.id !== id);
        localStorage.setItem('bm_users', JSON.stringify(dbUsers));
        renderUsers();
        showToast("Utilisateur supprimé");
      }
    }

    function deleteContent(id) {
      if(confirm("Supprimer ce contenu définitivement ?")) {
        dbContent = dbContent.filter(c => c.id !== id);
        localStorage.setItem('bm_content', JSON.stringify(dbContent));
        renderContent();
        showToast("Contenu supprimé");
      }
    }

    // --- UTILS ---
    function showToast(msg) {
      const t = document.getElementById('toast');
      t.innerText = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 3000);
    }

  </script>
</body>
</html>