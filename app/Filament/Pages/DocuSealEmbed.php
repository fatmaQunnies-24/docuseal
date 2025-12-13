<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DocuSealEmbed extends Page
{
     protected static ?string $navigationLabel = 'DocuSeal';
    protected static ?string $title = 'DocuSeal Embed';

    protected   string $view = 'filament.pages.docu-seal-embed';

    public ?string $templateSlug = null;
    public ?string $email = null;
    public ?string $name = null;


    public function mount(): void
    {
        $this->templateSlug = request('templateSlug', 'bQKax93zzA41j8');

        $this->name = request('name', '');
             $this->email = request('email', '');
    }
}
