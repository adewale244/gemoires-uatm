<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Créer un compte</title>
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
            <a href="<?= APP_URL ?>/admin/dashboard" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📊 Tableau de bord</a>
            <a href="<?= APP_URL ?>/utilisateur/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm">👥 Utilisateurs</a>
            <a href="<?= APP_URL ?>/memoires/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📚 Mémoires</a>
            <a href="<?= APP_URL ?>/admin/upload" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">⬆️ Uploader mémoire</a>
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
        <div class="bg-white border-b border-gray-200 px-8 py-4 flex items-center gap-4 sticky top-0 z-10">
            <a href="<?= APP_URL ?>/utilisateur/index" class="text-gray-400 hover:text-gray-600 transition-colors">← Retour</a>
            <div>
                <h2 class="text-gray-800 font-semibold text-lg">Créer un compte utilisateur</h2>
                <p class="text-gray-400 text-xs">Le mot de passe par défaut est : uatm2025</p>
            </div>
        </div>

        <div class="p-8 max-w-2xl">

            <?php if (!empty($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg mb-6 text-sm">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl border border-gray-100 p-8">

                <form action="<?= APP_URL ?>/utilisateur/store" method="POST" class="space-y-6">

                    <!-- Nom & Prénom -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="prenom" class="input-field" placeholder="Ex: Adewale" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom" class="input-field" placeholder="Ex: JOHNSON" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email institutionnel <span class="text-red-500">*</span></label>
                        <input type="email" name="email" class="input-field" placeholder="prenom.nom@uatm.bj" required>
                    </div>

                    <!-- Rôle -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rôle <span class="text-red-500">*</span></label>
                        <select name="role" class="input-field bg-white" required onchange="toggleFields(this.value)">
                            <option value="">-- Sélectionner un rôle --</option>
                            <option value="professeur">Professeur</option>
                            <option value="diplome">Étudiant diplômé (L3 / M2)</option>
                            <option value="consultant">Étudiant consultant (L1 / L2 / M1)</option>
                            <option value="directeur">Directeur des études</option>
                            <option value="admin">Administrateur système</option>
                        </select>
                    </div>

                    <!-- Filière & Niveau (pour étudiants) -->
                    <div id="etudiant-fields" class="hidden grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filière</label>
                            <select name="filiere" class="input-field bg-white">
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
                                <option value="">-- Niveau --</option>
                                <option value="L1">Licence 1</option>
                                <option value="L2">Licence 2</option>
                                <option value="L3">Licence 3</option>
                                <option value="M1">Master 1</option>
                                <option value="M2">Master 2</option>
                            </select>
                        </div>
                    </div>

                    <!-- Mot de passe personnalisé -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <input type="password" name="password" class="input-field" placeholder="Laisser vide = uatm2025">
                        <p class="text-xs text-gray-400 mt-1">Si vide, le mot de passe par défaut sera : <strong>uatm2025</strong></p>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="bg-blue-900 hover:bg-yellow-500 hover:text-blue-900 text-white font-semibold px-8 py-3 rounded-xl transition-all text-sm">
                            Créer le compte →
                        </button>
                        <a href="<?= APP_URL ?>/utilisateur/index"
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
function toggleFields(role) {
    const fields = document.getElementById('etudiant-fields');
    if (role === 'diplome' || role === 'consultant') {
        fields.classList.remove('hidden');
        fields.classList.add('grid');
    } else {
        fields.classList.add('hidden');
        fields.classList.remove('grid');
    }
}
</script>
</body>
</html>