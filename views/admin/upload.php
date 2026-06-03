<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Upload mémoires</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { background: linear-gradient(180deg, #0a1628 0%, #1B3A6B 100%); }
        .nav-item { transition: all 0.2s; border-radius: 10px; }
        .nav-item:hover { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .nav-item.active { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .input-field {
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 11px 14px; font-size: 14px;
            transition: all 0.2s; width: 100%; outline: none;
        }
        .input-field:focus { border-color: #1B3A6B; box-shadow: 0 0 0 3px rgba(27,58,107,0.1); }
        .drop-zone {
            border: 2px dashed #e2e8f0; border-radius: 12px;
            padding: 30px; text-align: center;
            transition: all 0.2s; cursor: pointer;
        }
        .drop-zone:hover { border-color: #1B3A6B; background: #f0f5ff; }
        .tab-btn { transition: all 0.2s; }
        .tab-btn.active { background: #1B3A6B; color: white; }
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
            <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 mb-3">Navigation</p>
            <a href="<?= APP_URL ?>/admin/dashboard" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📊 Dashboard</a>
            <a href="<?= APP_URL ?>/utilisateur/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">👥 Utilisateurs</a>
            <a href="<?= APP_URL ?>/memoires/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📚 Mémoires</a>
            <a href="<?= APP_URL ?>/upload/index" class="nav-item active flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm">⬆️ Uploader mémoire</a>
        </nav>
        <div class="p-4 border-t border-white border-opacity-10">
            <div class="flex items-center gap-3 px-3 py-2">
                <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center text-blue-900 font-bold text-sm">
                    <?= strtoupper(substr($_SESSION['prenom'], 0, 1)) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate"><?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></p>
                    <p class="text-blue-300 text-xs capitalize"><?= $_SESSION['role'] ?></p>
                </div>
            </div>
            <a href="<?= APP_URL ?>/auth/logout" class="mt-2 flex items-center gap-2 px-3 py-2 text-red-400 text-sm nav-item">🚪 Déconnexion</a>
        </div>
    </aside>

    <!-- CONTENU -->
    <main class="flex-1 overflow-y-auto">
        <div class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-10">
            <h2 class="text-gray-800 font-semibold text-lg">⬆️ Upload de mémoires</h2>
            <p class="text-gray-400 text-xs">Ajouter des anciens mémoires à la bibliothèque</p>
        </div>

        <div class="p-8 max-w-3xl">

            <!-- Alerts -->
            <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-xl mb-6 text-sm">
                ✅ Mémoire uploadé avec succès !
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['success_masse'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-xl mb-6 text-sm">
                ✅ <?= $_GET['success_masse'] ?> mémoire(s) importé(s) avec succès !
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['error'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-xl mb-6 text-sm">
                ❌ Erreur lors de l'import. Vérifiez le format du fichier.
            </div>
            <?php endif; ?>

            <!-- Tabs -->
            <div class="flex gap-2 mb-6">
                <button onclick="showTab('unitaire')" id="tab-unitaire"
                    class="tab-btn active px-5 py-2.5 rounded-xl text-sm font-medium border border-gray-200">
                    📄 Upload unitaire
                </button>
                <button onclick="showTab('masse')" id="tab-masse"
                    class="tab-btn px-5 py-2.5 rounded-xl text-sm font-medium border border-gray-200 text-gray-600">
                    📊 Import Excel (masse)
                </button>
            </div>

            <!-- TAB UNITAIRE -->
            <div id="tab-unitaire-content">
                <div class="bg-white rounded-2xl border border-gray-100 p-8">
                    <h3 class="font-semibold text-gray-800 mb-6">Ajouter un mémoire ancien</h3>

                    <form action="<?= APP_URL ?>/upload/store" method="POST" enctype="multipart/form-data" class="space-y-5">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Titre <span class="text-red-500">*</span></label>
                            <input type="text" name="titre" class="input-field" placeholder="Titre du mémoire" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Auteur (Prénom Nom)</label>
                            <input type="text" name="auteur" class="input-field" placeholder="Ex: Adewale JOHNSON">
                        </div>

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
                                <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                                <select name="niveau" class="input-field bg-white">
                                    <option value="licence">Licence 3</option>
                                    <option value="master">Master 2</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Année académique</label>
                                <input type="text" name="annee_academique" class="input-field" placeholder="Ex: 2022-2023">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Professeur encadreur</label>
                                <select name="id_professeur" class="input-field bg-white">
                                    <option value="">-- Aucun --</option>
                                    <?php foreach($profs as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Résumé</label>
                            <textarea name="resume" rows="3" class="input-field resize-none" placeholder="Résumé du mémoire..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fichier (PDF, DOC, DOCX)</label>
                            <div class="drop-zone" onclick="document.getElementById('fichier').click()">
                                <div id="drop-content-1">
                                    <div class="text-3xl mb-2">📄</div>
                                    <p class="text-gray-500 text-sm">Cliquez pour choisir un fichier</p>
                                    <p class="text-gray-400 text-xs mt-1">PDF, DOC, DOCX · Max 20MB</p>
                                </div>
                                <p id="file-name-1" class="text-green-600 text-sm font-medium hidden"></p>
                            </div>
                            <input type="file" id="fichier" name="fichier" accept=".pdf,.doc,.docx" class="hidden">
                        </div>

                        <button type="submit"
                            class="bg-blue-900 hover:bg-yellow-500 hover:text-blue-900 text-white font-semibold px-8 py-3 rounded-xl transition-all text-sm">
                            ⬆️ Uploader le mémoire →
                        </button>
                    </form>
                </div>
            </div>

            <!-- TAB MASSE -->
            <div id="tab-masse-content" class="hidden">
                <div class="bg-white rounded-2xl border border-gray-100 p-8">
                    <h3 class="font-semibold text-gray-800 mb-2">Import massif depuis Excel</h3>
                    <p class="text-gray-500 text-sm mb-6">Importez plusieurs mémoires en une seule fois depuis un fichier Excel.</p>

                    <!-- Format attendu -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                        <p class="text-blue-800 text-sm font-medium mb-2">📋 Format du fichier Excel attendu :</p>
                        <div class="overflow-x-auto">
                            <table class="text-xs w-full">
                                <thead>
                                    <tr class="bg-blue-100">
                                        <th class="px-3 py-2 text-left text-blue-700">Colonne A</th>
                                        <th class="px-3 py-2 text-left text-blue-700">Colonne B</th>
                                        <th class="px-3 py-2 text-left text-blue-700">Colonne C</th>
                                        <th class="px-3 py-2 text-left text-blue-700">Colonne D</th>
                                        <th class="px-3 py-2 text-left text-blue-700">Colonne E</th>
                                        <th class="px-3 py-2 text-left text-blue-700">Colonne F</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-blue-100">
                                        <td class="px-3 py-2 text-blue-600">Titre</td>
                                        <td class="px-3 py-2 text-blue-600">Auteur</td>
                                        <td class="px-3 py-2 text-blue-600">Filière</td>
                                        <td class="px-3 py-2 text-blue-600">Niveau</td>
                                        <td class="px-3 py-2 text-blue-600">Année</td>
                                        <td class="px-3 py-2 text-blue-600">Résumé</td>
                                    </tr>
                                    <tr class="border-t border-blue-100 bg-white">
                                        <td class="px-3 py-2 text-gray-500">Conception d'un...</td>
                                        <td class="px-3 py-2 text-gray-500">Adewale JOHNSON</td>
                                        <td class="px-3 py-2 text-gray-500">SIL</td>
                                        <td class="px-3 py-2 text-gray-500">licence</td>
                                        <td class="px-3 py-2 text-gray-500">2022-2023</td>
                                        <td class="px-3 py-2 text-gray-500">Ce mémoire...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-blue-600 text-xs mt-2">⚠️ La première ligne doit être les en-têtes (elle sera ignorée à l'import)</p>
                    </div>

                    <form action="<?= APP_URL ?>/upload/masse" method="POST" enctype="multipart/form-data" class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fichier Excel <span class="text-red-500">*</span></label>
                            <div class="drop-zone" onclick="document.getElementById('excel').click()">
                                <div id="drop-content-2">
                                    <div class="text-3xl mb-2">📊</div>
                                    <p class="text-gray-500 text-sm">Cliquez pour choisir votre fichier Excel</p>
                                    <p class="text-gray-400 text-xs mt-1">XLSX, XLS, CSV acceptés</p>
                                </div>
                                <p id="file-name-2" class="text-green-600 text-sm font-medium hidden"></p>
                            </div>
                            <input type="file" id="excel" name="excel" accept=".xlsx,.xls,.csv" class="hidden" required>
                        </div>

                        <button type="submit"
                            class="bg-blue-900 hover:bg-yellow-500 hover:text-blue-900 text-white font-semibold px-8 py-3 rounded-xl transition-all text-sm">
                            📊 Importer les mémoires →
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function showTab(tab) {
    document.getElementById('tab-unitaire-content').classList.add('hidden');
    document.getElementById('tab-masse-content').classList.add('hidden');
    document.getElementById('tab-unitaire').classList.remove('active');
    document.getElementById('tab-masse').classList.remove('active');
    document.getElementById('tab-' + tab + '-content').classList.remove('hidden');
    document.getElementById('tab-' + tab).classList.add('active');
}

document.getElementById('fichier').addEventListener('change', function() {
    if (this.files[0]) {
        document.getElementById('drop-content-1').classList.add('hidden');
        document.getElementById('file-name-1').classList.remove('hidden');
        document.getElementById('file-name-1').textContent = '✅ ' + this.files[0].name;
    }
});

document.getElementById('excel').addEventListener('change', function() {
    if (this.files[0]) {
        document.getElementById('drop-content-2').classList.add('hidden');
        document.getElementById('file-name-2').classList.remove('hidden');
        document.getElementById('file-name-2').textContent = '✅ ' + this.files[0].name;
    }
});
</script>
</body>
</html>