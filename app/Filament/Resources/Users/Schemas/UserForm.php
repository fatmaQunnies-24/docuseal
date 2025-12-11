<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Forms\Components\Grid::make(2)
                Section::make('User Info')

                    ->schema([
                        TextInput::make('name')
                            ->label('User Name')
                            ->rules([
                                'string',
                                'required',
                                'min:3',
                                'max:50',
                                // 'regex:/^[\p{Arabic}a-zA-Z0-9\s]+$/u',
                            ]),

                        TextInput::make('email')
                            ->label('User Email')
                            // ->reactive()
                            ->unique(
                                table: User::class,
                                column: 'email',
                                ignoreRecord: true,
                                modifyRuleUsing: fn(\Illuminate\Validation\Rules\Unique $rule) =>
                                $rule->whereNull('deleted_at')
                            )->rules([
                                'required'
                            ]),


                        TextInput::make('password')
                            ->label('Password')
                            ->password()->minLength(8)
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')->rules([
                                'nullable',
                                'min:8',
                                'max:255',
                                'regex:/[a-z]/',
                                'regex:/[A-Z]/',
                                'regex:/[0-9]/',
                                'regex:/[@$!%*#?&]/',

                            ])->afterStateHydrated(function ($component, $state) {
                                $component->extraAttributes(['data-plain-password' => $state]);
                            })
                            ->dehydrateStateUsing(function ($state, $record, $component) {
                                $component->getLivewire()->plainPassword = $state;
                                return Hash::make($state);
                            })->confirmed()->revealable()->helperText('Password must be 8-255 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.'),

                        TextInput::make('password_confirmation')
                            ->label('Password Confirmation')
                            ->password()
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->revealable()
                            ->same('password')->rules(['nullable']),


 
                    ]),

            ]);
    }
}
