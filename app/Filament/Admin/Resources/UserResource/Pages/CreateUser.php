<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Name')
                ->description('Give the category a clear and unique name')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                ]),
            Step::make('Email')
                ->description('Add some extra details')
                ->schema([
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->rules(['unique:users,email'])
                        ->validationMessages([
                            'unique' => 'Email này đã được sử dụng',
                        ]),
                    TextInput::make('password')
                        ->password()
                        ->required()
                        ->maxLength(255),
                ]),
        ];
    }
}
