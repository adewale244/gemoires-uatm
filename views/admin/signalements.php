<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Signalements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { background: linear-gradient(180deg, #0a1628 0%, #1B3A6B 100%); }
        .nav-item { transition: all 0.2s; border-radius: 10px; }
        .nav-item:hover { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .nav-item.active { background: rgba(200,168,75,0.15); color: #C8A84B; }
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
            <a href="<?= APP_URL ?>/upload/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">⬆️ Upload</a>
            <a href="<?= APP_URL ?>/admin/signalements" class="nav-item active flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm">🚩 Signalements</a>
            <a href="<?= APP_URL ?>/admin/stats" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📈 Statistiques</a>
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
                <h2 class="text-gray-800 font-semibold text-lg">🚩 Modération des signalements</h2>
                <p class="text-gray-400 text-xs"><?= count($signalements) ?> signalement(s) au total</p>
            </div>
            <?php include 'views/partials/navbar.php'; ?>
        </div>

        <div class="p-8">

            <?php if(empty($signalements)): ?>
            <div class="text-center py-20">
                <div class="text-7xl mb-4">✅</div>
                <h3 class="text-gray-500 font-medium text-lg mb-2">Aucun signalement</h3>
                <p class="text-gray-400 text-sm">La communauté est respectueuse !</p>
            </div>

            <?php else: ?>
            <div class="space-y-4">
                <?php foreach($signalements as $s): ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-6
                    <?= $s['statut'] === 'traite' ? 'opacity-60' : 'border-l-4 border-l-red-400' ?>">

                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <!-- Commentaire signalé -->
                            <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-4">
                                <p class="text-xs text-red-500 font-medium mb-1">
                                    💬 Commentaire signalé — par <?= htmlspecialchars($s['auteur_prenom'] . ' ' . $s['auteur_nom']) ?>
                                </p>
                                <p class="text-sm text-gray-700"><?= htmlspecialchars($s['commentaire_contenu']) ?></p>
                            </div>

                            <!-- Infos signalement -->
                            <div class="flex items-center gap-4 text-xs text-gray-400">
                                <span>🚩 Signalé par : <strong><?= htmlspecialchars($s['signaleur_prenom'] . ' ' . $s['signaleur_nom']) ?></strong></span>
                                <span>📅 <?= date('d/m/Y à H:i', strtotime($s['date_signalement'])) ?></span>
                                <span class="px-2 py-1 rounded-full <?= $s['statut'] === 'traite' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= $s['statut'] === 'traite' ? '✅ Traité' : '⏳ En attente' ?>
                                </span>
                            </div>

                            <?php if($s['motif']): ?>
                            <p class="text-xs text-gray-500 mt-2">
                                📝 Motif : <?= htmlspecialchars($s['motif']) ?>
                            </p>
                            <?php endif; ?>
                        </div>

                        <!-- Actions -->
                        <?php if($s['statut'] !== 'traite'): ?>
                        <div class="flex flex-col gap-2 flex-shrink-0">
                            <form method="POST" action="<?= APP_URL ?>/admin/traiterSignalement/<?= $s['id'] ?>">
                                <input type="hidden" name="action" value="supprimer">
                                <button type="submit"
                                    onclick="return confirm('Supprimer ce commentaire ?')"
                                    class="w-full text-xs px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all">
                                    🗑 Supprimer commentaire
                                </button>
                            </form>
                            <form method="POST" action="<?= APP_URL ?>/admin/traiterSignalement/<?= $s['id'] ?>">
                                <input type="hidden" name="action" value="ignorer">
                                <button type="submit"
                                    class="w-full text-xs px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-all">
                                    ✓ Ignorer
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>