<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Soumettre un mémoire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { background: linear-gradient(180deg, #0a1628 0%, #1B3A6B 100%); }
        .nav-item { transition: all 0.2s; border-radius: 10px; }
        .nav-item:hover { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .input-field {
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 11px 14px; font-size: 14px;
            transition: all 0.2s; width: 100%; outline: none;
        }
        .input-field:focus { border-color: #1B3A6B; box-shadow: 0 0 0 3px rgba(27,58,107,0.1); }
        .drop-zone {
            border: 2px dashed #e2e8f0; border-radius: 12px;
            padding: 40px; text-align: center;
            transition: all 0.2s; cursor: pointer;
        }
        .drop-zone:hover, .drop-zone.active { border-color: #1B3A6B; background: #f0f5ff; }
    </style>
</head>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="sidebar w-64 flex-shrink-0 flex flex-col">
        <div class="p-6 border-b border-white border-opacity-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center overflow-hidden">
                    <img src="<?= APP_URL ?>/public/assets/logo_uatm.png" alt="UATM" class="w-9 h-9 object-contain" onerror="this.style.display='none'">
                </div>
                <div>
                    <h1 class="text-yellow-400 font-bold text-lg leading-none">GéMoires</h1>
                    <p class="text-blue-300 text-xs">UATM GASA</p>
                </div>
            </div>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="<?= APP_URL ?>/memoires/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📚 Bibliothèque</a>
            <a href="<?= APP_URL ?>/memoires/soumettre" class="nav-item active flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm" style="background:rgba(200,168,75,0.15)">📤 Soumettre mémoire</a>
        </nav>
        <div class="p-4 border-t border-white border-opacity-10">
            <div class="flex items-center gap-3 px-3 py-2">
                <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center text-blue-900 font-bold text-sm">
                    <?= strtoupper(substr($_SESSION['prenom'], 0, 1)) ?>
                </div>
                <div>
                    <p class="text-white text-sm font-medium"><?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></p>
                    <p class="text-blue-300 text-xs capitalize"><?= $_SESSION['role'] ?></p>
                </div>
            </div>
            <a href="<?= APP_URL ?>/auth/logout" class="mt-2 flex items-center gap-2 px-3 py-2 text-red-400 text-sm nav-item">🚪 Déconnexion</a>
        </div>
    </aside>

    <!-- CONTENU -->
    <main class="flex-1 overflow-y-auto">
        <div class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-10 flex items-center gap-4">
            <a href="<?= APP_URL ?>/memoires/index" class="text-gray-400 hover:text-gray-600">← Retour</a>
            <div>
                <h2 class="text-gray-800 font-semibold text-lg">Soumettre un mémoire</h2>
                <p class="text-gray-400 text-xs">Votre mémoire sera envoyé à votre professeur encadreur pour validation</p>
            </div>
        </div>

        <div class="p-8 max-w-2xl">

            <!-- Info box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 px-5 py-4 rounded-r-xl mb-8">
                <p class="text-blue-800 text-sm font-medium mb-1">📋 Comment ça marche ?</p>
                <ol class="text-blue-700 text-xs space-y-1 list-decimal list-inside">
                    <li>Remplissez les informations de votre mémoire</li>
                    <li>Uploadez votre fichier (PDF recommandé)</li>
                    <li>Choisissez votre professeur encadreur</li>
                    <li>Il recevra une notification et validera ou rejettera</li>
                    <li>Si validé, votre mémoire sera visible sur la plateforme</li>
                </ol>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-8">
                <form action="<?= APP_URL ?>/memoires/store" method="POST" enctype="multipart/form-data" class="space-y-6">

                    <!-- Titre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titre du mémoire <span class="text-red-500">*</span></label>
                        <input type="text" name="titre" class="input-field"
                            placeholder="Ex: Conception d'un système de gestion..." required>
                    </div>

                    <!-- Résumé -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Résumé</label>
                        <textarea name="resume" rows="4" class="input-field resize-none"
                            placeholder="Décrivez brièvement votre mémoire..."></textarea>
                    </div>

                    <!-- Filière & Niveau -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filière <span class="text-red-500">*</span></label>
                            <select name="filiere" class="input-field bg-white" required>
                                <option value="">-- Filière --</option>
                                <option value="Génie Électrique">Génie Électrique</option>
                                <option value="SIL">SIL</option>
                                <option value="Génie Civil">Génie Civil</option>
                                <option value="Informatique">Informatique</option>
                                <option value="Management">Management</option>
                                <option value="Finance">Finance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Niveau <span class="text-red-500">*</span></label>
                            <select name="niveau" class="input-field bg-white" required>
                                <option value="">-- Niveau --</option>
                                <option value="licence">Licence 3</option>
                                <option value="master">Master 2</option>
                            </select>
                        </div>
                    </div>

                    <!-- Année académique -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Année académique</label>
                        <input type="text" name="annee_academique" class="input-field"
                            placeholder="Ex: 2024-2025">
                    </div>

                    <!-- Professeur encadreur -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Professeur encadreur <span class="text-red-500">*</span></label>
                        <select name="id_professeur" class="input-field bg-white" required>
                            <option value="">-- Choisir un professeur --</option>
                            <?php foreach($profs as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Upload fichier -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fichier mémoire <span class="text-red-500">*</span></label>
                        <div class="drop-zone" id="dropZone" onclick="document.getElementById('fichier').click()">
                            <div id="drop-content">
                                <div class="text-4xl mb-3">📄</div>
                                <p class="text-gray-600 font-medium text-sm">Cliquez ou glissez votre fichier ici</p>
                                <p class="text-gray-400 text-xs mt-1">PDF, DOC, DOCX · Max 20MB</p>
                            </div>
                            <div id="file-selected" class="hidden">
                                <div class="text-4xl mb-2">✅</div>
                                <p class="text-green-600 font-medium text-sm" id="file-name"></p>
                            </div>
                        </div>
                        <input type="file" id="fichier" name="fichier" accept=".pdf,.doc,.docx" class="hidden" required>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="bg-blue-900 hover:bg-yellow-500 hover:text-blue-900 text-white font-semibold px-8 py-3 rounded-xl transition-all text-sm">
                            Soumettre le mémoire →
                        </button>
                        <a href="<?= APP_URL ?>/memoires/index"
                            class="border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium px-6 py-3 rounded-xl transition-all text-sm">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </main>
</div>

<script>
const input = document.getElementById('fichier');
const dropZone = document.getElementById('dropZone');

input.addEventListener('change', function() {
    if (this.files[0]) showFile(this.files[0].name);
});

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('active'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('active'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('active');
    if (e.dataTransfer.files[0]) {
        input.files = e.dataTransfer.files;
        showFile(e.dataTransfer.files[0].name);
    }
});

function showFile(name) {
    document.getElementById('drop-content').classList.add('hidden');
    document.getElementById('file-selected').classList.remove('hidden');
    document.getElementById('file-name').textContent = name;
}
</script>
</body>
</html>