<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — <?= htmlspecialchars($memoire['titre']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { background: linear-gradient(180deg, #0a1628 0%, #1B3A6B 100%); }
        .nav-item { transition: all 0.2s; border-radius: 10px; }
        .nav-item:hover { background: rgba(200,168,75,0.15); color: #C8A84B; }
        /* Bloquer téléchargement PDF */
        #pdf-container {
            -webkit-user-select: none;
            user-select: none;
            pointer-events: none;
        }
        #pdf-wrap { pointer-events: all; }
        iframe { border: none; }
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
            <?php if(in_array($_SESSION['role'], ['admin','directeur'])): ?>
            <a href="<?= APP_URL ?>/admin/dashboard" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📊 Dashboard</a>
            <?php endif; ?>
            <a href="<?= APP_URL ?>/memoires/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📚 Bibliothèque</a>
            <?php if(in_array($_SESSION['role'], ['diplome','directeur','admin'])): ?>
            <a href="<?= APP_URL ?>/memoires/soumettre" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📤 Soumettre</a>
            <?php endif; ?>
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
            <div class="flex items-center gap-4">
                <a href="<?= APP_URL ?>/memoires/index" class="text-gray-400 hover:text-gray-600">← Retour</a>
                <div>
                    <h2 class="text-gray-800 font-semibold text-sm line-clamp-1"><?= htmlspecialchars($memoire['titre']) ?></h2>
                    <p class="text-gray-400 text-xs"><?= htmlspecialchars($memoire['filiere']) ?> · <?= $memoire['annee_academique'] ?></p>
                </div>
            </div>
            <!-- Actions -->
            <div class="flex items-center gap-2">
                <!-- Like -->
                <a href="<?= APP_URL ?>/memoires/liker/<?= $memoire['id'] ?>"
                   class="flex items-center gap-1.5 px-4 py-2 rounded-xl border transition-all text-sm
                   <?= $liked ? 'bg-red-50 border-red-200 text-red-600' : 'border-gray-200 text-gray-500 hover:bg-red-50 hover:border-red-200 hover:text-red-600' ?>">
                    <?= $liked ? '❤️' : '🤍' ?> Like
                </a>
                <!-- Favori -->
                <a href="<?= APP_URL ?>/memoires/favori/<?= $memoire['id'] ?>"
                   class="flex items-center gap-1.5 px-4 py-2 rounded-xl border transition-all text-sm
                   <?= $favori ? 'bg-yellow-50 border-yellow-300 text-yellow-600' : 'border-gray-200 text-gray-500 hover:bg-yellow-50' ?>">
                    <?= $favori ? '⭐' : '☆' ?> Favori
                </a>
            </div>
        </div>

        <div class="flex gap-0 h-full">

            <!-- PDF Viewer -->
            <div class="flex-1 flex flex-col" style="height: calc(100vh - 73px)">
                <?php if($memoire['url_fichier'] && strtolower(pathinfo($memoire['url_fichier'], PATHINFO_EXTENSION)) === 'pdf'): ?>
                <div id="pdf-wrap" class="flex-1 relative bg-gray-800">
                    <!-- Overlay anti-téléchargement -->
                    <div style="position:absolute;top:0;left:0;right:0;bottom:0;z-index:10;pointer-events:none;"></div>
                    <iframe
                        src="<?= APP_URL ?>/memoires/pdf/<?= urlencode($memoire['url_fichier']) ?>#toolbar=0&navpanes=0&scrollbar=1"
                        class="w-full h-full"
                        style="height: calc(100vh - 73px)"
                        sandbox="allow-same-origin allow-scripts"
                    ></iframe>
                </div>
                <?php else: ?>
                <div class="flex-1 flex items-center justify-center bg-gray-100">
                    <div class="text-center">
                        <div class="text-6xl mb-4">📄</div>
                        <p class="text-gray-500">Aperçu non disponible pour ce format.</p>
                        <p class="text-gray-400 text-sm mt-1">Format : <?= strtoupper(pathinfo($memoire['url_fichier'], PATHINFO_EXTENSION)) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Panneau latéral -->
            <div class="w-80 flex-shrink-0 border-l border-gray-200 bg-white flex flex-col" style="height: calc(100vh - 73px)">

                <!-- Infos mémoire -->
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm mb-4">Informations</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Auteur</p>
                            <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars(($memoire['prenom'] ?? '') . ' ' . ($memoire['nom'] ?? '')) ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Encadreur</p>
                            <p class="text-sm text-gray-700"><?= htmlspecialchars(($memoire['prof_prenom'] ?? '—') . ' ' . ($memoire['prof_nom'] ?? '')) ?></p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Filière</p>
                                <p class="text-sm text-gray-700"><?= htmlspecialchars($memoire['filiere'] ?? '—') ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Niveau</p>
                                <p class="text-sm text-gray-700 capitalize"><?= $memoire['niveau'] ?></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Année</p>
                            <p class="text-sm text-gray-700"><?= $memoire['annee_academique'] ?? '—' ?></p>
                        </div>
                        <?php if($memoire['resume']): ?>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Résumé</p>
                            <p class="text-xs text-gray-600 leading-relaxed"><?= htmlspecialchars(substr($memoire['resume'], 0, 200)) ?>...</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Commentaires -->
                <div class="flex-1 overflow-y-auto p-5">
                    <h3 class="font-semibold text-gray-800 text-sm mb-4">
                        💬 Commentaires (<?= count($commentaires) ?>)
                    </h3>

                    <!-- Formulaire commentaire -->
                    <form action="<?= APP_URL ?>/memoires/commenter/<?= $memoire['id'] ?>" method="POST" class="mb-5">
                        <textarea name="contenu" rows="3" placeholder="Votre commentaire..."
                            class="w-full border border-gray-200 rounded-xl p-3 text-sm outline-none focus:border-blue-500 resize-none"></textarea>
                        <button type="submit"
                            class="mt-2 w-full bg-blue-900 hover:bg-yellow-500 hover:text-blue-900 text-white text-sm font-medium py-2 rounded-xl transition-all">
                            Commenter
                        </button>
                    </form>

                    <!-- Liste commentaires -->
                    <div class="space-y-4">
                        <?php if(empty($commentaires)): ?>
                        <p class="text-gray-400 text-xs text-center py-4">Aucun commentaire pour l'instant.</p>
                        <?php else: ?>
                        <?php foreach($commentaires as $c): ?>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-full bg-blue-900 flex items-center justify-center text-white text-xs font-bold">
                                    <?= strtoupper(substr($c['prenom'], 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-700"><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></p>
                                    <p class="text-xs text-gray-400"><?= date('d/m/Y H:i', strtotime($c['date_comment'])) ?></p>
                                </div>
                                <span class="ml-auto text-xs text-gray-400">❤️ <?= $c['total_likes'] ?></span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed"><?= htmlspecialchars($c['contenu']) ?></p>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Bloquer clic droit sur le PDF
    document.addEventListener('contextmenu', function(e) {
        if (e.target.tagName === 'IFRAME') e.preventDefault();
    });
    // Bloquer Ctrl+S, Ctrl+P
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && ['s','p','u'].includes(e.key.toLowerCase())) {
            e.preventDefault();
        }
    });
</script>
</body>
</html>