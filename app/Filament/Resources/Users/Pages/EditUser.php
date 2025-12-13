<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Users\UserResource;
use Illuminate\Support\Facades\Http;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendDocuSeal')
                ->label('Sign Document')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Send Signature Request')
                ->modalDescription("A signature link will be sent to the user's email: {$this->record->email}. Are you sure?")
                ->action(function () {
                     $userName = $this->record->name;
                    $userEmail = $this->record->email;
                Log::info('API Key Used: ' . config('services.docuseal.secret'));

                $response = Http::withHeaders([
                    'X-Auth-Token' => config('services.docuseal.secret'),

                    'Content-Type' => 'application/json',
                    ])->post('https://api.docuseal.com/submissions', [
                        'template_id' => (int) config('services.docuseal.template_id'),
                        'submitters' => [
                            [
                                'email' => $userEmail,
                                'fields' => [
                                    [
                                        'name' => 'name',
                                        'default_value' => $userName,
                                        'readonly' => false,
                                    ],
                                    [
                                        'name' => 'Email',
                                        'default_value' => $userEmail,
                                        'readonly' => true,
                                    ],
                                ],
                            ],
                        ],
                    ]);

                     if ($response->successful()) {
                        Notification::make()
                        ->title('Success!')
                        ->body("The signature request has been sent to {$userEmail}. The user will receive an email containing the signature link.")
                        ->success()
                            ->send();
                    } else {
                        $errorMessage = $response->json('error', 'Failed to send the signature request. Please check the API settings.');
                        Notification::make()
                            ->title('Send Error')
                            ->body($errorMessage)
                            ->danger()
                            ->send();

                        Log::info('API Key Used: ' . config('services.docuseal.secret'));
                        Log::error('DocuSeal API Error', [
                            'user' => $userEmail,
                            'response' => $response->body(),
                        ]);
                    }
                }),
            DeleteAction::make(),
        ];
    }

}
