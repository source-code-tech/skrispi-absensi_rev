<?php

namespace App\Exports;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MonthlyRecapExport implements FromArray, WithEvents
{
    protected array $recapData;
    protected string $monthName;
    protected string $className;
    protected Carbon $startDate;
    protected Carbon $endDate;
    protected array $dates;

    public function __construct(
        array $recapData,
        string $monthName,
        string $className,
        Carbon $startDate,
        Carbon $endDate
    ) {
        $this->recapData = $recapData;
        $this->monthName = $monthName;
        $this->className = $className;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;

        $period      = CarbonPeriod::create($startDate, $endDate);
        $this->dates = iterator_to_array($period);
    }

    public function array(): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $dates      = $this->dates;
                $totalDates = count($dates);

                $colDateStart = 4; // kolom D
                $colDateEnd   = 3 + $totalDates;
                $colH         = $colDateEnd + 1;
                $colT         = $colDateEnd + 2;
                $colS         = $colDateEnd + 3;
                $colI         = $colDateEnd + 4;
                $colA         = $colDateEnd + 5; // kolom terakhir

                $col = fn(int $n) => \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($n);

                // ── BARIS 1: Judul ──
                $sheet->setCellValue('A1', 'Rekap Absensi Bulanan - ' . $this->className);
                $sheet->mergeCells('A1:' . $col($colA) . '1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A5F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(28);

                // ── BARIS 2: Periode ──
                $sheet->setCellValue('A2', 'Periode: ' . $this->monthName . '  |  Dicetak: ' . now()->format('d/m/Y H:i'));
                $sheet->mergeCells('A2:' . $col($colA) . '2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 10, 'color' => ['argb' => 'FF374151']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── BARIS 3: Header tanggal ──
                $sheet->setCellValue('A3', 'No');
                $sheet->setCellValue('B3', 'Nama Siswa');
                $sheet->setCellValue('C3', 'NISN');

                foreach ($dates as $i => $date) {
                    $sheet->setCellValueByColumnAndRow($colDateStart + $i, 3, $date->day);
                }

                foreach ([
                    $colH => 'H',
                    $colT => 'T',
                    $colS => 'S',
                    $colI => 'I',
                    $colA => 'A',
                ] as $c => $label) {
                    $sheet->setCellValueByColumnAndRow($c, 3, $label);
                }

                // ── BARIS 4: Sub-header hari ──
                $dayMap = ['Sun'=>'Min','Mon'=>'Sen','Tue'=>'Sel','Wed'=>'Rab','Thu'=>'Kam','Fri'=>'Jum','Sat'=>'Sab'];
                $sheet->setCellValue('A4', '');
                $sheet->setCellValue('B4', '');
                $sheet->setCellValue('C4', '');

                foreach ($dates as $i => $date) {
                    $dayName = $dayMap[$date->format('D')] ?? $date->format('D');
                    $sheet->setCellValueByColumnAndRow($colDateStart + $i, 4, $dayName);
                }

                foreach ([$colH, $colT, $colS, $colI, $colA] as $c) {
                    $sheet->setCellValueByColumnAndRow($c, 4, '');
                }

                // Style header baris 3 & 4
                $sheet->getStyle('A3:' . $col($colA) . '4')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 9, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E40AF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(3)->setRowHeight(18);
                $sheet->getRowDimension(4)->setRowHeight(14);

                // Warna weekend di header
                foreach ($dates as $i => $date) {
                    if ($date->isWeekend()) {
                        foreach ([3, 4] as $row) {
                            $sheet->getStyleByColumnAndRow($colDateStart + $i, $row)
                                ->getFill()->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FF64748B');
                        }
                    }
                }

                // Warna header kolom rekap
                $rekapHeaderColors = [
                    $colH => 'FF16A34A',
                    $colT => 'FFF59E0B',
                    $colS => 'FF3B82F6',
                    $colI => 'FF60A5FA',
                    $colA => 'FFEF4444',
                ];
                foreach ($rekapHeaderColors as $c => $argb) {
                    foreach ([3, 4] as $row) {
                        $sheet->getStyleByColumnAndRow($c, $row)
                            ->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB($argb);
                    }
                }

                // ── BARIS DATA (mulai baris 5) ──
                $statusColorMap = [
                    'Hadir'     => 'FF86EFAC',
                    'Pulang'    => 'FF86EFAC',
                    'Terlambat' => 'FFFDE68A',
                    'Sakit'     => 'FFBFDBFE',
                    'Izin'      => 'FFBAe6FD',
                    'Alfa'      => 'FFFCA5A5',
                ];
                $statusSymbol = [
                    'Hadir'     => 'H',
                    'Pulang'    => 'H',
                    'Terlambat' => 'T',
                    'Sakit'     => 'S',
                    'Izin'      => 'I',
                    'Alfa'      => 'A',
                ];

                $dataStartRow = 5;

                foreach ($this->recapData as $idx => $data) {
                    $row = $dataStartRow + $idx;

                    $sheet->setCellValue('A' . $row, $idx + 1);
                    $sheet->setCellValue('B' . $row, $data['name'] ?? '-');
                    $sheet->setCellValue('C' . $row, $data['nisn'] ?? '-');

                    $summary = ['Hadir' => 0, 'Terlambat' => 0, 'Sakit' => 0, 'Izin' => 0, 'Alfa' => 0];

                    foreach ($dates as $i => $date) {
                        $colIdx    = $colDateStart + $i;
                        $dateKey   = $date->format('d'); // '01'..'31'
                        $statusRaw = $data['status_by_day'][$dateKey] ?? null;

                        if ($date->isWeekend()) {
                            $sheet->setCellValueByColumnAndRow($colIdx, $row, '');
                            $sheet->getStyleByColumnAndRow($colIdx, $row)
                                ->getFill()->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFE2E8F0');
                        } elseif ($statusRaw === null) {
                            $sheet->setCellValueByColumnAndRow($colIdx, $row, '—');
                        } else {
                            $symbol = $statusSymbol[$statusRaw] ?? $statusRaw;
                            $sheet->setCellValueByColumnAndRow($colIdx, $row, $symbol);

                            if (isset($statusColorMap[$statusRaw])) {
                                $sheet->getStyleByColumnAndRow($colIdx, $row)
                                    ->getFill()->setFillType(Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB($statusColorMap[$statusRaw]);
                            }

                            $statusKey = ($statusRaw === 'Pulang') ? 'Hadir' : $statusRaw;
                            if (isset($summary[$statusKey])) {
                                $summary[$statusKey]++;
                            }
                        }
                    }

                    // Kolom rekap kanan (tanpa %)
                    $sheet->setCellValueByColumnAndRow($colH, $row, $summary['Hadir']);
                    $sheet->setCellValueByColumnAndRow($colT, $row, $summary['Terlambat']);
                    $sheet->setCellValueByColumnAndRow($colS, $row, $summary['Sakit']);
                    $sheet->setCellValueByColumnAndRow($colI, $row, $summary['Izin']);
                    $sheet->setCellValueByColumnAndRow($colA, $row, $summary['Alfa']);

                    // Warna kolom rekap
                    $sheet->getStyleByColumnAndRow($colH, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFdcfce7');
                    $sheet->getStyleByColumnAndRow($colT, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFfef9c3');
                    $sheet->getStyleByColumnAndRow($colS, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFdbeafe');
                    $sheet->getStyleByColumnAndRow($colI, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFe0f2fe');
                    $sheet->getStyleByColumnAndRow($colA, $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFfee2e2');

                    // Zebra stripe kolom A-C
                    if ($idx % 2 === 1) {
                        $sheet->getStyle('A' . $row . ':C' . $row)
                            ->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFF8FAFC');
                    }

                    $sheet->getRowDimension($row)->setRowHeight(16);
                }

                $lastRow = $dataStartRow + count($this->recapData) - 1;

                // ── BORDER ──
                $sheet->getStyle('A3:' . $col($colA) . $lastRow)
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // ── ALIGNMENT ──
                $sheet->getStyle('A5:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C5:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col($colDateStart) . '5:' . $col($colDateEnd) . $lastRow)
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($col($colH) . '5:' . $col($colA) . $lastRow)
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // ── LEBAR KOLOM ──
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(28);
                $sheet->getColumnDimension('C')->setWidth(16);

                for ($i = 0; $i < $totalDates; $i++) {
                    $sheet->getColumnDimensionByColumn($colDateStart + $i)->setWidth(4.5);
                }

                foreach ([$colH, $colT, $colS, $colI, $colA] as $c) {
                    $sheet->getColumnDimensionByColumn($c)->setWidth(5);
                }

                // ── FREEZE PANE ──
                $sheet->freezePane('D5');

                // ── FONT GLOBAL ──
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
            },
        ];
    }
}