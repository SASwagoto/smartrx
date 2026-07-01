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
