<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Accès refusé</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        .dot-grid {
            background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>

    
</head>
<body class="bg-[#0A0F1E] dot-grid min-h-screen flex items-center justify-center text-white">
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-96 h-96 rounded-full blur-3xl pointer-events-none"
         style="background:rgba(37,99,235,0.15);"></div>
    <div class="text-center relative">
        <p class="text-8xl font-extrabold mb-4" style="color:#2563EB;">403</p>
        <h1 class="text-2xl font-bold text-white mb-2">Accès non autorisé</h1>
        <p class="text-white/30 mb-8">Vous n'avez pas la permission d'accéder à cette page.</p>
        <a href="{{ url('/') }}"
           class="inline-block font-semibold px-7 py-3 rounded-xl text-sm text-white transition"
           style="background:linear-gradient(135deg,#2563EB,#1d4ed8);">
            Retour à l'accueil
        </a>
    </div>
</body>
</html>