@extends('layouts.adminlte')

@section('title', 'Rekap Absensi')

@section('content')
<div class="space-y-4">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Rekap Absensi</h2>
            <p class="text-sm text-gray-500 mt-1">
                Kelas: <span class="font-bold text-indigo-600">{{ $class->name }}</span>
                &bull; Periode: <span class="font-bold text-gray-800">{{ $startDate->format('d/m/Y') }} &ndash; {{ $endDate->format('d/m/Y') }}</span>
            </p>
        </div>
        <nav class="flex text-sm font-medium text-gray-500 space-x-2" aria-label="Breadcrumb">
            <a href="{{ route('walikelas.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 transition">Dashboard</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-600">Rekap Absensi</span>
        </nav>
    </div>

    {{-- CARD UTAMA --}}
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

        {{-- TOOLBAR --}}
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col lg:flex-row justify-between items-start lg:items-end gap-3">

            {{-- Filter Bulan & Tahun --}}
            <form action="{{ route('walikelas.report.monthly_recap') }}" method="GET"
                  class="flex flex-wrap items-end gap-3">

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Bulan</label>
                    <select name="month"
                            class="px-3 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 text-sm font-semibold text-gray-700 bg-white shadow-sm">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $startDate->month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Tahun</label>
                    <select name="year"
                            class="px-3 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 text-sm font-semibold text-gray-700 bg-white shadow-sm">
                        @foreach(range(now()->year - 2, now()->year + 1) as $y)
                            <option value="{{ $y }}" {{ $startDate->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition">
                    <i class="fas fa-filter mr-1"></i> Tampilkan
                </button>
            </form>

            {{-- Kanan: Search + Export --}}
            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Cari Siswa</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input type="text" id="searchInput" placeholder="Nama, NISN, atau NIS..."
                               class="pl-8 pr-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 text-sm text-gray-700 bg-white shadow-sm w-52">
                    </div>
                </div>

                <a href="{{ route('walikelas.report.monthly_recap.export', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}"
                   target="_blank"
                   class="inline-flex items-center px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition hover:-translate-y-0.5">
                    <i class="fas fa-file-excel mr-2"></i> Download Excel
                </a>
            </div>
        </div>

        {{-- LEGENDA --}}
        <div class="px-5 py-2.5 bg-gray-50 border-b border-gray-100 flex flex-wrap items-center gap-4">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Keterangan</span>
            @foreach(['Hadir'=>'bg-green-100 text-green-700','Terlambat'=>'bg-amber-100 text-amber-700','Sakit'=>'bg-cyan-100 text-cyan-700','Izin'=>'bg-blue-100 text-blue-700','Alfa'=>'bg-red-100 text-red-700'] as $label => $cls)
                <span class="inline-flex items-center gap-1.5 text-xs">
                    <span class="px-2 py-0.5 rounded-md font-bold {{ $cls }}">{{ strtoupper($label[0]) }}</span>
                    <span class="text-gray-500">{{ $label }}</span>
                </span>
            @endforeach
            <span class="inline-flex items-center gap-1.5 text-xs">
                <span class="px-2 py-0.5 rounded-md font-bold bg-gray-100 text-gray-400">—</span>
                <span class="text-gray-500">Libur / tidak masuk sekolah</span>
            </span>
        </div>

        {{-- TABEL MATRIKS --}}
        {{-- Data disiapkan oleh JS dari $matrixData yang di-encode --}}
        <div class="overflow-x-auto">
            <table class="border-collapse text-xs w-full" id="matrixTable">
                <thead>
                    {{-- Baris 1: header tanggal --}}
                    <tr id="headerDates" class="bg-gray-800 text-white">
                        <th class="sticky left-0 z-10 bg-gray-800 px-4 py-3 text-left text-xs font-bold uppercase tracking-wider whitespace-nowrap min-w-[180px]">
                            Nama Siswa
                        </th>
                        {{-- kolom tanggal di-generate JS --}}
                        <th class="px-2 py-3 text-center font-bold text-xs uppercase tracking-wider whitespace-nowrap w-8 bg-green-600">H</th>
                        <th class="px-2 py-3 text-center font-bold text-xs uppercase tracking-wider whitespace-nowrap w-8 bg-orange-500">T</th>
                        <th class="px-2 py-3 text-center font-bold text-xs uppercase tracking-wider whitespace-nowrap w-8 bg-cyan-600">S</th>
                        <th class="px-2 py-3 text-center font-bold text-xs uppercase tracking-wider whitespace-nowrap w-8 bg-blue-600">I</th>
                        <th class="px-2 py-3 text-center font-bold text-xs uppercase tracking-wider whitespace-nowrap w-8 bg-red-600">A</th>
                    </tr>
                    {{-- Baris 2: nama hari --}}
                    <tr id="headerDays" class="bg-gray-700 text-gray-300">
                        <th class="sticky left-0 z-10 bg-gray-700 px-4 py-1.5 text-left text-xs text-gray-400">
                            {{ $startDate->translatedFormat('F Y') }}
                        </th>
                        {{-- nama hari di-generate JS --}}
                        <th colspan="6" class="bg-gray-900 text-center text-xs text-gray-400 py-1.5">Rekap</th>
                    </tr>
                </thead>
                <tbody id="matrixBody" class="divide-y divide-gray-100 bg-white">
                    {{-- Diisi JS --}}
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-500" id="pageInfo"></p>
            <div id="pageBtns" class="flex gap-1.5"></div>
        </div>

    </div>
</div>

{{-- DATA DARI BLADE → JS --}}
@php
    /*
     * Pivot $logData (koleksi Absence) menjadi:
     * matrixData = [
     *   [ 'no'=>1, 'name'=>'...', 'nisn'=>'...', 'nis'=>'...', 'days'=>['01'=>'Hadir', '02'=>'', ...] ]
     * ]
     * Key 'days' berindeks string '01'..'31' sesuai jumlah hari bulan.
     */
    $daysInMonth  = $startDate->daysInMonth;   // misal: 30
    $monthPad     = $startDate->format('Y-m'); // misal: '2026-06'

    // Group per siswa
    $grouped = $logData->groupBy('student_id');

    $matrixRows = [];
    $no = 1;
    foreach ($grouped as $studentId => $records) {
        $student = $records->first()->student;
        $dayMap  = [];
        foreach ($records as $rec) {
            $dayKey = $rec->attendance_time->format('d'); // '01'..'31'
            $dayMap[$dayKey] = $rec->status;
        }
        $matrixRows[] = [
            'no'   => $no++,
            'name' => $student->name ?? '-',
            'nisn' => $student->nisn ?? '-',
            'nis'  => $student->nis  ?? '-',
            'days' => $dayMap,
        ];
    }

    // Hari libur: koleksi kolom tanggal yang weekend di bulan ini
    $weekendDays = [];
    for ($d = 1; $d <= $daysInMonth; $d++) {
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $monthPad . '-' . str_pad($d, 2, '0', STR_PAD_LEFT));
        if ($date->isWeekend()) {
            $weekendDays[] = str_pad($d, 2, '0', STR_PAD_LEFT);
        }
    }

    $dayNames = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
    $dayLabels = [];
    for ($d = 1; $d <= $daysInMonth; $d++) {
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $monthPad . '-' . str_pad($d, 2, '0', STR_PAD_LEFT));
        $dayLabels[str_pad($d, 2, '0', STR_PAD_LEFT)] = $dayNames[$date->dayOfWeek];
    }
@endphp

<script>
const MATRIX_DATA   = @json($matrixRows);
const DAYS_IN_MONTH = {{ $daysInMonth }};
const WEEKEND_DAYS  = @json($weekendDays);
const DAY_LABELS    = @json($dayLabels);
const PAGE_SIZE     = 10;

let currentPage = 1;
let filteredData = [...MATRIX_DATA];

const STATUS_CLASS = {
    'Hadir'     : 'bg-green-100 text-green-700',
    'Terlambat' : 'bg-amber-100 text-amber-700',
    'Sakit'     : 'bg-cyan-100 text-cyan-700',
    'Izin'      : 'bg-blue-100 text-blue-700',
    'Alfa'      : 'bg-red-100 text-red-700',
};
const STATUS_SHORT = {
    'Hadir':'H','Terlambat':'T','Sakit':'S','Izin':'I','Alfa':'A'
};

function buildHeaders() {
    const trDate = document.getElementById('headerDates');
    const trDay  = document.getElementById('headerDays');

    // Hapus kolom tanggal lama (sisakan th pertama dan 6 th rekap terakhir)
    const fixedTailDate = 5; // H T S I A %
    const fixedTailDay  = 1; // "Rekap" colspan

    // Kumpulkan semua <th> lama selain fixed
    const existingDateThs = trDate.querySelectorAll('th');
    const existingDayThs  = trDay.querySelectorAll('th');

    // Hapus semua kecuali [0] dan [last fixedTailDate]
    // Rebuild: clear isi, tambahkan ulang
    // Simpan th[0] (nama siswa) dan 6 th rekap terakhir
    const nameTh     = existingDateThs[0].cloneNode(true);
    const rekapThs   = [...existingDateThs].slice(-fixedTailDate).map(n => n.cloneNode(true));
    const nameDayTh  = existingDayThs[0].cloneNode(true);
    const rekapDayTh = existingDayThs[existingDayThs.length - 1].cloneNode(true);

    trDate.innerHTML = '';
    trDay.innerHTML  = '';

    trDate.appendChild(nameTh);
    trDay.appendChild(nameDayTh);

    // Tambah kolom per tanggal
    for (let d = 1; d <= DAYS_IN_MONTH; d++) {
        const key = String(d).padStart(2,'0');
        const isWe = WEEKEND_DAYS.includes(key);

        const th = document.createElement('th');
        th.className = 'px-1 py-3 text-center font-bold text-xs whitespace-nowrap w-7 ' +
                       (isWe ? 'bg-gray-700 text-gray-400' : '');
        th.textContent = d;
        trDate.appendChild(th);

        const thDay = document.createElement('th');
        thDay.className = 'px-1 py-1.5 text-center text-xs whitespace-nowrap w-7 ' +
                          (isWe ? 'bg-gray-700 text-gray-500' : 'text-gray-400');
        thDay.textContent = DAY_LABELS[key];
        trDay.appendChild(thDay);
    }

    rekapThs.forEach(th => trDate.appendChild(th));
    trDay.appendChild(rekapDayTh);
}

function renderPage() {
    const tbody = document.getElementById('matrixBody');
    tbody.innerHTML = '';

    const total = filteredData.length;
    const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
    if (currentPage > totalPages) currentPage = totalPages;

    const start = (currentPage - 1) * PAGE_SIZE;
    const slice = filteredData.slice(start, start + PAGE_SIZE);

    slice.forEach((row, idx) => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-indigo-50/30 transition duration-100 student-row';
        tr.dataset.name = row.name.toLowerCase();
        tr.dataset.nisn = row.nisn;
        tr.dataset.nis  = row.nis;

        // Kolom nama + nisn + nis
        const tdName = document.createElement('td');
        tdName.className = 'sticky left-0 z-10 bg-white px-4 py-2 text-sm font-semibold text-gray-800 whitespace-nowrap border-r border-gray-100';
        tdName.innerHTML = `
            <div class="font-bold text-gray-800 text-xs">${start + idx + 1}. ${row.name}</div>
            <div class="text-gray-400 text-xs font-normal mt-0.5">NISN: ${row.nisn} &nbsp;|&nbsp; NIS: ${row.nis}</div>
        `;
        tr.appendChild(tdName);

        // Kolom per tanggal
        let cH=0, cT=0, cS=0, cI=0, cA=0, cEfektif=0;
        for (let d = 1; d <= DAYS_IN_MONTH; d++) {
            const key    = String(d).padStart(2,'0');
            const isWe   = WEEKEND_DAYS.includes(key);
            const status = row.days[key] || '';
            const td     = document.createElement('td');

            if (isWe) {
                td.className = 'w-7 h-8 text-center bg-gray-50 border-r border-gray-100';
                td.innerHTML = '<span class="text-gray-300 text-xs">—</span>';
            } else {
                cEfektif++;
                if (status) {
                    const cls = STATUS_CLASS[status] || 'bg-gray-100 text-gray-500';
                    const short = STATUS_SHORT[status] || status[0];
                    td.className = `w-7 h-8 text-center border-r border-gray-100 cursor-default`;
                    td.innerHTML = `<span class="inline-flex items-center justify-center w-6 h-6 rounded-md text-xs font-bold ${cls}" title="${status}">${short}</span>`;
                    if (status === 'Hadir')     cH++;
                    else if (status === 'Terlambat') cT++;
                    else if (status === 'Sakit')  cS++;
                    else if (status === 'Izin')   cI++;
                    else if (status === 'Alfa')   cA++;
                } else {
                    td.className = 'w-7 h-8 text-center border-r border-gray-100';
                    td.innerHTML = '<span class="text-gray-200 text-xs">·</span>';
                }
            }
            tr.appendChild(td);
        }

        // Kolom rekap
        
        const rekapCols = [
            { val: cH, cls: 'bg-green-900 text-white'     },
            { val: cT, cls: 'bg-amber-800 text-white'    },
            { val: cS, cls: 'bg-cyan-800 text-white'     },
            { val: cI, cls: 'bg-blue-800 text-white'     },
            { val: cA, cls: 'bg-red-800 text-white'      },
            
        ];
        rekapCols.forEach(col => {
            const td = document.createElement('td');
            td.className = `w-8 h-8 text-center text-xs font-semibold border-r border-gray-700 ${col.cls}`;
            td.textContent = col.val;
            tr.appendChild(td);
        });

        tbody.appendChild(tr);
    });

    // Kosong
    if (slice.length === 0) {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td colspan="100" class="py-10 text-center text-gray-400 italic text-sm">
            Tidak ada data siswa pada periode ini.
        </td>`;
        tbody.appendChild(tr);
    }

    // Info halaman
    const showing = total === 0 ? 0 : start + 1;
    const showingEnd = Math.min(start + PAGE_SIZE, total);
    document.getElementById('pageInfo').textContent =
        `Menampilkan ${showing}–${showingEnd} dari ${total} siswa`;

    // Tombol pagination
    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const container = document.getElementById('pageBtns');
    container.innerHTML = '';

    if (totalPages <= 1) return;

    const makeBtn = (label, page, isActive, isDisabled) => {
        const btn = document.createElement('button');
        btn.textContent = label;
        btn.disabled = isDisabled;
        btn.className = [
            'px-3 py-1 rounded-lg text-xs font-semibold border transition',
            isActive
                ? 'bg-indigo-600 text-white border-indigo-600'
                : 'bg-white text-gray-600 border-gray-300 hover:bg-indigo-50',
            isDisabled ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer',
        ].join(' ');
        if (!isDisabled) btn.addEventListener('click', () => { currentPage = page; renderPage(); });
        return btn;
    };

    container.appendChild(makeBtn('‹', currentPage - 1, false, currentPage === 1));

    // Tampilkan maks 5 nomor halaman
    let start = Math.max(1, currentPage - 2);
    let end   = Math.min(totalPages, start + 4);
    if (end - start < 4) start = Math.max(1, end - 4);

    for (let p = start; p <= end; p++) {
        container.appendChild(makeBtn(p, p, p === currentPage, false));
    }

    container.appendChild(makeBtn('›', currentPage + 1, false, currentPage === totalPages));
}

// Search
document.getElementById('searchInput').addEventListener('input', function () {
    const kw = this.value.toLowerCase().trim();
    filteredData = MATRIX_DATA.filter(r =>
        r.name.toLowerCase().includes(kw) ||
        r.nisn.includes(kw) ||
        r.nis.includes(kw)
    );
    currentPage = 1;
    renderPage();
});

// Init
buildHeaders();
renderPage();
</script>
@stop