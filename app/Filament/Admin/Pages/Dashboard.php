<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Admin\Resources\PatientResource\Widgets\PatientOverview;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home'; // tuỳ chỉnh icon nếu muốn

    protected static ?string $activeNavigationIcon = 'heroicon-s-home';

    protected function getHeaderWidgets(): array
    {
        return [
            PatientOverview::class,
            // Thêm các widget khác nếu cần
        ];
    }
}
