<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('User Name')
                    ->searchable()->sortable(),
                TextColumn::make('email')->label('User Email')
                    ->searchable()->sortable(),
            ])
            ->filters([])->headerActions([

            ])
            ->actions([
                ActionGroup::make([
        EditAction::make(),
                    DeleteAction::make()
                        ->label('Delete')
                        ->hidden(fn($record) => $record->id === Auth::id()),



                ]),
            ])
            ->bulkActions([
               BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Delete')
                        ->action(function ($records) {
                            $currentUserId = Auth::id();
                            $recordsToDelete = $records->reject(fn($record) => $record->id === $currentUserId);


                            if ($records->contains('id', $currentUserId)) {
                                Notification::make()
                                    ->title('Cannot_Delete_Current_User')
                                    ->body('Cannot_Delete_Current_User_Desc')

                                    ->danger()
                                    ->send();

                                if ($recordsToDelete->isNotEmpty()) {
                                    $recordsToDelete->each->delete();
                                }
                            } else {

                                $records->each->delete();
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
