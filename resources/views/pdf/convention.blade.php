<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Convention de stage</title>
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }
        body {
            font-family: Arial, sans-serif;
            color: #1B3B59;
            font-size: 10.5px;
            line-height: 1.3;
            margin: 0;
        }
        h1 {
            text-align: center;
            font-size: 22px;
            margin: 0;
            color: #FDD400;
        }
        h2 {
            font-size: 12px;
            margin: 10px 0 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid #DDE5EA;
            color: #1B3B59;
        }
        .subtitle {
            text-align: center;
            color: #708090;
            margin-bottom: 12px;
        }
        .header {
            border-bottom: 2px solid #FDD400;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .grid {
            display: table;
            width: 100%;
            table-layout: fixed;
            border-spacing: 10px 0;
            margin-left: -10px;
        }
        .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .box {
            border: 1px solid #DDE5EA;
            padding: 6px 8px;
            margin-bottom: 5px;
            border-radius: 3px;
            min-height: 26px;
        }
        .label {
            color: #708090;
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 1px;
        }
        .value {
            font-weight: bold;
        }
        .text {
            white-space: pre-line;
            min-height: 36px;
        }
        .signatures {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin-top: 14px;
            border-spacing: 0;
        }
        .signature {
            display: table-cell;
            width: 50%;
            border: 1px solid #DDE5EA;
            height: 62px;
            padding: 8px;
            vertical-align: top;
        }
        .muted {
            color: #708090;
        }
    </style>
</head>
<body>
    @php
        $candidature = $stage->candidature;
        $offre = $candidature->offre;
        $stagiaire = $candidature->stagiaire;
        $entreprise = $offre->entreprise;
    @endphp

    <div class="header">
        <h1>Convention de stage</h1>
        <p class="subtitle">Document préparé par {{ $stage->school_name ?? 'YouCode' }}</p>
    </div>

    <div class="grid">
        <div class="col">
            <h2>Stagiaire</h2>
            <div class="box">
                <div class="label">Nom complet</div>
                <div class="value">{{ $stagiaire->user->name ?? '—' }}</div>
            </div>
            <div class="box">
                <div class="label">Email</div>
                <div>{{ $stagiaire->user->email ?? '—' }}</div>
            </div>
        </div>

        <div class="col">
            <h2>Entreprise</h2>
            <div class="box">
                <div class="label">Entreprise</div>
                <div class="value">{{ $entreprise->company_name ?? '—' }}</div>
            </div>
            <div class="box">
                <div class="label">Email</div>
                <div>{{ $entreprise->user->email ?? '—' }}</div>
            </div>
            <div class="box">
                <div class="label">Ville</div>
                <div>{{ $entreprise->city ?? $offre->city ?? '—' }}</div>
            </div>
        </div>
    </div>

    <h2>Informations du stage</h2>
    <div class="grid">
        <div class="col">
            <div class="box">
                <div class="label">École</div>
                <div class="value">{{ $stage->school_name ?? 'YouCode' }}</div>
            </div>
            <div class="box">
                <div class="label">Sujet / poste</div>
                <div class="value">{{ $offre->title ?? '—' }}</div>
            </div>
            <div class="box">
                <div class="label">Lieu du stage</div>
                <div>{{ $stage->convention_place ?? $offre->city ?? '—' }}</div>
            </div>
        </div>
        <div class="col">
            <div class="box">
                <div class="label">Période</div>
                <div>
                    Du {{ optional($stage->actual_start_date)->format('d/m/Y') ?? '—' }}
                    au {{ optional($stage->actual_end_date)->format('d/m/Y') ?? '—' }}
                </div>
            </div>
            <div class="box">
                <div class="label">Durée</div>
                <div>{{ $offre->duration_months }} mois</div>
            </div>
        </div>
    </div>

    <h2>Missions principales</h2>
    <div class="box text">{{ $stage->convention_tasks }}</div>

    <h2>Règles et conditions de l'école</h2>
    <div class="box text">
        {{ $stage->convention_notes ?: "Le stagiaire doit respecter le règlement de stage, les horaires convenus avec l'entreprise, la confidentialité des informations professionnelles et déposer les documents demandés par l'école." }}
    </div>

    <p class="muted">
        L'entreprise signe et cachette ce document, puis dépose la version signée sur la plateforme.
    </p>

    <div class="signatures">
        <div class="signature">
            <strong>Signature et cachet de l'entreprise</strong>
            <br><br>
            Date :
        </div>
        <div class="signature">
            <strong>Validation école / encadrant</strong>
            <br><br>
            Nom : {{ $stage->encadrant->user->name ?? '—' }}
        </div>
    </div>
</body>
</html>
