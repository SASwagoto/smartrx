<link rel="stylesheet" href="{{ asset('backend/plugins/DataTables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/DataTables/responsive.dataTables.min.css') }}">
<style>
    /* Search wrapper */
    .dt-search-wrapper .dataTables_filter {
        width: 100%;
        margin: 0;
    }

    /* Label full width */
    .dt-search-wrapper .dataTables_filter label {
        width: 100%;
        margin-bottom: 0;
    }

    /* Search input full width */
    .dt-search-wrapper .dataTables_filter input {
        width: 100% !important;
        margin-left: 0 !important;
    }

    /* Vertically center first row */
    .dataTables_wrapper .row:first-child {
        align-items: center;
    }

    /* Buttons vertically center */
    .dt-buttons {
        display: flex;
        align-items: center;
    }

    table.dataTable.nowrap th[title="Action"] {
        text-align: end !important;
    }

    .dataTables_wrapper .table-responsive {
        overflow: visible !important;
    }

    .table-responsive {
        overflow: visible !important;
    }

    table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
    table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
        border: none !important;
        margin-left: 5px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
</style>
    <style>
        /* 🚀 এক্সপোর্ট বাটনের লাক্সারি থিমিং */
        .custom-dt-btn {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #475569 !important;
            font-size: 13px !important;
            padding: 7px 12px !important;
            border-radius: 6px !important;
            transition: all 0.2s ease-in-out !important;
        }
        .custom-dt-btn:hover {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px 0 rgba(0,0,0,0.05) !important;
        }
        .custom-dt-btn:active {
            transform: translateY(0px);
        }

        /* ডাটাটেবিল পেজিনেশন স্টাইলিং */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #2563eb !important;
            color: white !important;
            border-color: #2563eb !important;
            border-radius: 6px;
        }
        
        /* সার্চ বক্স ইন্টিগ্রেশন */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            padding: 7px 14px !important;
            font-size: 13px !important;
            background-color: #ffffff;
            width: 240px;
            transition: border-color 0.15s ease-in-out;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #2563eb !important;
            outline: none;
        }

        /* টেবিল কোর স্ট্রাকচার */
        table.dataTable {
            border-collapse: collapse !important;
            font-size: 13px !important;
        }
        table.dataTable thead th {
            background-color: #f8fafc !important;
            color: #475569 !important;
            font-weight: 600 !important;
            border-bottom: 2px solid #e2e8f0 !important;
            padding: 14px 10px !important;
        }
        table.dataTable tbody td {
            padding: 12px 10px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .no-outline-flash:focus {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
