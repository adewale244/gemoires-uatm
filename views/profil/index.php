<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Mon profil</title>
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
            <?php if(in_array($_SESSION['role'], ['admin','directeur'])): ?>
            <a href="<?= APP_URL ?>/admin/dashboard" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📊 Dashboard</a>
            <a href="<?= APP_URL ?>/utilisateur/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">👥 Utilisateurs</a>
            <?php endif; ?>
            <?php if($_SESSION['role'] === 'professeur'): ?>
            <a href="<?= APP_URL ?>/professeur/dashboard" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">✅ Mes validations</a>
            <?php endif; ?>
            <a href="<?= APP_URL ?>/memoires/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📚 Bibliothèque</a>
            <a href="<?= APP_URL ?>/notification/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">🔔 Notifications</a>
            <a href="<?= APP_URL ?>/profil/index" class="nav-item active flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm">👤 Mon profil</a>
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
        <div class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-10 flex items-center justify-between">
            <div>
                <h2 class="text-gray-800 font-semibold text-lg">👤 Mon profil</h2>
                <p class="text-gray-400 text-xs">Gérez vos informations personnelles</p>
            </div>
            <?php include 'views/partials/navbar.php'; ?>
        </div>

        <div class="p-8 max-w-2xl">

            <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-xl mb-6 text-sm">
                ✅ Profil mis à jour avec succès !
            </div>
            <?php endif; ?>

            <?php if(!empty($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-xl mb-6 text-sm">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <!-- Avatar + infos -->
            <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-2xl p-6 mb-6 flex items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-yellow-400 flex items-center justify-center text-blue-900 font-bold text-3xl flex-shrink-0">
                    <?= strtoupper(substr($user['prenom'], 0, 1)) ?>
                </div>
                <div>
                    <h3 class="text-white text-xl font-bold"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h3>
                    <p class="text-blue-200 text-sm capitalize"><?= $user['role'] ?> — <?= $user['filiere'] ?? 'UATM GASA' ?></p>
                    <p class="text-blue-300 text-xs mt-1"><?= htmlspecialchars($user['email']) ?></p>
                    <span class="inline-block mt-2 text-xs bg-green-500 text-white px-3 py-1 rounded-full">
                        ● Compte actif
                    </span>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="bg-white rounded-2xl border border-gray-100 p-8">
                <h3 class="font-semibold text-gray-800 mb-6">Modifier mes informations</h3>

                <form action="<?= APP_URL ?>/profil/update" method="POST" class="space-y-5">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                            <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" class="input-field" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                            <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" class="input-field" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="input-field" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filière</label>
                        <select name="filiere" class="input-field bg-white">
                            <option value="">-- Filière --</option>
                            <?php foreach(['Génie Électrique','SIL','Génie Civil','Informatique','Management','Finance'] as $f): ?>
                            <option value="<?= $f ?>" <?= $user['filiere'] === $f ? 'selected' : '' ?>><?= $f ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="border-t border-gray-100 pt-5">
                        <h4 class="font-medium text-gray-700 mb-4">🔒 Changer le mot de passe</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
                                <input type="password" name="current_password" class="input-field" placeholder="Laissez vide si pas de changement">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                                <input type="password" name="new_password" class="input-field" placeholder="Minimum 6 caractères">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="bg-blue-900 hover:bg-yellow-500 hover:text-blue-900 text-white font-semibold px-8 py-3 rounded-xl transition-all text-sm">
                            Sauvegarder →
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
</body>
</html>