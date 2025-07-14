<?php

namespace App\Filament\Admin\Resources\PatientResource\Pages;

use App\Filament\Admin\Resources\PatientResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back')
                ->url(PatientResource::getUrl('index'))
                ->color('gray'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('date_of_birth')
                    ->date('d/m/Y'),
                Infolists\Components\TextEntry::make('type'),
                Infolists\Components\TextEntry::make('owner.name')
                    ->label('Owner Name'),
            ]);
    }
}
