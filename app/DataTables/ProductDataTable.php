<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends BaseDataTable
{
    protected string $tableId = 'products-table';
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Product> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn() 
            ->addColumn('index', function ($row) {
                return ''; // আমরা ব্লেডে জাস্ট চেকবক্স দেখাবো, তাই এখানে ব্ল্যাঙ্ক স্ট্রিং দিলেই হবে
            })
            ->setRowId('id')
            ->rawColumns(['index']);

        // BaseDataTable এর মেটা ও টাইম-স্ট্যাম্প লজিক অ্যাপ্লাই
        return $this->applyAucitColumnLogic($dataTable);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Product>
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('quantity', 'desc'); // ডিফল্টভাবে প্রোডাক্টগুলো কোয়ান্টিটি অনুযায়ী সাজানো হবে
    }

   /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return array_merge([
            $this->indexColumn(), // চেকবক্স/ইনডেক্স কলাম
            Column::make('code')->title('Code')->responsivePriority(1),
            Column::make('category_name')->title('Category')->style('font-size:13px;'),
            Column::make('name')->title('Product Name')->addClass('text-capitalize'),
            Column::make('price')->title('Price'),
            Column::make('quantity')->title('Quantity')->addClass('text-center'),
        ], $this->auditColumns()); // BaseDataTable এর অডিট কলাম মার্জ করা হলো
    }

    /**
     * Export Columns Range Overwrite
     */
    protected function getExportColumns(): array|string
    {
        return [1, 2, 3, 4, 5, 6]; // এক্সপোর্ট করার সময় একচুয়াল ডাটা কলাম রেঞ্জ
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
