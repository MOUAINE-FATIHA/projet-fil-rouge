<?php
namespace App\Http\Controllers\Entreprise;
use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Offre;
use App\Notifications\ConventionDeposee;
use App\Repositories\Contracts\CandidatureContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class CandidatureController extends Controller
{
public function __construct(private CandidatureContract $candidatures) {}
    public function toutes(Request $request)
    {
        $entrepriseId = Auth::user()->profilEntreprise->id;

        $candidatures = Candidature::with(['stagiaire.user', 'offre'])
            ->whereHas('offre', fn ($query) => $query->where('company_id', $entrepriseId))
            ->when($request->statut, fn ($query) => $query->where('status', $request->statut))
            ->latest()
            ->paginate(15);

        return view('entreprise.candidatures.toutes', compact('candidatures'));
    }

    public function index(Offre $offre)
    {
        $this->verifierAppartenance($offre);
        $candidatures = $this->candidatures->parOffre(
            $offre->id,
            request('statut')
        );
        return view('entreprise.candidatures.index', compact('offre', 'candidatures'));
    }
    public function show(Candidature $candidature)
    {
        $this->verifierAcces($candidature);
        $candidature->load('stagiaire.user', 'offre', 'stage');

        return view('entreprise.candidatures.show', compact('candidature'));
    }

    public function telechargerCv(Candidature $candidature)
    {
        $this->verifierAcces($candidature);

        abort_unless($candidature->cv_path, 404, 'Aucun CV trouvé pour cette candidature.');
        abort_unless(Storage::disk('private')->exists($candidature->cv_path), 404, 'Le fichier CV est introuvable.');

        $nom = 'cv-' . str($candidature->stagiaire->user->name ?? 'stagiaire')
            ->slug()
            ->value() . '.pdf';

        return Storage::disk('private')->download($candidature->cv_path, $nom);
    }

    public function uploadConvention(Request $request, Candidature $candidature)
    {
        $this->verifierAcces($candidature);

        abort_unless($candidature->status === 'accepted', 403, 'La convention est disponible après acceptation.');
        abort_unless($candidature->stage, 404, 'Aucun stage trouvé pour cette candidature.');
        abort_unless($candidature->stage->conventionPreparee(), 422, 'La convention doit être préparée par l’administration avant le dépôt.');

        $donnees = $request->validate([
            'convention' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $stage = $candidature->stage;

        if ($stage->convention_path) {
            Storage::disk('private')->delete($stage->convention_path);
        }

        $chemin = $donnees['convention']->store('conventions', 'private');

        $stage->update([
            'convention_path' => $chemin,
            'convention_status' => 'pending',
            'convention_validated_at' => null,
        ]);

        $stage->loadMissing([
            'candidature.offre.entreprise.user',
            'candidature.stagiaire.user',
            'encadrant.user',
        ]);

        if ($stage->encadrant?->user) {
            $stage->encadrant->user->notify(new ConventionDeposee($stage));
        }

        return back()->with('succes', 'Convention déposée. Elle attend la validation de l’encadrant.');
    }

    public function telechargerConventionPreparee(Candidature $candidature)
    {
        $this->verifierAcces($candidature);

        $stage = $candidature->stage;
        abort_unless($stage && $stage->conventionPreparee(), 404, 'La convention n’est pas encore préparée.');

        $stage->load([
            'candidature.offre.entreprise.user',
            'candidature.stagiaire.user',
            'encadrant.user',
        ]);

        $dossier = storage_path('app/private/tmp');
        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }

        $htmlPath = $dossier . '/convention-stage-' . $stage->id . '.html';
        $pdfPath = $dossier . '/convention-stage-' . $stage->id . '.pdf';

        file_put_contents($htmlPath, view('pdf.convention', compact('stage'))->render());

        $chrome = trim(shell_exec('command -v google-chrome || command -v chromium || command -v chromium-browser') ?? '');
        abort_unless($chrome, 500, 'Chrome ou Chromium est nécessaire pour générer le PDF.');

        $process = new Process([
            $chrome,
            '--headless',
            '--disable-gpu',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--no-pdf-header-footer',
            '--print-to-pdf-no-header',
            '--print-to-pdf=' . $pdfPath,
            'file://' . $htmlPath,
        ]);
        $process->run();

        abort_unless($process->isSuccessful() && file_exists($pdfPath), 500, 'Impossible de générer le PDF.');
        @unlink($htmlPath);

        return response()
            ->download($pdfPath, 'convention-a-signer-stage-' . $stage->id . '.pdf')
            ->deleteFileAfterSend(true);
    }

    public function telechargerConvention(Candidature $candidature)
    {
        $this->verifierAcces($candidature);

        $stage = $candidature->stage;
        abort_unless($stage && $stage->convention_path, 404, 'Aucune convention trouvée.');
        abort_if(str_starts_with($stage->convention_path, 'cvs/'), 422, 'Le fichier enregistré comme convention ressemble à un CV. Veuillez redéposer la convention signée.');
        abort_unless(Storage::disk('private')->exists($stage->convention_path), 404, 'Le fichier de convention est introuvable.');

        $nom = 'convention-stage-' . $stage->id . '.pdf';

        return Storage::disk('private')->download($stage->convention_path, $nom);
    }

    public function accepter(Request $request, Candidature $candidature)
    {
        $this->verifierAcces($candidature);

        abort_unless(
            $candidature->offre->aDesPlacesDisponibles(),
            422,
            'Plus de places disponibles pour cette offre.'
        );
        $donnees = $request->validate([
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);
        $this->candidatures->accepter($candidature->id, $donnees['feedback'] ?? null);

        return redirect()
            ->route('entreprise.candidatures.index', $candidature->offer_id)
            ->with('succes', 'Candidature acceptée. Le stage a été créé automatiquement.');
    }

    public function refuser(Request $request, Candidature $candidature)
    {
        $this->verifierAcces($candidature);

        $donnees = $request->validate([
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $this->candidatures->refuser($candidature->id, $donnees['feedback'] ?? null);

        return redirect()
            ->route('entreprise.candidatures.index', $candidature->offer_id)
            ->with('succes', 'Candidature refusée.');
    }

    private function verifierAppartenance(Offre $offre): void
    {
        $entrepriseId = Auth::user()->profilEntreprise->id;
        abort_if($offre->company_id !== $entrepriseId, 403);
    }

    private function verifierAcces(Candidature $candidature): void
    {
        $this->verifierAppartenance($candidature->offre);
    }
}
