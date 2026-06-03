<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Statistiques</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { background: linear-gradient(180deg, #0a1628 0%, #1B3A6B 100%); }
        .nav-item { transition: all 0.2s; border-radius: 10px; }
        .nav-item:hover { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .nav-item.active { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .stat-card { transition: all 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
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
            <a href="<?= APP_URL ?>/admin/stats" class="nav-item active flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm">📈 Statistiques</a>
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
                <h2 class="text-gray-800 font-semibold text-lg">📈 Statistiques</h2>
                <p class="text-gray-400 text-xs">Vue globale de la plateforme</p>
            </div>
            <?php include 'views/partials/navbar.php'; ?>
        </div>

        <div class="p-8">

            <!-- Stats mémoires -->
            <h3 class="text-gray-700 font-semibold mb-4">📚 Mémoires</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-blue-900"><?= $total_memoires ?></p>
                    <p class="text-gray-500 text-xs mt-1">Total</p>
                </div>
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-green-600"><?= $total_valides ?></p>
                    <p class="text-gray-500 text-xs mt-1">Validés</p>
                </div>
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-yellow-500"><?= $total_en_attente ?></p>
                    <p class="text-gray-500 text-xs mt-1">En attente</p>
                </div>
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-red-500"><?= $total_rejetes ?></p>
                    <p class="text-gray-500 text-xs mt-1">Rejetés</p>
                </div>
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-purple-600"><?= $total_anciens ?></p>
                    <p class="text-gray-500 text-xs mt-1">Anciens</p>
                </div>
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-indigo-600"><?= $total_nouveaux ?></p>
                    <p class="text-gray-500 text-xs mt-1">Nouveaux</p>
                </div>
            </div>

            <!-- Stats utilisateurs -->
            <h3 class="text-gray-700 font-semibold mb-4">👥 Utilisateurs</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-blue-900"><?= $total_users ?></p>
                    <p class="text-gray-500 text-xs mt-1">Total comptes</p>
                </div>
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-green-600"><?= $total_profs ?></p>
                    <p class="text-gray-500 text-xs mt-1">Professeurs</p>
                </div>
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-yellow-500"><?= $total_diplomes ?></p>
                    <p class="text-gray-500 text-xs mt-1">Diplômés</p>
                </div>
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-purple-600"><?= $total_consultants ?></p>
                    <p class="text-gray-500 text-xs mt-1">Consultants</p>
                </div>
            </div>

            <!-- Stats interactions -->
            <h3 class="text-gray-700 font-semibold mb-4">💬 Interactions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-blue-500"><?= $total_consultations ?></p>
                    <p class="text-gray-500 text-xs mt-1">Consultations</p>
                </div>
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-red-500"><?= $total_likes ?></p>
                    <p class="text-gray-500 text-xs mt-1">Likes</p>
                </div>
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-green-500"><?= $total_comments ?></p>
                    <p class="text-gray-500 text-xs mt-1">Commentaires</p>
                </div>
                <div class="stat-card bg-white rounded-2xl border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-yellow-500"><?= $total_favoris ?></p>
                    <p class="text-gray-500 text-xs mt-1">Favoris</p>
                </div>
            </div>

            <!-- Graphiques + Top -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                <!-- Graphique par filière -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">📁 Mémoires par filière</h3>
                    <?php if(!empty($par_filiere)): ?>
                    <canvas id="chartFiliere" height="200"></canvas>
                    <?php else: ?>
                    <p class="text-gray-400 text-sm text-center py-8">Pas encore de données</p>
                    <?php endif; ?>
                </div>

                <!-- Top mémoires -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">🏆 Top mémoires les plus consultés</h3>
                    <?php if(empty($top_memoires)): ?>
                    <p class="text-gray-400 text-sm text-center py-8">Pas encore de données</p>
                    <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach($top_memoires as $i => $m): ?>
                        <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm flex-shrink-0
                                <?= $i === 0 ? 'bg-yellow-400 text-yellow-900' :
                                   ($i === 1 ? 'bg-gray-300 text-gray-700' :
                                   ($i === 2 ? 'bg-orange-300 text-orange-900' : 'bg-blue-100 text-blue-700')) ?>">
                                <?= $i + 1 ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($m['titre']) ?></p>
                                <p class="text-xs text-gray-400"><?= htmlspecialchars($m['filiere'] ?? '—') ?></p>
                            </div>
                            <div class="flex gap-3 text-xs text-gray-400 flex-shrink-0">
                                <span>👁 <?= $m['vues'] ?></span>
                                <span>❤️ <?= $m['likes'] ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
<?php if(!empty($par_filiere)): ?>
const ctx = document.getElementById('chartFiliere').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [<?= implode(',', array_map(fn($f) => '"' . addslashes($f['filiere']) . '"', $par_filiere)) ?>],
        datasets: [{
            data: [<?= implode(',', array_column($par_filiere, 'total')) ?>],
            backgroundColor: ['#1B3A6B','#C8A84B','#3a8a5a','#c84a4a','#9a70d0','#5ab8ea'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 12 } } }
        }
    }
});
<?php endif; ?>
</script>
</body>
</html>