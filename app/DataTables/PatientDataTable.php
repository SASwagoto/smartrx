<?php

namespace App\DataTables;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PatientDataTable extends BaseDataTable
{
    protected string $tableId = 'patients-table';
    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn() // BaseDataTable এর indexColumn সাপোর্ট করার জন্য
            ->addColumn('index', function ($row) {
                return ''; // আমরা ব্লেডে জাস্ট চেকবক্স দেখাবো, তাই এখানে ব্ল্যাঙ্ক স্ট্রিং দিলেই হবে
            })
            ->editColumn('name', function ($row) {
                // পেশেন্টের ছবি সহ নাম দেখানোর সুন্দর স্মার্ট ইন্টারফেস
                $avatar = $row->image
                    ? asset('storage/' . $row->image)
                    : 'https://placehold.co/40x40?text=' . urlencode(substr($row->name, 0, 1));

                return '<a href="' . route('patients.show', $row->id) . '" class="d-flex align-items-center gap-3">
                            <img src="' . $avatar . '" class="rounded-circle border" style="width:36px; height:36px; object-fit:cover;">
                            <div>
                                <div class="fw-bold text-dark" style="font-size:13px;">' . e($row->name) . '</div>
                                <span class="badge bg-light text-secondary border px-2 py-1" style="font-size:10px;">' . e($row->patient_unique_id) . '</span>
                            </div>
                        </a>';
            })
            ->editColumn('blood_group', function ($row) {
                if (!$row->blood_group) return '<span class="text-muted">—</span>';
                $color = in_array($row->blood_group, ['O+', 'A+', 'B+', 'AB+']) ? 'danger' : 'warning';
                return '<span class="badge bg-' . $color . ' bg-opacity-10 text-' . $color . ' px-2 py-1 fw-bold">' . $row->blood_group . '</span>';
            })
            ->editColumn('is_active', function ($row) {
                $status = $row->is_active
                    ? '<span class="badge bg-success bg-opacity-10 text-success px-2 py-1"><i class="fa-solid fa-circle-check me-1"></i>Active</span>'
                    : '<span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1"><i class="fa-solid fa-circle-minus me-1"></i>Inactive</span>';
                return $status;
            })
            ->addColumn('action', function ($row) {
                // আপনার থিমের সাথে মিল রেখে কমপ্যাক্ট ড্রপডাউন বা ফ্লেক্স বাটন
                return '<div class="d-flex justify-content-center gap-1">
                            <a href="' . route('patients.show', $row->id) . '" class="btn btn-sm btn-light border no-outline-flash" title="View Profile"><i class="fa-solid fa-eye text-muted"></i></a>
                            <a href="' . route('patients.edit', $row->id) . '" class="btn btn-sm btn-light border no-outline-flash" title="Edit Patient"><i class="fa-solid fa-pen-to-square text-primary"></i></a>
                        </div>';
            })
            ->setRowId('id')
            ->rawColumns(['name', 'blood_group', 'is_active', 'action', 'created_at', 'updated_at']);

        // BaseDataTable এর মেটা ও টাইম-স্ট্যাম্প লজিক অ্যাপ্লাই
        return $this->applyAucitColumnLogic($dataTable);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Patient $model): QueryBuilder
    {
        // লেটেস্ট ডেটা সবার আগে দেখানোর জন্য এবং রিলেশন লোড করার জন্য
        return $model->newQuery()->with(['creator'])->latest('id');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return array_merge([
            $this->indexColumn(), // চেকবক্স/ইনডেক্স কলাম
            Column::make('name')->title('Patient Particulars')->responsivePriority(1),
            Column::make('phone_number')->title('Phone Number')->style('font-size:13px;'),
            Column::make('gender')->title('Gender')->addClass('text-capitalize'),
            Column::make('age')->title('Age'),
            Column::make('blood_group')->title('Blood')->addClass('text-center'),
            Column::make('is_active')->title('Status')->addClass('text-center'),
            Column::computed('action')->title('Actions')->exportable(false)->printable(false)->addClass('text-center')->width('80px'),
        ], $this->auditColumns()); // BaseDataTable এর অডিট কলাম মার্জ করা হলো
    }

    /**
     * Export Columns Range Overwrite
     */
    protected function getExportColumns(): array|string
    {
        return [1, 2, 3, 4, 5, 6]; // এক্সপোর্ট করার সময় একচুয়াল ডাটা কলাম রেঞ্জ
    }

    protected function filename(): string
    {
        return 'SmartRx_Patients_' . date('YmdHis');
    }
}