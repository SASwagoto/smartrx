<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;

class UsersDataTable extends BaseDataTable
{
    protected string $tableId = 'users-table';

    protected function getExportColumns(): array|string
    {
        return [1, 2, 3];
    }
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $datatable = (new EloquentDataTable($query))
            ->addColumn('index', function ($row) {
                return '<input type="checkbox" class="row-checkbox mt-2" value="' . $row->id . '" />';
            })
            ->addColumn('action', function ($row) {
                $deleteUrl = '#';
                $edit = '<a href="javascript:void(0)" onclick="editUser(\'' . (string) $row->id . '\')" class="btn  btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>';
                $delete = '<button type="button" data-url="' . $deleteUrl . '" data-table-id="#users-table" data-name="User" class="btn btn-danger delete-btn btn-sm"><i class="fa-solid fa-trash"></i></button>';

                return $edit . ' ' . $delete;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at ? $row->updated_at->format('Y-m-d H:i:s') : '';
            })
            ->rawColumns(['index', 'action']);
        return $datatable;
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            $this->indexColumn(),
            Column::make('id'),
            Column::make('name'),
            Column::make('email'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('action')->exportable(false)->printable(false)->width(60)->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
