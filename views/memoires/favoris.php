<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Mes favoris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { background: linear-gradient(180deg, #0a1628 0%, #1B3A6B 100%); }
        .nav-item { transition: all 0.2s; border-radius: 10px; }
        .nav-item:hover { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .nav-item.active { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .card { transition: all 0.25s; }
        .card:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
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
            <?php if(in_array($_SESSION['role'], ['diplome','directeur','admin'])): ?>
            <a href="<?= APP_URL ?>/memoires/soumettre" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📤 Soumettre</a>
            <?php endif; ?>
            <a href="<?= APP_URL ?>/memoires/favoris" class="nav-item active flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm">⭐ Mes favoris</a>
            <a href="<?= APP_URL ?>/notification/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">🔔 Notifications</a>
            <a href="<?= APP_URL ?>/profil/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">👤 Mon profil</a>
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
                <h2 class="text-gray-800 font-semibold text-lg">⭐ Mes favoris</h2>
                <p class="text-gray-400 text-xs"><?= count($favoris) ?> mémoire(s) en favoris</p>
            </div>
            <?php include 'views/partials/navbar.php'; ?>
        </div>

        <div class="p-8">
            <?php if(empty($favoris)): ?>
            <div class="text-center py-20">
                <div class="text-7xl mb-4">⭐</div>
                <h3 class="text-gray-500 font-medium text-lg mb-2">Aucun favori pour l'instant</h3>
                <p class="text-gray-400 text-sm mb-6">Ajoutez des mémoires en favoris depuis la bibliothèque.</p>
                <a href="<?= APP_URL ?>/memoires/index"
                   class="bg-blue-900 text-white px-6 py-3 rounded-xl text-sm font-medium hover:bg-yellow-500 hover:text-blue-900 transition-all">
                    Parcourir la bibliothèque →
                </a>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                <?php foreach($favoris as $m): ?>
                <div class="card bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 p-5 relative">
                        <div class="absolute top-4 right-4 text-3xl opacity-20">⭐</div>
                        <span class="text-xs bg-white bg-opacity-20 text-white px-2 py-1 rounded-full mb-3 inline-block">
                            <?= ucfirst($m['niveau']) ?>
                        </span>
                        <h3 class="text-white font-semibold text-sm leading-snug line-clamp-2 pr-8">
                            <?= htmlspecialchars($m['titre']) ?>
                        </h3>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-7 h-7 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-800 font-bold text-xs">
                                <?= strtoupper(substr($m['prenom'] ?? 'I', 0, 1)) ?>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-700"><?= htmlspecialchars(($m['prenom'] ?? '') . ' ' . ($m['nom'] ?? '')) ?></p>
                                <p class="text-xs text-gray-400"><?= htmlspecialchars($m['filiere'] ?? '—') ?> · <?= $m['annee_academique'] ?? '—' ?></p>
                            </div>
                        </div>

                        <?php if($m['resume']): ?>
                        <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 mb-4">
                            <?= htmlspecialchars(substr($m['resume'], 0, 120)) ?>...
                        </p>
                        <?php endif; ?>

                        <p class="text-xs text-gray-400 mb-4">
                            ⭐ Ajouté le <?= date('d/m/Y', strtotime($m['date_ajout'])) ?>
                        </p>

                        <div class="flex gap-2">
                            <a href="<?= APP_URL ?>/memoires/voir/<?= $m['id'] ?>"
                               class="flex-1 text-center bg-blue-900 hover:bg-yellow-500 hover:text-blue-900 text-white text-sm font-medium py-2.5 rounded-xl transition-all">
                                Consulter →
                            </a>
                            <a href="<?= APP_URL ?>/memoires/favori/<?= $m['id'] ?>"
                               class="px-3 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 rounded-xl transition-all text-sm"
                               title="Retirer des favoris">✕</a>
                        </div>
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