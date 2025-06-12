<?php

namespace App\DataTables;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    protected $exportedClass = UsersExport::class;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */

    protected string|array $exportColumns = ["name", "email", "total_posts"];
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $query->latest();
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn("total_posts", function ($row) {
                $total_posts = $row->posts()->count();
                if (!$total_posts) {
                    return "No Posts";
                }
                return $total_posts . " Posts";
            })
            ->addColumn("action", function ($row) {
                $btn = '<a onclick="updateRecord(' . $row->id . ', ' . $row->id . ')" class="edit btn btn-primary btn-sm">Edit</a>
                        <a style="color: white;" onclick="deleteRecord(' . $row->id . ', ' . $row->id . ')" class="delete btn btn-danger btn-sm">Delete</a>';
                return $btn;
            })
            ->setRowId('user-{{$id}}');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'dom'          => 'Blfrtip',
                'buttons'      => ['excel', 'csv'],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            'id',
            [
                'data' => 'name',
                'className' => "name-cell"
            ],
            [
                'data' => 'email',
                'className' => 'email-cell'
            ],
            'total_posts',
            'action'
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
