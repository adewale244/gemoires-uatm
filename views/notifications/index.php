<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { background: linear-gradient(180deg, #0a1628 0%, #1B3A6B 100%); }
        .nav-item { transition: all 0.2s; border-radius: 10px; }
        .nav-item:hover { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .nav-item.active { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .notif-card { transition: all 0.2s; }
        .notif-card:hover { background: #f8faff; }
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
            <a href="<?= APP_URL ?>/notification/index" class="nav-item active flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm">🔔 Notifications</a>
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

        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-10 flex items-center justify-between">
            <div>
                <h2 class="text-gray-800 font-semibold text-lg">🔔 Notifications</h2>
                <p class="text-gray-400 text-xs"><?= count($notifications) ?> notification(s) au total</p>
            </div>
            <?php if(!empty($notifications)): ?>
            <a href="<?= APP_URL ?>/notification/index"
               class="text-xs text-blue-600 hover:underline">
                Tout marquer comme lu ✓
            </a>
            <?php endif; ?>
        </div>

        <div class="p-8 max-w-3xl">

            <?php if(empty($notifications)): ?>
            <!-- Empty state -->
            <div class="text-center py-20">
                <div class="text-7xl mb-4">🔕</div>
                <h3 class="text-gray-500 font-medium text-lg mb-2">Aucune notification</h3>
                <p class="text-gray-400 text-sm">Vous êtes à jour !</p>
            </div>

            <?php else: ?>

            <!-- Liste notifications -->
            <div class="space-y-3">
                <?php foreach($notifications as $n): ?>
                <div class="notif-card bg-white rounded-2xl border border-gray-100 p-5 flex items-start gap-4
                    <?= !$n['lu'] ? 'border-l-4 border-l-blue-500' : '' ?>">

                    <!-- Icône selon message -->
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0
                        <?= str_contains($n['message'], 'validé') ? 'bg-green-100' :
                           (str_contains($n['message'], 'rejeté') ? 'bg-red-100' :
                           (str_contains($n['message'], 'correction') ? 'bg-yellow-100' : 'bg-blue-100')) ?>">
                        <?= str_contains($n['message'], 'validé') ? '✅' :
                           (str_contains($n['message'], 'rejeté') ? '❌' :
                           (str_contains($n['message'], 'correction') ? '⚠️' : '📬')) ?>
                    </div>

                    <!-- Contenu -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 leading-relaxed"><?= htmlspecialchars($n['message']) ?></p>
                        <div class="flex items-center gap-3 mt-2">
                            <p class="text-xs text-gray-400">
                                🕐 <?= date('d/m/Y à H:i', strtotime($n['date_notification'])) ?>
                            </p>
                            <?php if(!$n['lu']): ?>
                            <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">Nouveau</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Supprimer -->
                    <a href="<?= APP_URL ?>/notification/supprimer/<?= $n['id'] ?>"
                       class="text-gray-300 hover:text-red-400 transition-colors flex-shrink-0 text-lg"
                       title="Supprimer">✕</a>
                </div>
                <?php endforeach; ?>
            </div>

            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>