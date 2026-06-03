<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Espace Professeur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar { background: linear-gradient(180deg, #0a1628 0%, #1B3A6B 100%); }
        .nav-item { transition: all 0.2s; border-radius: 10px; }
        .nav-item:hover { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .nav-item.active { background: rgba(200,168,75,0.15); color: #C8A84B; }
        .modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:50; align-items:center; justify-content:center; }
        .modal.open { display:flex; }
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
            <a href="<?= APP_URL ?>/professeur/dashboard" class="nav-item active flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm">✅ Mes validations</a>
            <a href="<?= APP_URL ?>/memoires/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📚 Bibliothèque</a>
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
            <h2 class="text-gray-800 font-semibold text-lg">Espace Professeur</h2>
            <p class="text-gray-400 text-xs">Bienvenue <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></p>
        </div>

        <div class="p-8">

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-5 mb-8">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
                    <p class="text-3xl font-bold text-yellow-500"><?= count($memoires) ?></p>
                    <p class="text-gray-500 text-sm mt-1">En attente</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
                    <p class="text-3xl font-bold text-green-500">
                        <?= count(array_filter($historique, fn($h) => $h['decision'] === 'approuve')) ?>
                    </p>
                    <p class="text-gray-500 text-sm mt-1">Validés</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
                    <p class="text-3xl font-bold text-red-500">
                        <?= count(array_filter($historique, fn($h) => $h['decision'] === 'rejete')) ?>
                    </p>
                    <p class="text-gray-500 text-sm mt-1">Rejetés</p>
                </div>
            </div>

            <!-- Mémoires en attente -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-100 bg-yellow-50">
                    <h3 class="font-semibold text-gray-800">⏳ Mémoires en attente de validation (<?= count($memoires) ?>)</h3>
                </div>

                <?php if(empty($memoires)): ?>
                <div class="text-center py-12">
                    <div class="text-5xl mb-3">✅</div>
                    <p class="text-gray-400">Aucun mémoire en attente.</p>
                </div>
                <?php else: ?>
                <div class="divide-y divide-gray-50">
                    <?php foreach($memoires as $m): ?>
                    <div class="p-6 flex items-start gap-5">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">📄</div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-800 mb-1"><?= htmlspecialchars($m['titre']) ?></h4>
                            <p class="text-sm text-gray-500 mb-1">
                                👤 <?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?> ·
                                📁 <?= htmlspecialchars($m['filiere'] ?? '—') ?> ·
                                🎓 <?= ucfirst($m['niveau']) ?>
                            </p>
                            <p class="text-xs text-gray-400 mb-3">Déposé le <?= date('d/m/Y à H:i', strtotime($m['created_at'])) ?></p>
                            <?php if($m['resume']): ?>
                            <p class="text-xs text-gray-500 line-clamp-2 mb-4"><?= htmlspecialchars(substr($m['resume'], 0, 150)) ?>...</p>
                            <?php endif; ?>

                            <!-- Actions -->
                            <div class="flex gap-2 flex-wrap">
                                <a href="<?= APP_URL ?>/memoires/voir/<?= $m['id'] ?>"
                                   class="text-xs px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition-all">
                                    👁 Consulter le PDF
                                </a>
                                <button onclick="ouvrirModal(<?= $m['id'] ?>, '<?= htmlspecialchars(addslashes($m['titre'])) ?>')"
                                    class="text-xs px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">
                                    ✅ Prendre une décision
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Historique -->
            <?php if(!empty($historique)): ?>
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">📋 Historique des décisions</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    <?php foreach($historique as $h): ?>
                    <div class="px-6 py-4 flex items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($h['titre']) ?></p>
                            <p class="text-xs text-gray-400"><?= htmlspecialchars($h['prenom'] . ' ' . $h['nom']) ?></p>
                        </div>
                        <span class="text-xs px-3 py-1 rounded-full flex-shrink-0
                            <?= $h['decision'] === 'approuve' ? 'bg-green-100 text-green-700' :
                               ($h['decision'] === 'rejete' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') ?>">
                            <?= $h['decision'] === 'approuve' ? '✅ Validé' :
                               ($h['decision'] === 'rejete' ? '❌ Rejeté' : '⚠️ Correction') ?>
                        </span>
                        <span class="text-xs text-gray-400 flex-shrink-0"><?= $h['date_action'] ? date('d/m/Y', strtotime($h['date_action'])) : '—' ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </main>
</div>

<!-- Modal décision -->
<div class="modal" id="modal">
    <div class="bg-white rounded-2xl p-8 w-full max-w-md mx-4 shadow-2xl">
        <h3 class="font-bold text-gray-800 text-lg mb-1">Prendre une décision</h3>
        <p class="text-gray-500 text-sm mb-6" id="modal-titre"></p>

        <form method="POST" id="form-decision">
            <div class="space-y-4">
                <!-- Décision -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Décision <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="decision" value="approuve" class="hidden peer" required>
                            <div class="peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 border-2 border-gray-200 rounded-xl p-3 text-center text-xs font-medium text-gray-600 hover:border-green-400 transition-all">
                                ✅ Approuver
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="decision" value="correction" class="hidden peer">
                            <div class="peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-yellow-500 border-2 border-gray-200 rounded-xl p-3 text-center text-xs font-medium text-gray-600 hover:border-yellow-400 transition-all">
                                ⚠️ Correction
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="decision" value="rejete" class="hidden peer">
                            <div class="peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 border-2 border-gray-200 rounded-xl p-3 text-center text-xs font-medium text-gray-600 hover:border-red-400 transition-all">
                                ❌ Rejeter
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Commentaire -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                    <textarea name="commentaire" rows="3" placeholder="Motif de la décision..."
                        class="w-full border border-gray-200 rounded-xl p-3 text-sm outline-none focus:border-blue-500 resize-none"></textarea>
                </div>

                <!-- Boutons -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-blue-900 hover:bg-yellow-500 hover:text-blue-900 text-white font-semibold py-3 rounded-xl transition-all text-sm">
                        Confirmer →
                    </button>
                    <button type="button" onclick="fermerModal()"
                        class="px-6 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-all text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function ouvrirModal(id, titre) {
    document.getElementById('modal-titre').textContent = titre;
    document.getElementById('form-decision').action = '<?= APP_URL ?>/professeur/valider/' + id;
    document.getElementById('modal').classList.add('open');
}
function fermerModal() {
    document.getElementById('modal').classList.remove('open');
}
</script>
</body>
</html>