<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Bibliothèque</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { background: linear-gradient(180deg, #0a1628 0%, #1B3A6B 100%); }
        .nav-item { transition: all 0.2s; border-radius: 10px; }
        .nav-item:hover { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .nav-item.active { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .memoire-card { transition: all 0.25s; }
        .memoire-card:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .input-search { border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 10px 16px; outline: none; transition: all 0.2s; }
        .input-search:focus { border-color: #1B3A6B; box-shadow: 0 0 0 3px rgba(27,58,107,0.1); }
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
            <a href="<?= APP_URL ?>/admin/dashboard" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📊 Tableau de bord</a>
            <a href="<?= APP_URL ?>/utilisateur/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">👥 Utilisateurs</a>
            <?php endif; ?>
            <a href="<?= APP_URL ?>/memoires/index" class="nav-item active flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm">📚 Mémoires</a>
            <?php if(in_array($_SESSION['role'], ['diplome','directeur','admin'])): ?>
            <a href="<?= APP_URL ?>/memoires/soumettre" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📤 Soumettre mémoire</a>
            <?php endif; ?>
            <?php if(in_array($_SESSION['role'], ['professeur'])): ?>
            <a href="<?= APP_URL ?>/professeur/dashboard" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">✅ Mes validations</a>
            <?php endif; ?>
            <a href="<?= APP_URL ?>/memoires/favoris" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">⭐ Mes favoris</a>
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
        <div class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-gray-800 font-semibold text-lg">Bibliothèque des mémoires</h2>
                    <p class="text-gray-400 text-xs"><?= count($memoires) ?> mémoire(s) disponible(s)</p>
                </div>
                <?php if(in_array($_SESSION['role'], ['diplome','directeur','admin'])): ?>
                <a href="<?= APP_URL ?>/memoires/soumettre"
                   class="bg-blue-900 hover:bg-yellow-500 hover:text-blue-900 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all">
                    + Soumettre un mémoire
                </a>
                <?php endif; ?>
            </div>

            <!-- Barre de recherche -->
            <form method="GET" action="<?= APP_URL ?>/memoires/index" class="flex gap-3 flex-wrap">
                <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                    placeholder="🔍 Rechercher par titre, auteur, résumé..."
                    class="input-search flex-1 min-w-64 text-sm">

                <select name="filiere" class="input-search bg-white text-sm text-gray-600">
                    <option value="">Toutes les filières</option>
                    <?php foreach($filieres as $f): ?>
                    <option value="<?= $f['filiere'] ?>" <?= ($_GET['filiere'] ?? '') === $f['filiere'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['filiere']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <select name="niveau" class="input-search bg-white text-sm text-gray-600">
                    <option value="">Tous niveaux</option>
                    <option value="licence" <?= ($_GET['niveau'] ?? '') === 'licence' ? 'selected' : '' ?>>Licence</option>
                    <option value="master" <?= ($_GET['niveau'] ?? '') === 'master' ? 'selected' : '' ?>>Master</option>
                </select>

                <input type="text" name="annee" value="<?= htmlspecialchars($_GET['annee'] ?? '') ?>"
                    placeholder="Année ex: 2023-2024"
                    class="input-search text-sm w-40">

                <button type="submit"
                    class="bg-blue-900 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-800 transition-all">
                    Filtrer
                </button>
                <?php if(!empty($_GET['q']) || !empty($_GET['filiere']) || !empty($_GET['niveau']) || !empty($_GET['annee'])): ?>
                <a href="<?= APP_URL ?>/memoires/index"
                    class="border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition-all">
                    ✕ Reset
                </a>
                <?php endif; ?>
            </form>
        </div>

        <div class="p-8">
            <?php if(empty($memoires)): ?>
            <!-- Empty state -->
            <div class="text-center py-20">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-gray-500 font-medium text-lg mb-2">Aucun mémoire trouvé</h3>
                <p class="text-gray-400 text-sm">Essayez d'autres critères de recherche.</p>
            </div>
            <?php else: ?>

            <!-- Grille mémoires -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                <?php foreach($memoires as $m): ?>
                <div class="memoire-card bg-white rounded-2xl border border-gray-100 overflow-hidden">

                    <!-- Header card -->
                    <div class="bg-gradient-to-r from-blue-900 to-blue-700 p-5 relative">
                        <div class="absolute top-4 right-4 text-3xl opacity-20">📄</div>
                        <span class="text-xs bg-white bg-opacity-20 text-white px-2 py-1 rounded-full mb-3 inline-block">
                            <?= ucfirst($m['niveau']) ?>
                        </span>
                        <h3 class="text-white font-semibold text-sm leading-snug line-clamp-2 pr-8">
                            <?= htmlspecialchars($m['titre']) ?>
                        </h3>
                    </div>

                    <!-- Body card -->
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-900 font-bold text-xs">
                                <?= strtoupper(substr($m['prenom'] ?? 'I', 0, 1)) ?>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-700"><?= htmlspecialchars(($m['prenom'] ?? '') . ' ' . ($m['nom'] ?? 'Inconnu')) ?></p>
                                <p class="text-xs text-gray-400"><?= htmlspecialchars($m['filiere'] ?? '—') ?> · <?= $m['annee_academique'] ?? '—' ?></p>
                            </div>
                        </div>

                        <?php if($m['resume']): ?>
                        <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 mb-4">
                            <?= htmlspecialchars(substr($m['resume'], 0, 120)) ?>...
                        </p>
                        <?php endif; ?>

                        <!-- Stats -->
                        <div class="flex items-center gap-4 text-xs text-gray-400 mb-4">
                            <span>👁 <?= $m['total_vues'] ?> vues</span>
                            <span>❤️ <?= $m['total_likes'] ?> likes</span>
                            <span>💬 <?= $m['total_comments'] ?> commentaires</span>
                        </div>

                        <!-- Encadreur -->
                        <?php if($m['prof_nom']): ?>
                        <p class="text-xs text-gray-400 mb-4">
                            👨‍🏫 <?= htmlspecialchars($m['prof_prenom'] . ' ' . $m['prof_nom']) ?>
                        </p>
                        <?php endif; ?>

                        <!-- Bouton -->
                        <a href="<?= APP_URL ?>/memoires/voir/<?= $m['id'] ?>"
                           class="block w-full text-center bg-blue-900 hover:bg-yellow-500 hover:text-blue-900 text-white text-sm font-medium py-2.5 rounded-xl transition-all">
                            Consulter →
                        </a>
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