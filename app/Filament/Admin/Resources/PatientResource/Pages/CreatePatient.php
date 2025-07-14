<?php

namespace App\Filament\Admin\Resources\PatientResource\Pages;

use App\Filament\Admin\Resources\PatientResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Dinh nghia lai data truoc khi luu vao CSDL

        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Patient registered')
            ->success()
            ->body('The patient has been created successfully.');
    }
}
