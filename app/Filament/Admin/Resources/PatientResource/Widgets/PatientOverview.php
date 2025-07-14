<?php

namespace App\Filament\Admin\Resources\PatientResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Patient;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PatientOverview extends ChartWidget
{
//    public string $type = '';
    protected static ?string $heading = 'Patient Overview (Monthly)';
    protected static ?int $sort = 1; // Vị trí widget nếu nhiều cái

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Lấy tất cả các type khác nhau có trong bảng patients
        $types = Patient::select('type')->distinct()->pluck('type');

        $datasets = [];

        foreach ($types as $type) {
            $data = Patient::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->where('type', $type)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month');

            // Khởi tạo mảng 12 tháng mặc định là 0
            $monthlyData = array_fill(1, 12, 0);

            foreach ($data as $month => $count) {
                $monthlyData[$month] = $count;
            }

            $datasets[] = [
                'label' => ucfirst($type),
                'data' => array_values($monthlyData),
                'borderColor' => $this->getColorFromType($type),
                'backgroundColor' => $this->getBgColorFromType($type),
                'fill' => true,
                'tension' => 0.4,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
            ],
        ];
    }

    protected function getColorFromType(string $type): string
    {
        // Tạo màu từ hash của type
        $hash = crc32($type);
        return sprintf("#%06X", $hash & 0xFFFFFF); // chuyển thành mã HEX
    }

    protected function getBgColorFromType(string $type): string
    {
        $hex = ltrim($this->getColorFromType($type), '#');
        [$r, $g, $b] = sscanf($hex, "%02x%02x%02x");
        return "rgba($r, $g, $b, 0.2)";
    }

    protected function getType(): string
    {
        return 'line';
    }
}
