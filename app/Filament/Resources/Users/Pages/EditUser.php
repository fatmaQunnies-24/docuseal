<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Users\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Action::make('openDocuSeal')
                ->label('Sign Document')
                ->url(fn() => url('/admin/docu-seal-embed?templateSlug=RzukLCQmykNu38&email=' . $this->record->email . '&name=' . $this->record->name))
                ->openUrlInNewTab(),


            DeleteAction::make(),
        ];
    }
}
