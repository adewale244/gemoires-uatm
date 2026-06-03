<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { background: linear-gradient(180deg, #0a1628 0%, #1B3A6B 100%); }
        .nav-item { transition: all 0.2s; border-radius: 10px; }
        .nav-item:hover, .nav-item.active { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .gold { color: #C8A84B; }
        .bg-navy { background: #1B3A6B; }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen overflow-hidden">

    <!-- ═══ SIDEBAR ═══ -->
    <aside class="sidebar w-64 flex-shrink-0 flex flex-col">

        <!-- Logo -->
        <div class="p-6 border-b border-white border-opacity-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center overflow-hidden">
                    <img src="<?= APP_URL ?>/public/assets/logo_uatm.png" alt="UATM" class="w-9 h-9 object-contain"
                         onerror="this.style.display='none'">
                </div>
                <div>
                    <h1 class="text-yellow-400 font-bold text-lg leading-none">GéMoires</h1>
                    <p class="text-blue-300 text-xs">UATM GASA</p>
                </div>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 p-4 space-y-1">
            <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 mb-3">Navigation</p>

            <a href="<?= APP_URL ?>/admin/dashboard" class="nav-item active flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm">
                <span>📊</span> Tableau de bord
            </a>
            <a href="<?= APP_URL ?>/utilisateur/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">
                <span>👥</span> Utilisateurs
            </a>
            <a href="<?= APP_URL ?>/memoires/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">
                <span>📚</span> Mémoires
            </a>
            <a href="<?= APP_URL ?>/admin/upload" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">
                <span>⬆️</span> Uploader mémoire
            </a>
            <a href="<?= APP_URL ?>/admin/signalements" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">
                <span>🚩</span> Signalements
            </a>

            <div class="border-t border-white border-opacity-10 my-3"></div>
            <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 mb-3">Système</p>

            <a href="<?= APP_URL ?>/admin/config" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">
                <span>⚙️</span> Configuration
            </a>
            <a href="<?= APP_URL ?>/admin/stats" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">
                <span>📈</span> Statistiques
            </a>
        </nav>

        <!-- User -->
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
            <a href="<?= APP_URL ?>/auth/logout" class="mt-2 flex items-center gap-2 px-3 py-2 text-red-400 hover:text-red-300 text-sm nav-item">
                <span>🚪</span> Déconnexion
            </a>
        </div>
    </aside>

    <!-- ═══ CONTENU PRINCIPAL ═══ -->
    <main class="flex-1 overflow-y-auto">

        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h2 class="text-gray-800 font-semibold text-lg">Tableau de bord</h2>
                <p class="text-gray-400 text-xs"><?= date('l d F Y') ?></p>
            </div>
            <div class="flex items-center gap-3">
               <?php include 'views/partials/navbar.php'; ?>
            </div>
        </div>

        <div class="p-8">

            <!-- Bienvenue -->
            <div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
                <div class="absolute right-6 top-1/2 -translate-y-1/2 text-8xl opacity-10">📚</div>
                <p class="text-blue-200 text-sm mb-1">Bienvenue,</p>
                <h3 class="text-2xl font-bold mb-1"><?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></h3>
                <p class="text-blue-200 text-sm capitalize">Rôle : <?= $_SESSION['role'] ?> — GéMoires UATM</p>
            </div>

            <!-- Stats cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-2xl">📚</div>
                        <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Total</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_memoires ?></p>
                    <p class="text-gray-500 text-sm mt-1">Mémoires</p>
                </div>

                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-2xl">✅</div>
                        <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">Validés</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_valides ?></p>
                    <p class="text-gray-500 text-sm mt-1">Mémoires validés</p>
                </div>

                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-2xl">⏳</div>
                        <span class="text-xs text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">En attente</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800"><?= $en_attente ?></p>
                    <p class="text-gray-500 text-sm mt-1">En attente validation</p>
                </div>

                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-2xl">👥</div>
                        <span class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Comptes</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_users ?></p>
                    <p class="text-gray-500 text-sm mt-1">Utilisateurs actifs</p>
                </div>

            </div>

            <!-- Tableaux -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                <!-- Derniers mémoires -->
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Derniers mémoires déposés</h3>
                        <a href="<?= APP_URL ?>/memoires/index" class="text-xs text-blue-600 hover:underline">Voir tout →</a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        <?php if (empty($derniers_memoires)): ?>
                        <p class="text-gray-400 text-sm text-center py-8">Aucun mémoire pour l'instant</p>
                        <?php else: ?>
                        <?php foreach ($derniers_memoires as $m): ?>
                        <div class="px-6 py-4 flex items-center gap-4">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-lg flex-shrink-0">📄</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($m['titre']) ?></p>
                                <p class="text-xs text-gray-400"><?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?> — <?= $m['filiere'] ?></p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full flex-shrink-0
                                <?= $m['statut'] === 'valide' ? 'bg-green-100 text-green-700' :
                                   ($m['statut'] === 'en_attente' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?>">
                                <?= ucfirst($m['statut']) ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Derniers utilisateurs -->
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Derniers comptes créés</h3>
                        <a href="<?= APP_URL ?>/admin/utilisateurs" class="text-xs text-blue-600 hover:underline">Voir tout →</a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        <?php if (empty($derniers_users)): ?>
                        <p class="text-gray-400 text-sm text-center py-8">Aucun utilisateur pour l'instant</p>
                        <?php else: ?>
                        <?php foreach ($derniers_users as $u): ?>
                        <div class="px-6 py-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-900 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                <?= strtoupper(substr($u['prenom'], 0, 1)) ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800"><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></p>
                                <p class="text-xs text-gray-400"><?= htmlspecialchars($u['email']) ?></p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700 capitalize flex-shrink-0">
                                <?= $u['role'] ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

</body>
</html>