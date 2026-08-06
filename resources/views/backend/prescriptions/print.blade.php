<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Prescription Print - {{ $prescription->prescription_no }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        @media print {
            @page {
                size: A4;
                margin: 0;
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
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            font-family: Arial, Helvetica, sans-serif;
            color: #1a1a1a;
        }

        #prescription_body {
            width: 210mm;
            min-height: 297mm;
            padding: 3mm 8mm;
            background: white;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #print_btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        /* হেডার */
        .header_row {
            text-align: center;
            margin-bottom: 10px;
        }

        .doctor-name {
            font-size: 24px;
            font-weight: 900;
            margin: 0;
            color: #000;
        }

        .doctor-info {
            font-size: 13px;
            line-height: 1.4;
            margin: 5px 0;
        }

        /* পেশেন্ট ইনফো */
        .patient_info {
            border: 1px solid #000;
            padding: 6px 15px;
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            font-size: 14px;
            background: #f9f9f9;
        }

        .main_content {
            display: flex;
            flex: 1;
            border-bottom: 1px solid #000;
        }

        /* সাইডবার ডিজাইন */
        .sidebar {
            width: 50%;
            border-right: 1.5px solid #000;
            padding-right: 10px;
            padding-top: 10px;
            font-size: 11px;
            line-height: 1.4;
        }

        .section-label {
            font-weight: 800;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 12px 0 6px 0;
            display: block;
            font-size: 11.5px;
            color: #000;
        }

        .group-title {
            font-weight: bold;
            color: #333;
            display: inline-block;
        }

        .option-item {
            display: inline-block;
            padding: 0 3px;
            margin: 0 1px;
            color: #555;
            border-radius: 2px;
        }

        /* হাইলাইট স্টাইল */
        .highlight {
            background-color: #e5ff00 !important;
            font-weight: bold;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
        }

        .dotted-val {
            border-bottom: 1px dotted #000;
            min-width: 35px;
            display: inline-block;
            text-align: center;
            font-weight: bold;
            color: #000;
        }

        /* Rx Side */
        .rx_side {
            width: 100%;
            padding-left: 10px;
            position: relative;
            padding-top: 10px;
        }

        .rx_title {
            font-family: "Times New Roman", serif;
            font-size: 40px;
            font-weight: bold;
            font-style: italic;
            margin-bottom: 10px;
        }

        .red-box {
            border: 2px solid red;
            color: red;
            padding: 6px;
            font-size: 11px;
            font-weight: bold;
            float: right;
            max-width: 200px;
            text-align: left;
            line-height: 1.3;
        }

        .med-item {
            margin-bottom: 18px;
        }

        .med-name {
            font-weight: bold;
            font-size: 15px;
            text-transform: uppercase;
            color: #000;
        }

        .med-details {
            margin-left: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 3px;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            padding-top: 12px;
            margin-top: auto;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <button id="print_btn" onclick="window.print();">Print Prescription</button>

    <div id="prescription_body">
        <!-- Header -->
        <div class="header_row">
            <h1 class="doctor-name">Dr. Muhammad Asif Sattar</h1>
            <div class="doctor-info">
                MBBS, MPH (Child Health) | PGPN (Boston University, America) <br>
                <span style="color:#008d00; font-weight:700;">Resident Medical Officer</span> - Dhaka Shishu (Children)
                Hospital <br>
                Email: asif.sattar1983@gmail.com
            </div>
        </div>

        <!-- Patient Strip -->
        <div class="patient_info">
            <span>Name: {{ $prescription->patient_name }}</span>
            <span>Age: {{ $prescription->patient_age }}</span>
            <span>Wt: {{ $prescription->patient_weight }}</span>
            <span>Date: {{ $prescription->prescription_date->format('d-m-Y') }}</span>
        </div>

        <div class="main_content">
            <!-- Sidebar with ALL options as per Create Form -->
            <div class="sidebar">
                @php
                    $symp = $prescription->symptoms;
                    $oe = $prescription->oe;
                @endphp
                <!-- Fever -->
                <div style="margin-bottom: 5px;">
                    <i class="fa-solid fa-caret-right"></i> <span class="group-title">Fever:</span>
                    <span
                        class="option-item {{ ($symp['fever']['type'] ?? '') == 'Intermittent' ? 'highlight' : '' }}">Intermittent</span>
                    /
                    <span
                        class="option-item {{ ($symp['fever']['type'] ?? '') == 'Continuous' ? 'highlight' : '' }}">Continuous</span>
                    <div style="padding-left:18px;">
                        <span class="dotted-val">{{ $symp['fever']['duration'] ?? ' ' }}</span>
                        <span
                            class="option-item {{ ($symp['fever']['duration_type'] ?? '') == 'days' ? 'highlight' : '' }}">Day</span>/
                        <span
                            class="option-item {{ ($symp['fever']['duration_type'] ?? '') == 'weeks' ? 'highlight' : '' }}">Week</span>/
                        <span
                            class="option-item {{ ($symp['fever']['duration_type'] ?? '') == 'months' ? 'highlight' : '' }}">Month</span>
                    </div>
                </div>

                <!-- Cough -->
                <div style="margin-bottom: 5px;">
                    <i class="fa-solid fa-caret-right"></i> <span class="group-title">Cough:</span>
                    @foreach (['Acute', 'Chronic', 'Intermittent', 'Persistent', 'Productive', 'Croup', 'Nocturnal', 'Non-Productive'] as $cType)
                        <span
                            class="option-item {{ in_array($cType, $symp['cough']['types'] ?? []) ? 'highlight' : '' }}">{{ $cType }}</span>
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                    <div style="padding-left:18px;">
                        <span class="dotted-val">{{ $symp['cough']['duration'] ?? ' ' }}</span>
                        <span
                            class="option-item {{ ($symp['cough']['duration_type'] ?? '') == 'days' ? 'highlight' : '' }}">Day</span>/
                        <span
                            class="option-item {{ ($symp['cough']['duration_type'] ?? '') == 'weeks' ? 'highlight' : '' }}">Week</span>/
                        <span
                            class="option-item {{ ($symp['cough']['duration_type'] ?? '') == 'months' ? 'highlight' : '' }}">Month</span>
                    </div>
                </div>

                <div style="margin-bottom: 5px;">
                    <i class="fa-solid fa-caret-right"></i>
                    @foreach (['Runny Nose', 'Respiratory Distress'] as $respType)
                        <span
                            class="option-item {{ in_array($respType, $symp['resp'] ?? []) ? 'highlight' : '' }}">{{ $respType }}</span>
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                </div>

                <!-- Bowel -->
                <div style="margin-bottom: 5px;">
                    <i class="fa-solid fa-caret-right"></i>
                    @foreach (['Loose Motion', 'Watery', 'Blood', 'Mucoid', 'Abdominal Pain', 'Constipation', 'Distention', 'Altered bowel habit'] as $bType)
                        <span
                            class="option-item {{ in_array($bType, $symp['bowel'] ?? []) ? 'highlight' : '' }}">{{ $bType }}</span>
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                </div>

                <!-- General -->
                <div style="margin-bottom: 5px;">
                    <i class="fa-solid fa-caret-right"></i>
                    @foreach (['Pallor', 'Poor Appetite', 'Nausea', 'Vomiting', 'Thrush', 'Epiphora', 'Oral Ulcer', 'Sore Throat'] as $gType)
                        <span
                            class="option-item {{ in_array($gType, $symp['general'] ?? []) ? 'highlight' : '' }}">{{ $gType }}</span>
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                </div>

                <!-- Urine -->
                <div style="margin-bottom: 5px;">
                    <i class="fa-solid fa-caret-right"></i>
                    @foreach (['Painful Micturition', 'Frequency +-', 'Dribbling'] as $uType)
                        <span
                            class="option-item {{ in_array($uType, $symp['urine'] ?? []) ? 'highlight' : '' }}">{{ $uType }}</span>
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                </div>
                <div style="margin-bottom: 5px;">
                    <i class="fa-solid fa-caret-right"></i>
                    @foreach (['Painful Swelling', 'Limbs', 'Joint', 'Rash', 'Generalized', 'Localized'] as $swType)
                        <span
                            class="option-item {{ in_array($swType, $symp['swelling'] ?? []) ? 'highlight' : '' }}">{{ $swType }}</span>
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                    <span class="dotted-val">{{ $symp['swelling']['details'] ?? ' ' }}</span>
                </div>
                <!-- Others -->
                <div style="margin-bottom: 5px;">
                    <i class="fa-solid fa-caret-right"></i> <span class="group-title">Others:</span>
                    @foreach (['Developmental Delay', 'Convulsion', 'Nasal Block', 'Mouth Breathing', 'Epistaxis'] as $oType)
                        <span
                            class="option-item {{ in_array($oType, $symp['others'] ?? []) ? 'highlight' : '' }}">{{ $oType }}</span>
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                </div>

                <!-- Birth History -->
                <strong>Birth History</strong>
                <div style="margin-bottom: 5px; padding-left:5px; display:flex; justify-content:space-between; ">
                    <div>
                        <span
                            class="option-item {{ ($prescription->symptoms['birth_history']['delivery'] ?? '') == 'LUCS' ? 'highlight' : '' }}">LUCS</span>
                        /
                        <span
                            class="option-item {{ ($prescription->symptoms['birth_history']['delivery'] ?? '') == 'NVD' ? 'highlight' : '' }}">NVD</span>
                    </div>
                    <div>
                        <span
                            class="option-item {{ ($prescription->symptoms['birth_history']['place'] ?? '') == 'Hospital' ? 'highlight' : '' }}">Hospital</span>
                        /
                        <span
                            class="option-item {{ ($prescription->symptoms['birth_history']['place'] ?? '') == 'Home' ? 'highlight' : '' }}">Home</span>
                    </div>
                </div>
                <div style="margin-bottom: 5px; padding-left:5px;">
                    @foreach (['Term', 'Preterm', 'EBF', 'Formula', 'Issue', 'Uneventful', 'Delayed Crying', 'Meconium', 'Urine'] as $bHist)
                        <span
                            class="option-item {{ in_array($bHist, $prescription->symptoms['birth_history']['conditions'] ?? []) ? 'highlight' : '' }}">{{ $bHist }}</span>
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                </div>

                <!-- O/E Section - ALL FIELDS -->
                <strong>On Examination (O/E)</strong>
                <div style="padding-left:5px;">
                    Temp: <span class="dotted-val">{{ $oe['temp'] ?? '___' }}</span><br>
                    Resp. Rate: <span class="dotted-val">{{ $oe['response-rate'] ?? '___' }}</span>
                    Heart Rate: <span class="dotted-val">{{ $oe['heart-rate'] ?? '___' }}</span><br>
                    Appearance: <span class="dotted-val">{{ $oe['appearance'] ?? '___' }}</span><br>
                    Jaundice: <span class="dotted-val">{{ $oe['jaundice'] ?? '___' }}</span><br>
                    Oral Cavity: <span class="dotted-val">{{ $oe['oral-cavity'] ?? '___' }}</span> <br>
                    Lymph Node: <span class="dotted-val">{{ $oe['lymph-node'] ?? '___' }}</span><br>
                    Reflex: <span class="dotted-val">{{ $oe['reflex'] ?? '___' }}</span> <br>
                    Umbilicus: <span class="dotted-val">{{ $oe['umbilicus'] ?? '___' }}</span> <br>

                    <span class="group-title">Heart:</span>
                    <span class="option-item {{ ($oe['heart'] ?? '') == 'NAD' ? 'highlight' : '' }}">NAD</span> /
                    <span
                        class="option-item {{ ($oe['heart'] ?? '') == 'Murmur' ? 'highlight' : '' }}">Murmur</span><br>

                    <span class="group-title">Lungs:</span>
                    @foreach (['NAD', 'Rhonchi', 'Creps', 'Wheeze'] as $lType)
                        <span
                            class="option-item {{ ($oe['lungs'] ?? '') == $lType ? 'highlight' : '' }}">{{ $lType }}</span>
                        @if (!$loop->last)
                            /
                        @endif
                    @endforeach
                    <br>

                    <span class="group-title">P/Abd:</span>
                    <span class="option-item {{ ($oe['pabd'] ?? '') == 'Normal' ? 'highlight' : '' }}">Normal</span> /
                    <span
                        class="option-item {{ ($oe['pabd'] ?? '') == 'Distended' ? 'highlight' : '' }}">Distended</span>
                    <br>

                    Liver/Spleen: <span class="dotted-val">{{ $oe['liver-spleen-kidney'] ?? '___' }}</span> <br>
                    Bowel Sound: <span class="dotted-val">{{ $oe['bowel-sound'] ?? '___' }}</span> <br>
                    Genitalia: <span class="dotted-val">{{ $oe['genitalia'] ?? '___' }}</span> |
                    ENT: <span class="dotted-val">{{ $oe['ent'] ?? '___' }}</span> <br>
                    Skin: <span class="dotted-val">{{ $oe['skin'] ?? '___' }}</span>
                </div>
            </div>

            <!-- Rx Side -->
            <div class="rx_side">
                <div class="red-box">
                    * ঔষধ পরিবর্তন করা যাবে না (ডাক্তারের পরামর্শ ব্যতীত)। <br>
                    * পূর্বের প্রেসক্রিপশন অনুযায়ী ঔষধ খাওয়ানো যাবে না।
                </div>
                <span style="font-size:25px; font-style:italic">R<sub>x</sub></span>

                <div class="med_list" style="margin-top:60px;">
                    @foreach ($prescription->items as $index => $item)
                        <div style="display:flex; gap:30px; margin-bottom:10px;">
                            @php
                                $cleanName = trim(strtok($item->product_name, '('));
                            @endphp
                            <div>
                                <strong style="text-transform: uppercase; font-size:14px;">{{ $index + 1 }}. {{ $cleanName }}</strong><br>
                                <span style="font-size: 11px; font-style: italic; color: #666; margin-left: 20px;">
                                ({{ $item->generic_name }})</span>
                            </div>
                            <div class="details">
                                <div style="display: flex; gap:10px;">
                                    <span>{{ $item->dosage_data }}</span>
                                    <span>{{ $item->dosage_unit ?? '' }}</span>
                                    <span>{{ $item->dosage_time == 'after_meal' ? 'খাওয়ার পরে' : '' }}</span>
                                    <span>{{ $item->dosage_time == 'before_meal' ? 'খাওয়ার আগে' : '' }}</span>
                                    <span>{{ $item->duration ?? ''}}<span>
                                    @if($item->duration != null)
                                        <span>{{ $item->duration_type == 'day' ? 'দিন' : '' }}</span>
                                        <span>{{ $item->duration_type == 'week' ? 'সপ্তাহ' : '' }}</span>
                                        <span>{{ $item->duration_type == 'month' ? 'মাস' : '' }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (!empty($prescription->tests))
                    <div style="margin-top: 30px;">
                        <span class="section-label">Tests:</span>
                        @foreach ($prescription->tests as $tIndex => $test)
                            <div style="font-size:13px; margin-bottom: 2px;">{{ $tIndex + 1 }}.
                                <b>{{ $test['name'] }}</b> {{ !empty($test['note']) ? '(' . $test['note'] . ')' : '' }}
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($prescription->advice)
                    <div style="margin-top: 25px;">
                        <span class="section-label">Advice / পরামর্শ:</span>
                        <div style="font-size:13px; white-space: pre-line; line-height: 1.4;">
                            {{ $prescription->advice }}</div>
                    </div>
                @endif

                <!-- Follow Up -->
                <div style="position: absolute; bottom: 30px; right: 0; font-weight: bold; font-size: 14px;">
                    @if (isset($prescription->next_follow_up['duration']))
                        {{ $prescription->next_follow_up['duration'] }}
                        {{ $prescription->next_follow_up['duration_type'] == 'days' ? 'দিন' : ($prescription->next_follow_up['duration_type'] == 'weeks' ? 'সপ্তাহ' : 'মাস') }}
                        পর দেখা করবেন।
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="display:flex; justify-content:space-between">
            <div style="padding-top:10px; width:50%;">
                <strong style="margin:0px; color:red; font-size:20px; font-weight:700;">চেম্বারঃ</strong>
                <strong style="margin:0px; color:green; font-size:20px; font-weight:700;">ফিরোজা মেডিসিন কর্ণার</strong><br>
                <strong style="margin:0px; margin-left:70px; text-transform:uppercase; color:red; font-size:18px; font-weight:700;">Firoza Medicine Corner</strong>
            </div>
            <div style="padding-top:10px; width:50%;">
                <p style="text-align:center; margin:0px 0px 5px 0px; font-size:12; font-weight:500;">চ-৩, সিভিল এভিয়েশন ওয়েলফেয়ার মার্কেট,<br> কাওলার বাজার, দক্ষিণখান, ঢাকা।</p>
                <p style="margin:0px; text-align:center; color:red">সিরিয়ালের জন্য : ০১৮৯০-৩৩৮৩০০</p>
            </div>
        </div>
        <p style="text-align:center; font-size:10">পরবর্তী সাক্ষাতের সময় ব্যবস্থাপত্র অবশ্যই সঙ্গে নিয়ে আসবেন।</p>
    </div>

</body>

</html>
