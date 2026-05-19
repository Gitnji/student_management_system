<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Report Card — {{ $enrollment->student->full_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            padding: 20px 30px;
        }

        /* Header */
        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        .header-left, .header-right {
            display: table-cell;
            width: 50%;
            text-align: center;
            font-size: 9px;
            line-height: 1.6;
        }
        .school-name {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #0F172A;
            margin: 8px 0 2px;
            text-transform: uppercase;
        }
        .school-motto {
            text-align: center;
            font-style: italic;
            font-size: 10px;
            color: #555;
            margin-bottom: 4px;
        }
        .school-contact {
            text-align: center;
            font-size: 9px;
            color: #666;
            margin-bottom: 10px;
        }

        /* Term Banner */
        .term-banner {
            background-color: #0F172A;
            color: white;
            text-align: center;
            padding: 8px;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 12px;
        }

        /* Student Info */
        .student-info {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }
        .student-info-left, .student-info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-row {
            margin-bottom: 5px;
            font-size: 10px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }

        /* Marks Table */
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .marks-table th {
            background-color: #0F172A;
            color: white;
            padding: 7px 8px;
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .marks-table th.subject-col {
            text-align: left;
        }
        .marks-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
        }
        .marks-table td.subject-name {
            text-align: left;
            font-weight: bold;
        }
        .marks-table td.subject-code {
            font-size: 9px;
            color: #888;
            font-weight: normal;
        }
        .marks-table tr.failed-row {
            background-color: #fff0f0;
            color: #c00;
        }
        .marks-table tr:nth-child(even):not(.failed-row) {
            background-color: #f9fafb;
        }

        /* Footer row */
        .totals-row td {
            background-color: #1e3a5f;
            color: white;
            font-weight: bold;
            padding: 8px;
            font-size: 10px;
        }

        /* Remark badge */
        .remark-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
        }
        .remark-A { background-color: #d1fae5; color: #065f46; }
        .remark-B { background-color: #dbeafe; color: #1e40af; }
        .remark-C { background-color: #fef3c7; color: #92400e; }
        .remark-D { background-color: #ffedd5; color: #9a3412; }
        .remark-E { background-color: #fee2e2; color: #991b1b; }

        /* Summary Boxes */
        .summary-section {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }
        .summary-left, .summary-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 8px;
        }
        .summary-right { padding-right: 0; padding-left: 8px; }
        .summary-box {
            border: 1px solid #2563EB;
            border-radius: 4px;
            overflow: hidden;
        }
        .summary-box-header {
            background-color: #2563EB;
            color: white;
            padding: 5px 10px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .summary-box-body {
            padding: 8px 10px;
        }
        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
            font-size: 10px;
        }
        .summary-label { display: table-cell; color: #444; }
        .summary-value { display: table-cell; text-align: right; font-weight: bold; }

        /* Conduct checkboxes */
        .conduct-grid {
            display: table;
            width: 100%;
        }
        .conduct-cell {
            display: table-cell;
            width: 50%;
            font-size: 10px;
            padding: 3px 0;
        }
        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #333;
            margin-right: 4px;
            vertical-align: middle;
            text-align: center;
            line-height: 10px;
            font-size: 8px;
        }
        .checked { background-color: #0F172A; color: white; }

        /* Principal Remark */
        .principal-remark {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px 12px;
            margin-bottom: 14px;
        }
        .principal-remark-title {
            font-size: 11px;
            font-weight: bold;
            color: #2563EB;
            margin-bottom: 6px;
        }
        .principal-remark-text {
            font-style: italic;
            font-size: 10px;
            line-height: 1.6;
            color: #333;
        }

        /* Signatures */
        .signatures {
            display: table;
            width: 100%;
            margin-top: 16px;
        }
        .sig-cell {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            font-size: 10px;
        }
        .sig-line {
            border-top: 1px solid #333;
            margin: 20px 15px 5px;
        }

        /* Footer */
        .page-footer {
            margin-top: 14px;
            padding-top: 6px;
            border-top: 1px solid #e5e7eb;
            display: table;
            width: 100%;
            font-size: 8px;
            color: #aaa;
        }
        .footer-left  { display: table-cell; text-align: left; }
        .footer-right { display: table-cell; text-align: right; }

        .divider {
            border: none;
            border-top: 2px solid #0F172A;
            margin: 8px 0;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header-top">
        <div class="header-left">
            <strong>REPUBLIC OF CAMEROON</strong><br>
            Peace - Work - Fatherland<br>
            MINISTRY OF SECONDARY EDUCATION
        </div>
        <div class="header-right">
            <strong>RÉPUBLIQUE DU CAMEROUN</strong><br>
            Paix - Travail - Patrie<br>
            MINISTÈRE DES ENSEIGNEMENTS SECONDAIRES
        </div>
    </div>

    <hr class="divider">

    <div class="school-name">Imperial Comprehensive College</div>
    <div class="school-motto">"Knowledge, Discipline, Excellence"</div>
    <div class="school-contact">
        P.O. Box 421, Bamenda &middot; Tel: +237 670 100 200 &middot; contact@icc.edu.cm &middot; NORTH-WEST REGION
    </div>

    {{-- Term Banner --}}
    <div class="term-banner">
        TERM REPORT &middot; {{ strtoupper($term->term_name) }} &middot; {{ $term->academicYear->name }}
    </div>

    {{-- Student Info --}}
    <div class="student-info">
        <div class="student-info-left">
            <div class="info-row">
                <span class="info-label">{{ $enrollment->student->gender === 'female' ? 'Ms.' : 'Mr.' }}</span>
                {{ $enrollment->student->full_name }}
            </div>
            <div class="info-row">
                <span class="info-label">Matricule:</span>
                {{ $enrollment->student->matricule }}
            </div>
            <div class="info-row">
                <span class="info-label">Exam Center:</span>
                Bamenda Central
            </div>
        </div>
        <div class="student-info-right">
            <div class="info-row">
                <span class="info-label">Class:</span>
                {{ $enrollment->classroom->name }}
            </div>
            <div class="info-row">
                <span class="info-label">Date of Birth:</span>
                {{ $enrollment->student->date_of_birth?->format('d M Y') ?? '—' }}
            </div>
            <div class="info-row">
                <span class="info-label">Next Term Begins:</span>
                {{ $term->next_term_begins?->format('d M Y') ?? '—' }}
            </div>
        </div>
    </div>

    {{-- Marks Table --}}
    <table class="marks-table">
        <thead>
            <tr>
                <th class="subject-col" style="width:28%">Subject</th>
                <th style="width:8%">Coef</th>
                <th style="width:12%">1st Sequence</th>
                <th style="width:14%">2nd Sequence Examination</th>
                <th style="width:10%">Total</th>
                <th style="width:10%">Avg/20</th>
                <th style="width:10%">Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjectRows as $row)
                <tr class="{{ $row['failed'] ? 'failed-row' : '' }}">
                    <td class="subject-name">
                        {{ $row['name'] }}
                        @if($row['code'])
                            <span class="subject-code">({{ $row['code'] }})</span>
                        @endif
                    </td>
                    <td>{{ $row['coefficient'] }}</td>
                    <td>{{ number_format($row['seq1'], 2) }}</td>
                    <td>{{ number_format($row['seq2'], 2) }}</td>
                    <td>{{ number_format($row['total'], 2) }}</td>
                    <td><strong>{{ number_format($row['avg'], 2) }}</strong></td>
                    <td>
                        <span class="remark-badge remark-{{ $row['remark'] }}">
                            {{ $row['remark'] }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totals-row">
                <td colspan="2">GRAND TOTAL</td>
                <td>TOTAL COEF</td>
                <td></td>
                <td>AVG/20</td>
                <td></td>
                <td>POSITION</td>
            </tr>
            <tr class="totals-row">
                <td colspan="2" style="font-size:13px">{{ number_format($report->total_coefficient_points, 2) }}</td>
                <td style="font-size:13px">{{ collect($subjectRows)->sum('coefficient') }}</td>
                <td></td>
                <td style="font-size:13px">{{ number_format($report->term_average, 2) }}</td>
                <td></td>
                <td style="font-size:13px">{{ $report->position_in_class }}/{{ $report->class_size }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Summary + Conduct --}}
    <div class="summary-section">
        <div class="summary-left">
            <div class="summary-box">
                <div class="summary-box-header">PERFORMANCE SUMMARY</div>
                <div class="summary-box-body">
                    <div class="summary-row">
                        <span class="summary-label">Subjects Passed:</span>
                        <span class="summary-value">{{ collect($subjectRows)->where('failed', false)->count() }} / {{ count($subjectRows) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Subjects Failed:</span>
                        <span class="summary-value">{{ collect($subjectRows)->where('failed', true)->count() }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Class Average:</span>
                        <span class="summary-value">{{ number_format($report->class_average, 2) }}/20</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Highest in Class:</span>
                        <span class="summary-value">{{ number_format($report->highest_in_class, 2) }}/20</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Class Size:</span>
                        <span class="summary-value">{{ $report->class_size }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="summary-right">
            <div class="summary-box">
                <div class="summary-box-header">CONDUCT &amp; DISCIPLINE</div>
                <div class="summary-box-body">
                    @php
                        $conductRating = $report->conduct_rating;
                    @endphp
                    <div class="conduct-grid">
                        <div class="conduct-cell">
                            <span class="checkbox {{ $conductRating === 'excellent' ? 'checked' : '' }}">
                                {{ $conductRating === 'excellent' ? '✓' : '' }}
                            </span> Excellent
                        </div>
                        <div class="conduct-cell">
                            <span class="checkbox {{ $conductRating === 'good' ? 'checked' : '' }}">
                                {{ $conductRating === 'good' ? '✓' : '' }}
                            </span> Good
                        </div>
                        <div class="conduct-cell">
                            <span class="checkbox {{ $conductRating === 'average' ? 'checked' : '' }}">
                                {{ $conductRating === 'average' ? '✓' : '' }}
                            </span> Average
                        </div>
                        <div class="conduct-cell">
                            <span class="checkbox {{ $conductRating === 'poor' ? 'checked' : '' }}">
                                {{ $conductRating === 'poor' ? '✓' : '' }}
                            </span> Poor
                        </div>
                    </div>
                    <div style="margin-top:10px; font-size:10px;">
                        <div class="summary-row">
                            <span class="summary-label">Absences (hours):</span>
                            <span class="summary-value">—</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Lateness:</span>
                            <span class="summary-value">—</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Principal's Remark --}}
    <div class="principal-remark">
        <div class="principal-remark-title">PRINCIPAL'S REMARK</div>
        <div class="principal-remark-text">{{ $report->principal_remark }}</div>
    </div>

    {{-- Signatures --}}
    <div class="signatures">
        <div class="sig-cell">
            <div class="sig-line"></div>
            <strong>PRINCIPAL</strong><br>
            Mr. Achidi Tanyi
        </div>
        <div class="sig-cell">
            <div class="sig-line"></div>
            <strong>SCHOOL STAMP</strong>
        </div>
        <div class="sig-cell">
            <div class="sig-line"></div>
            <strong>PARENT / GUARDIAN</strong>
        </div>
    </div>

    {{-- Page Footer --}}
    <div class="page-footer">
        <div class="footer-left">Generated: {{ now()->format('d M Y, H:i') }}</div>
        <div class="footer-right">Powered by ICC SMS &middot; Imperial Comprehensive College</div>
    </div>

</body>
</html>