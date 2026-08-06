<html>

<head>
    <title>Print Prescription</title>
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
            #print_btn{
                display: none !important;
            }
        }
        #print_btn{
            position: absolute; 
            top:10px;
        }
        #prescription_body{
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
            <p style="margin:0px;">MBBS, MPH (Child Health) <br> PGPN (Boston University, America) <br> <span style="color:#008d00; font-weight:700;">Resident Medical Officer</span> <br> Dhaka Shishu (Children) Hospital
                <br> asif.sattar1983@gmail.com
            </p>
        </div>
        <div class="patient_info" style="border-top:1px solid #000; border-bottom:1px solid #000; padding:2px 0px; display:flex; justify-content:space-between">
            <div>Name: {{ $prescription->patient_name}}</div>
            <div>Age: {{ $prescription->patient_age}}</div>
            <div>Weight: {{ $prescription->patient_weight}}</div>
            <div>Date: {{ $prescription->prescription_date->format('d-m-Y')}}</div>
        </div>
    </div>
</body>
</html>
