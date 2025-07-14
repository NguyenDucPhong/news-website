<?php

namespace App\Filament\Admin\Resources\PatientResource\Pages;

use App\Filament\Admin\Resources\PatientResource;
use App\Filament\Admin\Resources\PatientResource\Widgets\PatientOverview;
use App\Models\Patient;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;

class ListPatients extends ListRecords
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

//    protected function getHeaderWidgets(): array
//    {
//        return [
//            PatientOverview::make([
////                'type' => 'rabbit', truyen tham so sang widget
//            ]),
//        ];
//    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()->icon('heroicon-m-user-group'),
            'dog' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'dog'))
                ->badge(Patient::query()->where('type', 'dog')->count())->badgeColor('primary')
                ->icon('heroicon-m-user'),
//                ->iconPosition(IconPosition::After),
            'cat' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'cat')),
            'rabbit' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'rabbit')),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
//        return 'dog';
        return 'all';
    }
}
