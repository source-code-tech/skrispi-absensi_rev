<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AbsenceRekapExport implements FromCollection, WithHeadings, WithTitle, WithColumnWidths, WithEvents
{
    protected Collection $rekap;
    protected Carbon $startDate;
    protected Carbon $endDate;
    protected string $className;

    public function __construct(Collection $rekap, Carbon $startDate, Carbon $endDate, string $className)
    {
        $this->rekap     = $rekap;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->className = $className;
    }

    public function collection(): Collection
    {
        return $this->rekap->map(function ($item, $index) {
            return [
                'no'        => $index + 1,
                'nisn'      => $item['student']->nisn ?? '-',
                'nis'       => $item['student']->nis  ?? '-',
                'nama'      => $item['student']->name ?? '-',
                'kelas'     => $item['kelas'],
                'hadir'     => $item['hadir'],
                'terlambat' => $item['terlambat'],
                'sakit'     => $item['sakit'],
                'izin'      => $item['izin'],
                'alfa'      => $item['alfa'],
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'NISN', 'NIS', 'Nama Siswa', 'Kelas', 'Hadir', 'Terlambat', 'Sakit', 'Izin', 'Alfa'];
    }

    public function title(): string
    {
        return 'Rekap Absensi';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 16,
            'C' => 12,
            'D' => 28,
            'E' => 10,
            'F' => 10,
            'G' => 12,
            'H' => 10,
            'I' => 10,
            'J' => 10,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet     = $event->sheet->getDelegate();
                $dataCount = $this->rekap->count();

                // ── Insert 2 baris judul di atas ──
                $sheet->insertNewRowBefore(1, 2);
                $lastRow = $dataCount + 3; // 2 judul + 1 heading + data

                // ── BARIS 1: Judul ──
                $sheet->setCellValue('A1', 'Rekap Absensi - ' . $this->className);
                $sheet->mergeCells('A1:J1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A5F']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(28);

                // ── BARIS 2: Periode ──
                $sheet->setCellValue('A2', 'Periode: ' . $this->startDate->format('d/m/Y') . ' s/d ' . $this->endDate->format('d/m/Y') . '  |  Dicetak: ' . now()->format('d/m/Y H:i'));
                $sheet->mergeCells('A2:J2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 10, 'color' => ['argb' => 'FF374151']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── BARIS 3: Style heading kolom ──
                $sheet->getStyle('A3:J3')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 9, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E40AF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(3)->setRowHeight(18);

                // Warna header per kolom status
                $headerColors = [
                    'F3' => 'FF16A34A', // Hadir - hijau
                    'G3' => 'FFF59E0B', // Terlambat - kuning
                    'H3' => 'FF3B82F6', // Sakit - biru
                    'I3' => 'FF60A5FA', // Izin - biru muda
                    'J3' => 'FFEF4444', // Alfa - merah
                ];
                foreach ($headerColors as $cell => $argb) {
                    $sheet->getStyle($cell)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB($argb);
                }

                // ── BARIS DATA: warna kolom status ──
                if ($dataCount > 0) {
                    $sheet->getStyle("F4:F{$lastRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFdcfce7'); // Hadir
                    $sheet->getStyle("G4:G{$lastRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFfef9c3'); // Terlambat
                    $sheet->getStyle("H4:H{$lastRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFdbeafe'); // Sakit
                    $sheet->getStyle("I4:I{$lastRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFe0f2fe'); // Izin
                    $sheet->getStyle("J4:J{$lastRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFfee2e2'); // Alfa

                    // Zebra stripe kolom A-E
                    for ($r = 4; $r <= $lastRow; $r++) {
                        if ($r % 2 === 1) {
                            $sheet->getStyle("A{$r}:E{$r}")->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFF8FAFC');
                        }
                        $sheet->getRowDimension($r)->setRowHeight(16);
                    }
                }

                // ── BORDER ──
                $sheet->getStyle("A3:J{$lastRow}")->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // ── ALIGNMENT ──
                $sheet->getStyle("A4:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("B4:C{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E4:J{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // ── FREEZE PANE ──
                $sheet->freezePane('A4');

                // ── FONT GLOBAL ──
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
            },
        ];
    }
}