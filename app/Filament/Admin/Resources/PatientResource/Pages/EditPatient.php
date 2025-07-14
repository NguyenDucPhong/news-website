<?php

namespace App\Filament\Admin\Resources\PatientResource\Pages;

use App\Filament\Admin\Resources\PatientResource;
use App\Models\Patient;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditPatient extends EditRecord
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeSave(): void
    {
        $patient = $this->getRecord();
        // Store the original patient data in cache for 5 minutes
        Cache::put("patient_undo_{$patient->id}", $patient->toArray(), now()->addMinutes(5));
    }

    protected function getSavedNotification(): ?Notification
    {
        $user = auth()->user();

        $patient = $this->getRecord();

        return Notification::make()
            ->title('Patient updated')
            ->success()
            ->body('The patient has been saved successfully.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->url('/admin/patients/' . $patient->id . '/edit')
                    ->openUrlInNewTab(),
                Action::make('undo')
                    ->color('gray')
                    ->url(route('admin.patients.undo', ['patient' => $patient->id]))
                    ->close(),
            ]);
    }
}
