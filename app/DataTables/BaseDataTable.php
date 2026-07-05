<?php

namespace App\DataTables;

use App\Models\Base;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

abstract class BaseDataTable extends DataTable
{
    protected string $tableId = 'datatable';

    protected function getExportColumns(): array|string
    {
        return [0, 1, 2, 3];
    }

    protected function disabledButtons(): array
    {
        return [];
    }

    protected function showUtilityButtons(): bool
    {
        return true;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->getTableId())
            ->columns($this->getColumns())
            ->minifiedAjax('', null, $this->getAjaxParams())
            ->parameters([
                'stateSave' => true,
                'scrollY' => '70vh',
                'scrollCollapse' => true,
                'fixedHeader' => ['header' => true],
                'responsive' => true,
                'autoWidth' => false,
                'order' => [[1, 'asc']],
                'dom' => "<'row mb-3 align-items-center'
                    <'col-md-2 d-none d-md-block'l>
                    <'col-md-5 order-3 order-md-2 dt-search-wrapper'f>
                    <'col-md-5 order-2 order-md-3 d-flex align-items-center justify-content-md-end justify-content-center'B>
                  >"
                . "<'row'<'col-12'tr>>"
                . "<'row mt-3'
                    <'col-md-5'i>
                    <'col-md-7 d-flex justify-content-md-end justify-content-center'p>
                  >",
                'buttons' => $this->getButtons(),
                'language' => $this->getLanguageConfig(),
                // 🚀 মেইন ফিক্স: টেবিল পেজিনেশন বা রি-ড্র হলে আগের ঝুলে থাকা টুলটিপ ডিলেট করার ইভেন্ট
                'drawCallback' => 'function() { $(".tooltip").remove(); }'
            ]);
    }

    /**
     * Generate common buttons for the table (Optimized for premium UI)
     */
    protected function getButtons(): array
    {
        // সবুজের বদলে স্লিক অ্যান্ড ক্লিন আউটলাইন ক্লাসে কনভার্ট করা হলো
        $btns = [
            'excel' => ['icon' => 'fa-file-excel', 'color' => '#16a34a'],
            'csv'   => ['icon' => 'fa-file-csv', 'color' => '#475569'],
            'pdf'   => ['icon' => 'fa-file-pdf', 'color' => '#dc2626'],
            'print' => ['icon' => 'fa-print', 'color' => '#2563eb'],
        ];

        $config = [];
        foreach ($btns as $type => $attr) {
            if (in_array($type, $this->disabledButtons())) {
                continue;
            }

            $config[] = [
                'extend' => $type,
                'className' => "btn btn-outline-secondary custom-dt-btn me-1",
                'text' => '<i class="fa-solid ' . $attr['icon'] . '" style="color: ' . $attr['color'] . ';"></i>',
                'exportOptions' => ['columns' => $this->getExportColumns()],
                // বুটস্ট্রাপ নেটিভ টুলটিপ অ্যাট্রিবিউট ডাইনামিকালি পুশ
                'init' => "function(dt, node, config){ $(node).attr({
                    'data-bs-toggle': 'tooltip',
                    'data-bs-trigger': 'hover',
                    'title': 'Export to " . ucfirst($type) . "'
                }); }"
            ];
        }

        // Column Visibility Button (ColVis)
        $config[] = [
            'extend' => 'colvis',
            'className' => 'btn btn-outline-secondary custom-dt-btn me-1',
            'text' => '<i class="fa-solid fa-columns text-secondary"></i>',
            'columnText' => 'function ( dt, idx, title ) {
                let cleanTitle = title.replace(/<[^>]*>?/gm, "").trim();
                return cleanTitle.length > 0 ? cleanTitle : "Select All";
            }',
            'align' => 'button-right',
            'init' => "function(dt, node, config){ $(node).attr({
                'data-bs-toggle': 'tooltip',
                'data-bs-trigger': 'hover',
                'title': 'Show/Hide Columns'
            }); }"
        ];

        $utils = [];
        if ($this->showUtilityButtons()) {
            $utils = [
                [
                    'text' => '<i class="fa-solid fa-rotate-left text-warning"></i>',
                    'className' => 'btn btn-outline-secondary custom-dt-btn me-1',
                    'action' => 'function ( e, dt, node, config ) { dt.search("").columns().search("").draw(); }',
                    'init' => 'function(dt, node, config){ $(node).attr({
                        "data-bs-toggle": "tooltip",
                        "data-bs-trigger": "hover",
                        "title": "Clear Filters"
                    }); }'
                ],
                [
                    'text' => '<i class="fa-solid fa-arrows-rotate text-info"></i>',
                    'className' => 'btn btn-outline-secondary custom-dt-btn me-1',
                    'action' => 'function ( e, dt, node, config ) { dt.ajax.reload(); }',
                    'init' => 'function(dt, node, config){ $(node).attr({
                        "data-bs-toggle": "tooltip",
                        "data-bs-trigger": "hover",
                        "title": "Refresh Table"
                    }); }'
                ]
            ];
        }

        return array_merge($config, $utils, $this->getCustomButtons());
    }

    protected function indexColumn(): Column
    {
        return Column::make('index')
            ->title('<input type="checkbox" class="form-check-input select-all" />')
            ->exportable(false)
            ->printable(false)
            ->orderable(false)
            ->searchable(false)
            ->addClass('text-center')
            ->width('20px')
            ->responsivePriority(1);
    }

    protected function auditColumns(): array
    {
        return [
            Column::make('created_at')->title('<div class="text-end">' . __('file.table.created_at') . '</div>')->addClass('text-end')->visible(false)->printable(false)->exportable(true),
            Column::make('updated_at')->title('<div class="text-end">' . __('file.table.updated_at') . '</div>')->addClass('text-end')->visible(false)->printable(false)->exportable(true),
        ];
    }

    protected function applyAucitColumnLogic($dataTable)
    {
        return $dataTable
            ->editColumn('created_at', function ($row) {
                $date = Carbon::parse($row->created_at)->format('d-m-Y');
                $user = isset($row->created_by) ? '<br><small class="text-muted">by: ' . $row->creator->name . '</small>' : '';
                return $date . $user;
            })
            ->editColumn('updated_at', function ($row) {
                $date = Carbon::parse($row->updated_at)->format('d-m-Y');
                $user = isset($row->updated_by) ? '<br><small class="text-muted">by: ' . $row->updater->name . '</small>' : '';
                return $date . $user;
            });
    }

    protected function getLanguageConfig(): array
    {
        return [
            'search' => '',
            'searchPlaceholder' => __('file.table.search') . '...',
            'lengthMenu' => "_MENU_ ",
            'info' => __('file.table.showing') . ' _START_ ' . __('file.table.to') . ' _END_ ' . __('file.table.of') . ' _TOTAL_ ' . __('file.table.entries'),
            'infoEmpty' => __('file.table.no_entries'),
            'infoFiltered' => '(' . __('file.table.filtered_from') . ' _MAX_ ' . __('file.table.total_entries') . ')',
            'zeroRecords' => __('file.table.no_matching_records'),
            'paginate' => [
                'previous' => __('file.table.prev'),
                'next' => __('file.table.next'),
            ],
        ];
    }

    protected function getTableId(): string
    {
        return $this->tableId;
    }
    protected function getAjaxParams(): array
    {
        return [];
    }
    protected function getCustomButtons(): array
    {
        return [];
    }
    abstract protected function getColumns(): array;
}