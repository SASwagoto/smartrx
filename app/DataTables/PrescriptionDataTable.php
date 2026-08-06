<?php

namespace App\DataTables;

use App\Models\Prescription;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Carbon\Carbon;

class PrescriptionDataTable extends BaseDataTable
{
    protected string $tableId = 'prescription-table';

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Prescription> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('index', function ($row) {
                return '<input type="checkbox" class="form-check-input select-item" value="' . $row->id . '" />';
            })
            ->editColumn('prescription_no', function ($row) {
                return '<strong>' . e($row->prescription_no) . '</strong>';
            })
            ->editColumn('patient_info', function ($row) {
                $name = e($row->patient_name ?? optional($row->patient)->name ?? 'N/A');
                $phone = e($row->patient_phone ?? optional($row->patient)->phone ?? '');
                $details = array_filter([
                    $row->patient_age ? $row->patient_age . ' YRS' : null,
                    $row->patient_gender ? ucfirst($row->patient_gender) : null,
                ]);

                $html = '<div class="fw-bold">' . $name . '</div>';
                if ($phone) {
                    $html .= '<small class="text-muted"><i class="fa-solid fa-phone me-1"></i>' . $phone . '</small><br>';
                }
                if (!empty($details)) {
                    $html .= '<small class="text-secondary">' . implode(' | ', $details) . '</small>';
                }
                return $html;
            })
            ->editColumn('doctor_name', function ($row) {
                return e(optional($row->doctor)->name ?? 'N/A');
            })
            ->editColumn('prescription_date', function ($row) {
                return $row->prescription_date ? Carbon::parse($row->prescription_date)->format('d-m-Y') : 'N/A';
            })
            ->addColumn('items_count', function ($row) {
                return '<span class="badge bg-info text-dark">' . $row->items_count . ' Medicines</span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex justify-content-center align-items-center gap-1">';
                
                // View Button
                $btn .= '<a href="' . route('prescriptions.show', $row->id) . '" class="btn btn-sm btn-outline-info custom-dt-btn" data-bs-toggle="tooltip" title="View Prescription"><i class="fa-solid fa-eye"></i></a>';
                
                // Edit Button
                $btn .= '<a href="' . route('prescriptions.edit', $row->id) . '" class="btn btn-sm btn-outline-primary custom-dt-btn" data-bs-toggle="tooltip" title="Edit Prescription"><i class="fa-solid fa-pen-to-square"></i></a>';
                
                // Print Button
                $btn .= '<a href="' . route('prescriptions.print', $row->id) . '" target="_blank" class="btn btn-sm btn-outline-success custom-dt-btn" data-bs-toggle="tooltip" title="Print Prescription"><i class="fa-solid fa-print"></i></a>';
                
                // Delete Button
                $btn .= '<button type="button" class="btn btn-sm btn-outline-danger custom-dt-btn delete-btn" data-id="' . $row->id . '" data-bs-toggle="tooltip" title="Delete Prescription"><i class="fa-solid fa-trash"></i></button>';

                $btn .= '</div>';
                return $btn;
            })
            ->filterColumn('patient_info', function ($query, $keyword) {
                $query->where('patient_name', 'like', "%{$keyword}%")
                    ->orWhere('patient_phone', 'like', "%{$keyword}%")
                    ->orWhereHas('patient', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                          ->orWhere('phone', 'like', "%{$keyword}%");
                    });
            })
            ->filterColumn('doctor_name', function ($query, $keyword) {
                $query->whereHas('doctor', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['index', 'prescription_no', 'patient_info', 'items_count', 'action']);

        return $this->applyAucitColumnLogic($dataTable);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Prescription>
     */
    public function query(Prescription $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['doctor', 'patient'])
            ->withCount('items');
    }

    /**
     * Get the dataTable columns definition.
     */
    protected function getColumns(): array
    {
        return array_merge([
            $this->indexColumn(),
            Column::make('prescription_no')->title(__('file.table.prescription_no') ?? 'Prescription No')->addClass('align-middle'),
            Column::make('prescription_date')->title(__('file.table.date') ?? 'Date')->addClass('align-middle'),
            Column::make('patient_info')->title(__('file.table.patient_info') ?? 'Patient Info')->addClass('align-middle'),
            Column::make('doctor_name')->title(__('file.table.doctor') ?? 'Doctor')->addClass('align-middle'),
            Column::make('items_count')->title(__('file.table.items') ?? 'Medicines')->addClass('text-center align-middle')->searchable(false),
            Column::computed('action')
                ->title(__('file.table.action') ?? 'Action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center align-middle')
                ->responsivePriority(1),
        ], $this->auditColumns());
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Prescription_' . date('YmdHis');
    }
}