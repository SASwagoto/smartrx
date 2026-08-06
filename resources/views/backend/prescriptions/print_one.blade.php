<html>

<head>
    <title>Print Prescription</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @media print {
            @page {
                margin: 0;
                size: A4;
            }

            body {
                margin: 0;
                padding: 0;
            }

            #print_btn {
                display: none !important;
            }
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        #print_btn {
            position: absolute;
            top: 10px;
        }

        #prescription_body {
            width: 210mm !important;
            height: 297mm !important;
            border: 1px solid #000;
            margin: 0px auto;
            padding: 10px;
        }
        
    </style>
</head>

<body>
    <button id="print_btn" onclick="window.print();">print</button>
    <div id="prescription_body">
        <div class="header_row" style="text-align: center; padding-bottom:10px;">
            <h4 style="font-weight: 900; font-size:22px; margin:0px;">Dr. Muhammad Asif Sattar</h4>
            <p style="margin:0px;">MBBS, MPH (Child Health) <br> PGPN (Boston University, America) <br> <span
                    style="color:#008d00; font-weight:700;">Resident Medical Officer</span> <br> Dhaka Shishu (Children)
                Hospital
                <br> asif.sattar1983@gmail.com
            </p>
        </div>
        <div class="patient_info"
            style="border-top:1px solid #000; border-bottom:1px solid #000; padding:2px 20px; display:flex; font-weight:700; justify-content:space-between">
            <div>Name: {{ $prescription->patient_name }}</div>
            <div>Age: {{ $prescription->patient_age }}</div>
            <div>Weight: {{ $prescription->patient_weight }}</div>
            <div>Date: {{ $prescription->prescription_date->format('d-m-Y') }}</div>
        </div>
        <div style="padding:0px 10px;">
            <div class="symptom_part" style="width:35%; padding-top:10px; border-right:1px solid #000">
                @php $symp = $prescription->symptoms; @endphp
                <div style="margin-bottom:20px;">
                    <div style="margin-bottom:10px; line-height:1.5; padding-right:5px;">
                        <i class="fa-solid fa-caret-right"></i>
                        <span>Faver: </span>
                        <span
                            style="padding: 2px 5px; {{ ($symp['fever']['type'] ?? '') == 'Intermittent' ? 'background:#e5ff00;' : '' }}">Intermittent</span>/
                        <span
                            style="padding: 2px 5px; {{ ($symp['fever']['type'] ?? '') == 'Continuous' ? 'background:#e5ff00;' : '' }}">Continuous</span>
                    </div>
                    <div style="display: flex; gap:5px; padding: 0px 10px;">
                        <span
                            style="border-bottom: 2px dotted #000; width:80px; text-align:center;">{{ $symp['fever']['duration'] ? $symp['fever']['duration'] : '' }}</span>
                        <span style="padding: 2px 5px; {{ ($symp['fever']['duration_type'] ?? '') == 'days' ? 'background:#e5ff00;' : '' }}">Day</span>/
                        <span style="padding: 2px 5px; {{ ($symp['fever']['duration_type'] ?? '') == 'weeks' ? 'background:#e5ff00;' : '' }}">Week</span>/
                        <span style="padding: 2px 5px; {{ ($symp['fever']['duration_type'] ?? '') == 'months' ? 'background:#e5ff00;' : '' }}">Month</span>
                    </div>
                </div>
                <div>
                    <div style="margin-bottom:10px; line-height:1.5; padding-right:5px;">
                        <i class="fa-solid fa-caret-right"></i>
                        <span>Cough: </span>
                        @foreach (['Acute', 'Chronic', 'Intermittent', 'Persistent', 'Productive', 'Croup', 'Nocturnal', 'Non-Productive'] as $key => $coughType)
                            <span
                                style="padding: 2px 5px; {{ in_array((string) $coughType, $symp['cough']['types'] ?? []) ? 'background:#e5ff00;' : '' }}">
                                {{ $coughType }}
                            </span>
                            @if (!$loop->last)
                                /
                            @endif
                        @endforeach
                    </div>
                    <div style="display: flex; gap:5px; padding: 0px 10px;">
                        <span
                            style="border-bottom: 2px dotted #000; width:80px; text-align:center;">{{ $symp['cough']['duration'] ? $symp['cough']['duration'] : '' }}</span>
                        <span style="padding: 2px 5px; {{ ($symp['cough']['duration_type'] ?? '') == 'days' ? 'background:#e5ff00;' : '' }}">Day</span>/
                        <span style="padding: 2px 5px; {{ ($symp['cough']['duration_type'] ?? '') == 'weeks' ? 'background:#e5ff00;' : '' }}">Week</span>/
                        <span style="padding: 2px 5px; {{ ($symp['cough']['duration_type'] ?? '') == 'months' ? 'background:#e5ff00;' : '' }}">Month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
