<?php
// Compter notifications non lues
$db_notif = Database::getInstance();
$nb_notifs = $db_notif->findOne(
    "SELECT COUNT(*) as total FROM notifications WHERE id_destinataire = ? AND lu = 0",
    [$_SESSION['user_id']]
)['total'];
?>
<div class="flex items-center gap-3">
    <!-- Cloche notifications -->
    <a href="<?= APP_URL ?>/notification/index" class="relative p-2 text-gray-400 hover:text-blue-700 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <?php if($nb_notifs > 0): ?>
        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
            <?= $nb_notifs > 9 ? '9+' : $nb_notifs ?>
        </span>
        <?php endif; ?>
    </a>
    <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium">● Système actif</span>
</div>