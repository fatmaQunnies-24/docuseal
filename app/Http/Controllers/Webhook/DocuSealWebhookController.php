<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DocuSealWebhookController extends Controller
{
     public function handle(Request $request, $token)
    {
         $expectedToken = config('services.docuseal.webhook_token');

        if ($token !== $expectedToken) {
            Log::warning('DocuSeal Webhook: Invalid token received.', ['token' => $token]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

         $payload = $request->all();
        Log::info('  DocuSeal Webhook Received:', $payload);

         if ($request->input('event_type') === 'form.completed') {
             $data = $request->input('data');
            $userEmail = $data['email'];

             $user = User::where('email', $userEmail)->first();

           
                    $documentUrl = $data['documents'][0]['url'];


                    // Log::info( '', [
                    //     'user_id' => $user->id,
                    //     'email' => $userEmail,
                    //     'document_url' => $documentUrl,
                    //     'submission_id' => $data['submission']['id']
                    // ]);


                    $this->downloadAndStoreDocument($documentUrl, $data['submission']['id'], $user->id);

        }

         return response()->json(['status' => 'received', 'message' => 's'], 200);
    }

     private function downloadAndStoreDocument($fileUrl, $submissionId, $userId)
    {
        try {
            $content = file_get_contents($fileUrl);

                 $fileName = 'signed_document_' . $userId . '_' . $submissionId . '_' . time() . '.pdf';

            Storage::disk('public')->put('signed_docs/' . $fileName, $content);

        } catch (\Exception $e) {

        }
    }
}
