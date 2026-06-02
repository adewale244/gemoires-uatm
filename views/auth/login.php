<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GéMoires — UATM GASA | Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }

        .left-panel {
            background: linear-gradient(160deg, #0a1628 0%, #1B3A6B 40%, #0d2244 100%);
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at center, rgba(200,168,75,0.08) 0%, transparent 60%);
        }
        .left-panel::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #C8A84B, transparent);
        }

        .right-panel { background: #f8f9fc; }

        .gold-line {
            width: 50px; height: 3px;
            background: linear-gradient(90deg, #C8A84B, #e8c76a);
            border-radius: 2px;
        }

        .input-modern {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.2s;
            background: white;
            width: 100%;
            outline: none;
            color: #1a2744;
        }
        .input-modern:focus {
            border-color: #1B3A6B;
            box-shadow: 0 0 0 3px rgba(27,58,107,0.1);
        }
        .input-modern::placeholder { color: #a0aec0; }

        .btn-login {
            background: linear-gradient(135deg, #1B3A6B 0%, #2d5aa0 100%);
            color: white;
            border: none;
            padding: 13px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.25s;
            letter-spacing: 0.3px;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #C8A84B 0%, #e8c76a 100%);
            color: #1a2744;
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(200,168,75,0.35);
        }

        .stat-card {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .shield-bg {
            position: absolute;
            right: -80px; top: 50%;
            transform: translateY(-50%);
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.02);
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .shield-bg-2 {
            position: absolute;
            right: -120px; top: 50%;
            transform: translateY(-50%);
            width: 550px; height: 550px;
            background: rgba(255,255,255,0.01);
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.04);
        }

        .badge {
            display: inline-block;
            background: rgba(200,168,75,0.15);
            border: 1px solid rgba(200,168,75,0.3);
            color: #e8c76a;
            font-size: 11px;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeup { animation: fadeUp 0.5s ease forwards; }
        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
    </style>
</head>
<body class="min-h-screen flex">

    <!-- ═══ PANNEAU GAUCHE — Branding UATM ═══ -->
    <div class="left-panel hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative">
        <div class="shield-bg-2"></div>
        <div class="shield-bg"></div>

        <!-- Header -->
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-8">
                <!-- Logo UATM -->
                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-lg overflow-hidden">
                    <img src="<?= APP_URL ?>/public/assets/logo_uatm.png" alt="UATM" class="w-14 h-14 object-contain"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <div style="display:none" class="w-full h-full bg-blue-900 items-center justify-center">
                        <span class="text-yellow-400 font-bold text-lg">UATM</span>
                    </div>
                </div>
                <div>
                    <p class="text-yellow-400 font-semibold text-sm tracking-wide">UATM GASA Formation</p>
                    <p class="text-blue-300 text-xs">Université Africaine de Technologie et de Management</p>
                </div>
            </div>

            <div class="gold-line mb-6"></div>

            <span class="badge mb-6 block w-fit">Plateforme académique</span>

            <h1 class="font-serif text-white text-4xl xl:text-5xl leading-tight mb-4">
                Bienvenue sur<br>
                <span class="text-yellow-400">GéMoires</span>
            </h1>

            <p class="text-blue-200 text-base leading-relaxed max-w-sm">
                La bibliothèque numérique officielle de l'UATM. Accédez à tous les mémoires académiques, consultez, échangez et soumettez vos travaux en toute sécurité.
            </p>
        </div>

        <!-- Stats -->
        <div class="relative z-10">
            <div class="grid grid-cols-3 gap-3 mb-8">
                <div class="stat-card">
                    <p class="text-yellow-400 text-2xl font-bold">24h</p>
                    <p class="text-blue-300 text-xs mt-1">Disponible</p>
                </div>
                <div class="stat-card">
                    <p class="text-yellow-400 text-2xl font-bold">100%</p>
                    <p class="text-blue-300 text-xs mt-1">Sécurisé</p>
                </div>
                <div class="stat-card">
                    <p class="text-yellow-400 text-2xl font-bold">∞</p>
                    <p class="text-blue-300 text-xs mt-1">Mémoires</p>
                </div>
            </div>

            <div class="border-t border-white border-opacity-10 pt-6">
                <p class="text-gray-500 text-xs">© <?= date('Y') ?> GéMoires — UATM GASA Formation. Tous droits réservés.</p>
            </div>
        </div>
    </div>

    <!-- ═══ PANNEAU DROIT — Formulaire ═══ -->
    <div class="right-panel w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-sm">

            <!-- Mobile header -->
            <div class="lg:hidden text-center mb-8">
                <h1 class="text-3xl font-bold text-blue-900" style="font-family:'Playfair Display',serif">GéMoires</h1>
                <p class="text-gray-500 text-sm">UATM GASA Formation</p>
            </div>

            <!-- Titre formulaire -->
            <div class="mb-8 animate-fadeup">
                <div class="gold-line mb-4"></div>
                <h2 class="text-2xl font-bold text-gray-800" style="font-family:'Playfair Display',serif">Connexion</h2>
                <p class="text-gray-500 text-sm mt-1">Entrez vos identifiants pour accéder à votre espace</p>
            </div>

            <!-- Erreur -->
            <?php if (!empty($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg mb-6 text-sm flex items-start gap-2 animate-fadeup">
                <span class="mt-0.5">⚠️</span>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <!-- Formulaire -->
            <form action="<?= APP_URL ?>/auth/authenticate" method="POST">

                <div class="space-y-5">

                    <!-- Email -->
                    <div class="animate-fadeup delay-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse email institutionnelle
                        </label>
                        <input
                            type="email"
                            name="email"
                            placeholder="prenom.nom@uatm.bj"
                            required
                            class="input-modern"
                        >
                    </div>

                    <!-- Mot de passe -->
                    <div class="animate-fadeup delay-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Mot de passe
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="pwd"
                                placeholder="••••••••••"
                                required
                                class="input-modern pr-12"
                            >
                            <button type="button" onclick="togglePwd()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-700 transition-colors">
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Bouton -->
                    <div class="animate-fadeup delay-3 pt-2">
                        <button type="submit" class="btn-login">
                            Accéder à mon espace →
                        </button>
                    </div>

                </div>
            </form>

            <!-- Séparateur -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <p class="text-xs text-blue-700 font-medium mb-1">🔒 Accès réservé</p>
                    <p class="text-xs text-gray-500">Les comptes sont créés uniquement par le Directeur des études. Contactez l'administration pour tout problème de connexion.</p>
                </div>
            </div>

            <!-- Roles indicatifs -->
            <div class="mt-4 flex flex-wrap gap-2 justify-center">
                <span class="text-xs bg-gray-100 text-gray-500 px-3 py-1 rounded-full">Étudiant</span>
                <span class="text-xs bg-gray-100 text-gray-500 px-3 py-1 rounded-full">Professeur</span>
                <span class="text-xs bg-gray-100 text-gray-500 px-3 py-1 rounded-full">Directeur</span>
                <span class="text-xs bg-gray-100 text-gray-500 px-3 py-1 rounded-full">Administrateur</span>
            </div>

        </div>
    </div>

    <script>
        function togglePwd() {
            const pwd = document.getElementById('pwd');
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>