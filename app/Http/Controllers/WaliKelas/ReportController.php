<?php

namespace App\Http\Controllers\WaliKelas;

use App\Models\Absence;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Exports\AbsenceRekapExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Models\ClassModel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Holiday;

class ReportController extends Controller
{
    /**
     * Helper: ambil log absensi mentah (1 baris = 1 kejadian absensi)
     * untuk kelas yang diampu Wali Kelas, berdasarkan rentang tanggal
     * bebas. Diurutkan tanggal terbaru di atas. Dipakai untuk
     * ditampilkan di halaman web (monthly_recap.blade.php).
     */
    private function getLogData(Carbon $startDate, Carbon $endDate, int $classId)
    {
        return Absence::with('student')
            ->whereBetween('attendance_time', [$startDate, $endDate])
            ->whereHas('student', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->join('students', 'absences.student_id', '=', 'students.id')
            ->orderBy('students.name', 'asc')
            ->orderBy('absences.attendance_time', 'asc')
            ->select('absences.*')
            ->get();
    }

    /**
     * Helper: ambil data rekap TOTAL per status (Hadir, Terlambat,
     * Sakit, Izin, Alfa, Total) untuk kelas yang diampu Wali Kelas,
     * berdasarkan rentang tanggal bebas. Dipakai KHUSUS untuk export
     * Excel, bukan untuk ditampilkan di halaman web. Strukturnya
     * disamakan dengan Admin\ReportController::getRekapData() agar
     * bisa pakai Export class yang sama (AbsenceRekapExport).
     */
    private function getRekapData(Carbon $startDate, Carbon $endDate, int $classId)
    {
        $absences = Absence::with(['student.class'])
            ->whereBetween('attendance_time', [$startDate, $endDate])
            ->whereHas('student', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->join('students', 'absences.student_id', '=', 'students.id')
            ->orderBy('students.name', 'asc')
            ->orderBy('absences.attendance_time', 'asc')
            ->select('absences.*')
            ->get();

        return $absences
            ->groupBy('student_id')
            ->map(function ($records) {
                $student = $records->first()->student;
                return [
                    'student'   => $student,
                    'kelas'     => $student->class->name ?? '-',
                    'hadir'     => $records->where('status', 'Hadir')->count(),
                    'terlambat' => $records->where('status', 'Terlambat')->count(),
                    'sakit'     => $records->where('status', 'Sakit')->count(),
                    'izin'      => $records->where('status', 'Izin')->count(),
                    'alfa'     => $records->where('status', 'Alfa')->count(),
                    'total'     => $records->count(),
                ];
            })
            ->values();
    }

   
public function monthlyRecap(Request $request)
{
    $user  = Auth::user();
    $class = $user->homeroomTeacher->class ?? null;

    if (!$class) {
        return redirect()->route('walikelas.dashboard')
                         ->with('error', 'Anda belum mengampu kelas.');
    }

    // Kalau ada parameter month/year dari dropdown → pakai itu.
    // Kalau tidak ada (akses pertama kali) → default bulan & tahun ini.
    if ($request->filled('month') && $request->filled('year')) {
        $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfDay();
        $endDate   = $startDate->copy()->endOfMonth()->endOfDay();
    } elseif ($request->filled('start_date') && $request->filled('end_date')) {
        // Tetap support parameter lama (misal dari link export yang sudah ada)
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate   = Carbon::parse($request->end_date)->endOfDay();
    } else {
        // Default: bulan ini
        $startDate = Carbon::now()->startOfMonth()->startOfDay();
        $endDate   = Carbon::now()->endOfMonth()->endOfDay();
    }

    $logData = $this->getLogData($startDate, $endDate, $class->id);

    return view('walikelas.report.monthly_recap', [
        'class'     => $class,
        'logData'   => $logData,
        'startDate' => $startDate,
        'endDate'   => $endDate,
    ]);
}

    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $class = $user->homeroomTeacher->class ?? null;

        if (!$class) {
            return redirect()->route('walikelas.dashboard')
                ->with('error', 'Anda belum mengampu kelas.');
        }

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $absences = Absence::with(['student.class'])
            ->whereBetween('attendance_time', [$startDate, $endDate])
            ->whereHas('student', function ($q) use ($class) {
                $q->where('class_id', $class->id);
            })
            ->orderBy('attendance_time', 'asc')
            ->get();

        $data = [
            'absences' => $absences,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'class' => $class,
        ];

        $pdf = Pdf::loadView('admin.reports.pdf_template', $data);

        $fileName = "Laporan_Absensi_" . $class->name . ".pdf";

        return $pdf->stream($fileName);
    }

    
   public function exportMonthlyRecap(Request $request)
{
    $user  = Auth::user();
    $class = $user->homeroomTeacher->class ?? null;

    if (!$class) {
        return redirect()->route('walikelas.dashboard')
                         ->with('error', 'Anda belum mengampu kelas.');
    }

    // Parameter sama persis dengan monthlyRecap() dan link di blade
    if ($request->filled('month') && $request->filled('year')) {
        $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfDay();
        $endDate   = $startDate->copy()->endOfMonth()->endOfDay();
    } elseif ($request->filled('start_date') && $request->filled('end_date')) {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate   = Carbon::parse($request->end_date)->endOfDay();
    } else {
        $startDate = Carbon::now()->startOfMonth()->startOfDay();
        $endDate   = Carbon::now()->endOfMonth()->endOfDay();
    }

    $className = $class->name ?? 'Kelas';
    $monthName = $startDate->translatedFormat('F Y');

    // Ambil logData SAMA PERSIS dengan method monthlyRecap() & blade
    $logData = $this->getLogData($startDate, $endDate, $class->id);

    // Pivot ke format yang dibutuhkan MonthlyRecapExport
    // Key 'status_by_day' pakai format 'd' → '01'..'31' (sama dengan blade)
    $grouped   = $logData->groupBy('student_id');
    $recapData = [];
    $no        = 1;

    foreach ($grouped as $studentId => $records) {
        $student = $records->first()->student;
        $dayMap  = [];

        foreach ($records as $rec) {
            $dayKey          = Carbon::parse($rec->attendance_time)->format('d');
            $dayMap[$dayKey] = $rec->status;
        }

        $recapData[] = [
            'no'            => $no++,
            'name'          => $student->name ?? '-',
            'nisn'          => $student->nisn ?? '-',
            'nis'           => $student->nis   ?? '-',
            'status_by_day' => $dayMap,
        ];
    }

    $fileName = "Rekap_{$className}_{$startDate->format('Y-m')}.xlsx";

    return Excel::download(
        new \App\Exports\MonthlyRecapExport(
            $recapData,
            $monthName,
            $className,
            $startDate,
            $endDate
        ),
        $fileName
    );
}
}