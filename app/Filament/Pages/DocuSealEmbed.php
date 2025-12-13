<?php

namespace App\Filament\Pages;

use Firebase\JWT\JWT;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;

class DocuSealEmbed extends Page
{
    protected static ?string $navigationLabel = 'DocuSeal';
    protected static ?string $title = 'DocuSeal Embed';

    protected string $view = 'filament.pages.docu-seal-embed';


    public ?string $templateSlug = null;
    public ?string $email = null;
    public ?string $name = null;
    public ?string $token = null;
    public ?string $fieldValues = null;

    public function mount(): void
    {
        $this->templateSlug = request()->query('templateSlug', 'bQKax93zzA41j8');

        $this->name  = request()->query('name', '');
        $this->email = request()->query('email', '');

        $this->token = JWT::encode([
            'signer_email' => $this->email,
            'user_email'   => $this->email,
            'prefill' => [
                'name'  => $this->name,
                'email' => $this->email,
            ],
        ], config('services.docuseal.secret'), 'HS256');
        $prefillData = [
            "name" => $this->name,
            "Email" => $this->email,
        ];
        $this->fieldValues = json_encode($prefillData);

        Log::info('DocuSeal Debug', [
            'name' => $this->name,
            'email' => $this->email,
            'token' => $this->token,
            'fieldValues' => $this->fieldValues
        ]);
    }
}
