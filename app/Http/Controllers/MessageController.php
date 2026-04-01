<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * GET /api/conversations
     * Toutes les conversations de l'utilisateur
     */
    public function conversations(Request $request): JsonResponse
    {
        $conversations = $request->user()
            ->conversations()
            ->with(['participants', 'lastMessage.sender'])
            ->latest()
            ->paginate(20);

        // Ajouter le nombre de messages non lus par conversation
        $conversations->getCollection()->transform(function ($conv) use ($request) {
            $conv->unread_count = $conv->getUnreadCountForUser($request->user());
            return $conv;
        });

        return response()->json($conversations);
    }

    /**
     * POST /api/conversations
     * Créer une nouvelle conversation
     */
    public function createConversation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'subject'        => ['nullable', 'string', 'max:255'],
            'participant_ids' => ['required', 'array', 'min:1'],
            'participant_ids.*' => ['exists:users,id'],
            'internship_id'  => ['nullable', 'exists:internships,id'],
            'message'        => ['required', 'string', 'max:5000'],
        ]);

        $conversation = Conversation::create([
            'subject'       => $data['subject'] ?? null,
            'internship_id' => $data['internship_id'] ?? null,
        ]);

        // Ajouter les participants (créateur + destinataires)
        $participantIds = array_unique(
            array_merge([$request->user()->id], $data['participant_ids'])
        );
        $conversation->participants()->attach($participantIds, ['last_read_at' => now()]);

        // Premier message
        $message = $conversation->messages()->create([
            'sender_id' => $request->user()->id,
            'content'   => $data['message'],
        ]);

        return response()->json([
            'message'      => 'Conversation créée.',
            'conversation' => $conversation->load('participants', 'lastMessage.sender'),
        ], 201);
    }

    /**
     * GET /api/conversations/{conversation}/messages
     */
    public function messages(Request $request, Conversation $conversation): JsonResponse
    {
        // Vérifier que l'utilisateur est participant
        abort_unless(
            $conversation->participants()->where('user_id', $request->user()->id)->exists(),
            403,
            'Accès refusé.'
        );

        $messages = $conversation->messages()
            ->with('sender')
            ->oldest()
            ->paginate(30);

        // Marquer comme lu
        $conversation->markAsReadForUser($request->user());

        return response()->json($messages);
    }

    /**
     * POST /api/conversations/{conversation}/messages
     * Envoyer un message
     */
    public function send(Request $request, Conversation $conversation): JsonResponse
    {
        abort_unless(
            $conversation->participants()->where('user_id', $request->user()->id)->exists(),
            403,
            'Accès refusé.'
        );

        $data = $request->validate([
            'content'    => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')
                ->store("conversations/{$conversation->id}", 'private');
        }

        $message = $conversation->messages()->create([
            'sender_id'       => $request->user()->id,
            'content'         => $data['content'],
            'attachment_path' => $attachmentPath,
        ]);

        // Mettre à jour le last_read pour l'expéditeur
        $conversation->markAsReadForUser($request->user());

        return response()->json([
            'message' => $message->load('sender'),
        ], 201);
    }
}
