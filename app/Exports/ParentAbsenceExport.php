<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;

class ParentAbsenceExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected Collection $absences;
    protected string $studentName;
    protected string $startDate;
    protected string $endDate;

    public function __construct(Collection $absences, string $studentName = '', string $startDate = '', string $endDate = '')
    {
        $this->absences    = $absences;
        $this->studentName = $studentName;
        $this->startDate   = $startDate;
        $this->endDate     = $endDate;
    }

    public function collection(): Collection
    {
        return $this->absences->map(function ($absence, $index) {
            $status = $absence->status;
            $statusDetail = $absence->checkout_time ? $status . ' / PULANG' : $status;

            return [
                'no'          => $index + 1,
                'nama'        => $absence->student->name ?? 'N/A',
                'kelas'       => $absence->student->class->name ?? 'N/A',
                'tanggal'     => $absence->attendance_time->format('d-m-Y'),
                'waktu_masuk' => in_array($absence->status, ['Sakit', 'Izin', 'Alfa']) ? '-' : $absence->attendance_time->format('H:i'),
                'waktu_pulang'=> $absence->checkout_time ? $absence->checkout_time->format('H:i') : '-',
                'status'      => $statusDetail,
                'keterangan'  => $absence->notes ?? '-',
                'dikoreksi'   => $absence->corrected_by ?? 'Otomatis',
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama Anak', 'Kelas', 'Tanggal', 'Waktu Masuk', 'Waktu Pulang', 'Status', 'Keterangan', 'Dikoreksi Oleh'];
    }

    public function title(): string
    {
        return 'Riwayat Absensi';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 8,
            'D' => 14,
            'E' => 13,
            'F' => 14,
            'G' => 18,
            'H' => 20,
            'I' => 20,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1E40AF']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet     = $event->sheet->getDelegate();
                $dataCount = $this->absences->count();

                $sheet->insertNewRowBefore(1, 2);

                $lastRow = $dataCount + 3;

                $title = $this->studentName ? "Riwayat Absensi - {$this->studentName}" : "Riwayat Absensi";
                $sheet->setCellValue('A1', $title);

                $periode = '';
                if ($this->startDate && $this->endDate) {
                    $periode = "Periode: {$this->startDate} s/d {$this->endDate}";
                }
                $sheet->setCellValue('A2', $periode);

                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

                $sheet->getStyle("A4:I{$lastRow}")->getAlignment()->setHorizontal('center');
                $sheet->getStyle('B4:B' . $lastRow)->getAlignment()->setHorizontal('left');
                $sheet->getStyle('H4:I' . $lastRow)->getAlignment()->setHorizontal('left');

                $sheet->getStyle("A3:I{$lastRow}")->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}