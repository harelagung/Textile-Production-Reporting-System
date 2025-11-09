<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 0.5mm;
            size: A4 portrait;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 2mm;
            background: white;
            line-height: 1.1;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 12px;
            font-weight: normal;
            margin: 3px 0;
        }

        /* Page breaks */
        .page-break {
            page-break-after: always;
        }

        /* SOLUSI UTAMA: CSS Table untuk layout 2 kolom */
        .layout-table {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .layout-cell {
            display: table-cell;
            width: 47%;
            vertical-align: top;
            padding-right: 6%;
        }

        .layout-cell:last-child {
            padding-right: 0;
        }

        .shift-section {
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 9px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            text-align: center;
            background-color: #f0f0f0;
            padding: 3px;
        }

        .operator-table,
        .construction-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 7px;
        }

        .operator-table th,
        .construction-table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            font-weight: bold;
            font-size: 6px;
        }

        .operator-table td,
        .construction-table td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            vertical-align: middle;
            font-size: 6px;
        }

        .text-left {
            text-align: left;
            padding-left: 3px;
        }

        .text-right {
            text-align: right;
            padding-right: 3px;
        }

        .total-row {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        /* Table column widths untuk kolom 47% - UPDATED WITH WORK HOURS AND DATE COLUMNS */
        .operator-table th:nth-child(1),
        .operator-table td:nth-child(1) {
            width: 10%;
        }

        .operator-table th:nth-child(2),
        .operator-table td:nth-child(2) {
            width: 25%;
        }

        .operator-table th:nth-child(3),
        .operator-table td:nth-child(3) {
            width: 8%;
        }

        .operator-table th:nth-child(4),
        .operator-table td:nth-child(4) {
            width: 15%;
        }

        .operator-table th:nth-child(5),
        .operator-table td:nth-child(5) {
            width: 8%;
        }

        .operator-table th:nth-child(6),
        .operator-table td:nth-child(6) {
            width: 12%;
        }

        .operator-table th:nth-child(7),
        .operator-table td:nth-child(7) {
            width: 22%;
        }

        .construction-table th:nth-child(1),
        .construction-table td:nth-child(1) {
            width: 8%;
        }

        .construction-table th:nth-child(2),
        .construction-table td:nth-child(2) {
            width: 45%;
        }

        .construction-table th:nth-child(3),
        .construction-table td:nth-child(3) {
            width: 12%;
        }

        .construction-table th:nth-child(4),
        .construction-table td:nth-child(4) {
            width: 20%;
        }

        .construction-table th:nth-child(5),
        .construction-table td:nth-child(5) {
            width: 15%;
        }

        .keterangan-title {
            font-size: 10px;
            font-weight: bold;
            margin: 15px 0 10px 0;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 3px;
        }

        .shift-construction-title {
            font-size: 8px;
            font-weight: bold;
            background-color: #e0e0e0;
            padding: 3px;
            text-align: center;
            margin-bottom: 5px;
        }

        /* Layout centered untuk single column */
        .centered-layout {
            width: 70%;
            margin: 0 auto;
        }

        .footer {
            position: fixed;
            bottom: 2mm;
            right: 2mm;
            font-size: 7px;
            color: #666;
        }
    </style>
</head>

<body>
    @php
        $reportDate = '';
        if ($date_from && $date_until) {
            if ($date_from === $date_until) {
                $reportDate = \Carbon\Carbon::parse($date_from)->format('d F Y');
            } else {
                $reportDate =
                    \Carbon\Carbon::parse($date_from)->format('d F Y') .
                    ' - ' .
                    \Carbon\Carbon::parse($date_until)->format('d F Y');
            }
        } elseif ($date_from) {
            $reportDate = 'Mulai ' . \Carbon\Carbon::parse($date_from)->format('d F Y');
        } elseif ($date_until) {
            $reportDate = 'Sampai ' . \Carbon\Carbon::parse($date_until)->format('d F Y');
        } else {
            $reportDate = $reports->isNotEmpty()
                ? $reports->first()->created_at->format('d F Y')
                : now()->format('d F Y');
        }

        $reportsByShift = $reports
            ->groupBy(function ($report) {
                return $report->shift->id;
            })
            ->sortKeys();

        $constructionByShift = $allReports
            ->groupBy(function ($report) {
                return $report->shift_id;
            })
            ->sortKeys()
            ->map(function ($shiftReports) {
                return $shiftReports->groupBy('construction_id')->map(function ($constructionReports) {
                    return [
                        'construction' => $constructionReports->first()->construction,
                        'total_stock' => $constructionReports->sum('stock'),
                        'machine_count' => $constructionReports->unique('machine_id')->count(),
                        'avg_eff' => $constructionReports->avg('eff'),
                    ];
                });
            });

        $shiftsArray = $reportsByShift->keys()->toArray();
        $totalShifts = count($shiftsArray);
    @endphp

    <!-- HEADER HALAMAN 1 -->
    <div class="header">
        <h1>{{ $title }}</h1>
        <h2>Tanggal Cetak: {{ $date }}</h2>
    </div>

    @if ($totalShifts >= 2)
        <!-- SHIFT 1 & 2 BERSEBELAHAN (CSS Table Layout) -->
        <div class="layout-table">
            <!-- KOLOM KIRI: SHIFT 1 -->
            <div class="layout-cell">
                @php
                    $shiftId = $shiftsArray[0];
                    $shiftReports = $reportsByShift[$shiftId];
                @endphp
                <div class="shift-section">
                    <div class="section-title">PEGANGAN OPERATOR TENUN
                        {{ $shiftReports->first()->shift->name ?? 'SHIFT ' . $shiftId }} (
                        {{ $shiftReports->first()->shift->duration_hours ?? 8 }} JAM )</div>

                    <table class="operator-table">
                        <thead>
                            <tr>
                                <th>MC</th>
                                <th>NAMA</th>
                                <th>MC</th>
                                <th>PJG(MTR)</th>
                                <th>EFF</th>
                                <th>JAM KERJA</th>
                                <th>TANGGAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shiftReports as $report)
                                @php
                                    $currentShiftId = $report->shift_id;
                                    $currentUserId = $report->user_id;
                                    $currentDate = $report->created_at->format('Y-m-d');

                                    $sameDayReports = $allReports->filter(function ($r) use (
                                        $currentUserId,
                                        $currentShiftId,
                                        $currentDate,
                                    ) {
                                        return $r->user_id === $currentUserId &&
                                            $r->shift_id === $currentShiftId &&
                                            $r->created_at->format('Y-m-d') === $currentDate;
                                    });

                                    $machineCount = $sameDayReports->unique('machine_id')->count();
                                    $totalStock = $sameDayReports->sum('stock');

                                    // Calculate average efficiency from same day reports
                                    $effValue = $sameDayReports->avg('eff') ?? 0;

                                    // Hitung jam kerja
                                    $shiftDuration = $report->shift->duration_hours ?? 8;
                                    $overtime = $report->overtime ?? 0;
                                    $workhours = $shiftDuration + $overtime;
                                @endphp
                                <tr>
                                    <td>{{ $report->machine->kd_mach ?? '' }}</td>
                                    <td class="text-left">{{ $report->user->name ?? '' }}</td>
                                    <td>{{ $machineCount }}</td>
                                    <td class="text-right">{{ number_format($totalStock, 2) }}</td>
                                    <td>{{ number_format($effValue, 1) }}%</td>
                                    <td>{{ $workhours }} jam</td>
                                    <td>{{ $report->created_at->format('j M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (isset($constructionByShift[$shiftId]))
                        <div class="keterangan-title">KETERANGAN</div>
                        <div class="shift-construction-title">
                            {{ $shiftReports->first()->shift->name ?? 'SHIFT ' . $shiftId }}</div>
                        <table class="construction-table">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NO KONSTRUKSI</th>
                                    <th>MC</th>
                                    <th>PJG(MTR)</th>
                                    <th>EFF(%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($constructionByShift[$shiftId] as $constructionId => $data)
                                    @if ($data['construction'])
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left">{{ $data['construction']->name }}</td>
                                            <td>{{ $data['machine_count'] }}</td>
                                            <td class="text-right">{{ number_format($data['total_stock'], 2) }}</td>
                                            <td>{{ number_format($data['avg_eff'], 1) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                @php
                                    $constructions = $constructionByShift[$shiftId];
                                    $totalStock = collect($constructions)->sum('total_stock');
                                    $totalMachines = collect($constructions)->sum('machine_count');
                                    $avgEff = collect($constructions)->avg('avg_eff');
                                @endphp
                                <tr class="total-row">
                                    <td colspan="2"><strong>TOTAL</strong></td>
                                    <td><strong>{{ $totalMachines }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($totalStock, 2) }}</strong></td>
                                    <td><strong>{{ number_format($avgEff, 1) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- KOLOM KANAN: SHIFT 2 -->
            <div class="layout-cell">
                @php
                    $shiftId = $shiftsArray[1];
                    $shiftReports = $reportsByShift[$shiftId];
                @endphp
                <div class="shift-section">
                    <div class="section-title">PEGANGAN OPERATOR TENUN
                        {{ $shiftReports->first()->shift->name ?? 'SHIFT ' . $shiftId }} (
                        {{ $shiftReports->first()->shift->duration_hours ?? 8 }} JAM )</div>

                    <table class="operator-table">
                        <thead>
                            <tr>
                                <th>MC</th>
                                <th>NAMA</th>
                                <th>MC</th>
                                <th>PJG(MTR)</th>
                                <th>EFF</th>
                                <th>JAM KERJA</th>
                                <th>TANGGAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shiftReports as $report)
                                @php
                                    $currentShiftId = $report->shift_id;
                                    $currentUserId = $report->user_id;
                                    $currentDate = $report->created_at->format('Y-m-d');

                                    $sameDayReports = $allReports->filter(function ($r) use (
                                        $currentUserId,
                                        $currentShiftId,
                                        $currentDate,
                                    ) {
                                        return $r->user_id === $currentUserId &&
                                            $r->shift_id === $currentShiftId &&
                                            $r->created_at->format('Y-m-d') === $currentDate;
                                    });

                                    $machineCount = $sameDayReports->unique('machine_id')->count();
                                    $totalStock = $sameDayReports->sum('stock');

                                    // Calculate average efficiency from same day reports
                                    $effValue = $sameDayReports->avg('eff') ?? 0;

                                    // Hitung jam kerja
                                    $shiftDuration = $report->shift->duration_hours ?? 8;
                                    $overtime = $report->overtime ?? 0;
                                    $workhours = $shiftDuration + $overtime;
                                @endphp
                                <tr>
                                    <td>{{ $report->machine->kd_mach ?? '' }}</td>
                                    <td class="text-left">{{ $report->user->name ?? '' }}</td>
                                    <td>{{ $machineCount }}</td>
                                    <td class="text-right">{{ number_format($totalStock, 2) }}</td>
                                    <td>{{ number_format($effValue, 1) }}%</td>
                                    <td>{{ $workhours }} jam</td>
                                    <td>{{ $report->created_at->format('j M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (isset($constructionByShift[$shiftId]))
                        <div class="keterangan-title">KETERANGAN</div>
                        <div class="shift-construction-title">
                            {{ $shiftReports->first()->shift->name ?? 'SHIFT ' . $shiftId }}</div>
                        <table class="construction-table">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NO KONSTRUKSI</th>
                                    <th>MC</th>
                                    <th>PJG(MTR)</th>
                                    <th>EFF(%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($constructionByShift[$shiftId] as $constructionId => $data)
                                    @if ($data['construction'])
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left">{{ $data['construction']->name }}</td>
                                            <td>{{ $data['machine_count'] }}</td>
                                            <td class="text-right">{{ number_format($data['total_stock'], 2) }}</td>
                                            <td>{{ number_format($data['avg_eff'], 1) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                @php
                                    $constructions = $constructionByShift[$shiftId];
                                    $totalStock = collect($constructions)->sum('total_stock');
                                    $totalMachines = collect($constructions)->sum('machine_count');
                                    $avgEff = collect($constructions)->avg('avg_eff');
                                @endphp
                                <tr class="total-row">
                                    <td colspan="2"><strong>TOTAL</strong></td>
                                    <td><strong>{{ $totalMachines }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($totalStock, 2) }}</strong></td>
                                    <td><strong>{{ number_format($avgEff, 1) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    @elseif($totalShifts == 1)
        <!-- HANYA 1 SHIFT - CENTERED -->
        @php
            $shiftId = $shiftsArray[0];
            $shiftReports = $reportsByShift[$shiftId];
        @endphp
        <div class="centered-layout">
            <div class="shift-section">
                <div class="section-title">PEGANGAN OPERATOR TENUN
                    {{ $shiftReports->first()->shift->name ?? 'SHIFT ' . $shiftId }} (
                    {{ $shiftReports->first()->shift->duration_hours ?? 8 }} JAM )</div>

                <table class="operator-table">
                    <thead>
                        <tr>
                            <th>MC</th>
                            <th>NAMA</th>
                            <th>MC</th>
                            <th>PJG(MTR)</th>
                            <th>EFF</th>
                            <th>JAM KERJA</th>
                            <th>TANGGAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shiftReports as $report)
                            @php
                                $currentShiftId = $report->shift_id;
                                $currentUserId = $report->user_id;
                                $currentDate = $report->created_at->format('Y-m-d');

                                $sameDayReports = $allReports->filter(function ($r) use (
                                    $currentUserId,
                                    $currentShiftId,
                                    $currentDate,
                                ) {
                                    return $r->user_id === $currentUserId &&
                                        $r->shift_id === $currentShiftId &&
                                        $r->created_at->format('Y-m-d') === $currentDate;
                                });

                                $machineCount = $sameDayReports->unique('machine_id')->count();
                                $totalStock = $sameDayReports->sum('stock');

                                // PERUBAHAN DI SINI - Menghitung rata-rata efficiency
                                $effValue = $sameDayReports->avg('eff') ?? 0;

                                // Hitung jam kerja
                                $shiftDuration = $report->shift->duration_hours ?? 8;
                                $overtime = $report->overtime ?? 0;
                                $workhours = $shiftDuration + $overtime;
                            @endphp
                            <tr>
                                <td>{{ $report->machine->kd_mach ?? '' }}</td>
                                <td class="text-left">{{ $report->user->name ?? '' }}</td>
                                <td>{{ $machineCount }}</td>
                                <td class="text-right">{{ number_format($totalStock, 2) }}</td>
                                <td>{{ number_format($effValue, 1) }}%</td>
                                <td>{{ $workhours }} jam</td>
                                <td>{{ $report->created_at->format('j M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if (isset($constructionByShift[$shiftId]))
                    <div class="keterangan-title">KETERANGAN</div>
                    <div class="shift-construction-title">
                        {{ $shiftReports->first()->shift->name ?? 'SHIFT ' . $shiftId }}</div>
                    <table class="construction-table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NO KONSTRUKSI</th>
                                <th>MC</th>
                                <th>PJG(MTR)</th>
                                <th>EFF(%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($constructionByShift[$shiftId] as $constructionId => $data)
                                @if ($data['construction'])
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td class="text-left">{{ $data['construction']->name }}</td>
                                        <td>{{ $data['machine_count'] }}</td>
                                        <td class="text-right">{{ number_format($data['total_stock'], 2) }}</td>
                                        <td>{{ number_format($data['avg_eff'], 1) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            @php
                                $constructions = $constructionByShift[$shiftId];
                                $totalStock = collect($constructions)->sum('total_stock');
                                $totalMachines = collect($constructions)->sum('machine_count');
                                $avgEff = collect($constructions)->avg('avg_eff');
                            @endphp
                            <tr class="total-row">
                                <td colspan="2"><strong>TOTAL</strong></td>
                                <td><strong>{{ $totalMachines }}</strong></td>
                                <td class="text-right"><strong>{{ number_format($totalStock, 2) }}</strong></td>
                                <td><strong>{{ number_format($avgEff, 1) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endif

    @if ($totalShifts > 2)
        <!-- PAGE BREAK UNTUK SHIFT 3+ -->
        <div class="page-break"></div>

        <!-- HEADER HALAMAN 2 -->
        <div class="header">
            <h1>{{ $title }} - Lanjutan</h1>
            <h2>Tanggal Cetak: {{ $date }}</h2>
        </div>

        <!-- SHIFT 3 DAN SETERUSNYA -->
        @for ($i = 2; $i < $totalShifts; $i++)
            @php
                $shiftId = $shiftsArray[$i];
                $shiftReports = $reportsByShift[$shiftId];
            @endphp
            <div class="centered-layout" style="margin-bottom: 30px;">
                <div class="shift-section">
                    <div class="section-title">PEGANGAN OPERATOR TENUN
                        {{ $shiftReports->first()->shift->name ?? 'SHIFT ' . $shiftId }} (
                        {{ $shiftReports->first()->shift->duration_hours ?? 8 }} JAM )</div>

                    <table class="operator-table">
                        <thead>
                            <tr>
                                <th>MC</th>
                                <th>NAMA</th>
                                <th>MC</th>
                                <th>PJG(MTR)</th>
                                <th>EFF</th>
                                <th>JAM KERJA</th>
                                <th>TANGGAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shiftReports as $report)
                                @php
                                    $currentShiftId = $report->shift_id;
                                    $currentUserId = $report->user_id;
                                    $currentDate = $report->created_at->format('Y-m-d');

                                    $sameDayReports = $allReports->filter(function ($r) use (
                                        $currentUserId,
                                        $currentShiftId,
                                        $currentDate,
                                    ) {
                                        return $r->user_id === $currentUserId &&
                                            $r->shift_id === $currentShiftId &&
                                            $r->created_at->format('Y-m-d') === $currentDate;
                                    });

                                    $machineCount = $sameDayReports->unique('machine_id')->count();
                                    $totalStock = $sameDayReports->sum('stock');

                                    // PERUBAHAN DI SINI - Menghitung rata-rata efficiency
                                    $effValue = $sameDayReports->avg('eff') ?? 0;

                                    // Hitung jam kerja
                                    $shiftDuration = $report->shift->duration_hours ?? 8;
                                    $overtime = $report->overtime ?? 0;
                                    $workhours = $shiftDuration + $overtime;
                                @endphp
                                <tr>
                                    <td>{{ $report->machine->kd_mach ?? '' }}</td>
                                    <td class="text-left">{{ $report->user->name ?? '' }}</td>
                                    <td>{{ $machineCount }}</td>
                                    <td class="text-right">{{ number_format($totalStock, 2) }}</td>
                                    <td>{{ number_format($effValue, 1) }}%</td>
                                    <td>{{ $workhours }} jam</td>
                                    <td>{{ $report->created_at->format('j M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (isset($constructionByShift[$shiftId]))
                        <div class="keterangan-title">KETERANGAN</div>
                        <div class="shift-construction-title">
                            {{ $shiftReports->first()->shift->name ?? 'SHIFT ' . $shiftId }}</div>
                        <table class="construction-table">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NO KONSTRUKSI</th>
                                    <th>MC</th>
                                    <th>PJG(MTR)</th>
                                    <th>EFF(%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($constructionByShift[$shiftId] as $constructionId => $data)
                                    @if ($data['construction'])
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left">{{ $data['construction']->name }}</td>
                                            <td>{{ $data['machine_count'] }}</td>
                                            <td class="text-right">{{ number_format($data['total_stock'], 2) }}</td>
                                            <td>{{ number_format($data['avg_eff'], 1) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                @php
                                    $constructions = $constructionByShift[$shiftId];
                                    $totalStock = collect($constructions)->sum('total_stock');
                                    $totalMachines = collect($constructions)->sum('machine_count');
                                    $avgEff = collect($constructions)->avg('avg_eff');
                                @endphp
                                <tr class="total-row">
                                    <td colspan="2"><strong>TOTAL</strong></td>
                                    <td><strong>{{ $totalMachines }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($totalStock, 2) }}</strong></td>
                                    <td><strong>{{ number_format($avgEff, 1) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endfor
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>

</html>
