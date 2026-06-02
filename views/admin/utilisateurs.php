<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — Utilisateurs</title>
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
            <a href="<?= APP_URL ?>/admin/dashboard" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📊 Tableau de bord</a>
            <a href="<?= APP_URL ?>/utilisateur/index" class="nav-item active flex items-center gap-3 px-3 py-2.5 text-yellow-400 text-sm">👥 Utilisateurs</a>
            <a href="<?= APP_URL ?>/memoires/index" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">📚 Mémoires</a>
            <a href="<?= APP_URL ?>/admin/upload" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">⬆️ Uploader mémoire</a>
            <div class="border-t border-white border-opacity-10 my-3"></div>
            <a href="<?= APP_URL ?>/admin/config" class="nav-item flex items-center gap-3 px-3 py-2.5 text-blue-200 text-sm">⚙️ Configuration</a>
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
        <div class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h2 class="text-gray-800 font-semibold text-lg">Gestion des utilisateurs</h2>
                <p class="text-gray-400 text-xs"><?= count($users) ?> comptes au total</p>
            </div>
            <a href="<?= APP_URL ?>/utilisateur/create"
               class="bg-blue-900 hover:bg-yellow-500 hover:text-blue-900 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all">
                + Créer un compte
            </a>
        </div>

        <div class="p-8">

            <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg mb-6 text-sm">
                ✅ Compte créé avec succès !
            </div>
            <?php endif; ?>

            <!-- Filtres rapides -->
            <div class="flex gap-2 mb-6 flex-wrap">
                <?php
                $roles = ['Tous', 'admin', 'directeur', 'professeur', 'diplome', 'consultant'];
                foreach ($roles as $r):
                ?>
                <button onclick="filtrer('<?= $r ?>')"
                    class="filtre-btn text-xs px-4 py-2 rounded-full border border-gray-200 text-gray-600 hover:bg-blue-900 hover:text-white hover:border-blue-900 transition-all"
                    data-role="<?= $r ?>">
                    <?= ucfirst($r) ?>
                </button>
                <?php endforeach; ?>
            </div>

            <!-- Tableau -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rôle</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Filière</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Créé le</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="table-body">
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 py-12">Aucun utilisateur trouvé</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-gray-50 transition-colors user-row" data-role="<?= $u['role'] ?>">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-900 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        <?= strtoupper(substr($u['prenom'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800"><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></p>
                                        <p class="text-xs text-gray-400"><?= htmlspecialchars($u['email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-3 py-1 rounded-full font-medium
                                    <?= $u['role'] === 'admin' ? 'bg-purple-100 text-purple-700' :
                                       ($u['role'] === 'directeur' ? 'bg-blue-100 text-blue-700' :
                                       ($u['role'] === 'professeur' ? 'bg-green-100 text-green-700' :
                                       ($u['role'] === 'diplome' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700'))) ?>">
                                    <?= ucfirst($u['role']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= $u['filiere'] ?: '—' ?></td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-3 py-1 rounded-full <?= $u['statut'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= $u['statut'] ? '● Actif' : '● Inactif' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                            <td class="px-6 py-4">
                                <a href="<?= APP_URL ?>/utilisateur/toggle/<?= $u['id'] ?>"
                                   class="text-xs px-3 py-1.5 rounded-lg border transition-all
                                   <?= $u['statut'] ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-green-200 text-green-600 hover:bg-green-50' ?>">
                                    <?= $u['statut'] ? 'Désactiver' : 'Activer' ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
function filtrer(role) {
    document.querySelectorAll('.user-row').forEach(row => {
        row.style.display = (role === 'Tous' || row.dataset.role === role) ? '' : 'none';
    });
    document.querySelectorAll('.filtre-btn').forEach(btn => {
        btn.classList.toggle('bg-blue-900', btn.dataset.role === role);
        btn.classList.toggle('text-white', btn.dataset.role === role);
    });
}
</script>
</body>
</html>