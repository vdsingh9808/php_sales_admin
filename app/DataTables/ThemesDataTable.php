<?php

namespace App\DataTables;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ThemesDataTable extends DataTable
{

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', 'pages.themes._columns.name')
            ->addColumn('action', 'pages.themes._columns.action')
            ->addColumn('logo', 'pages.themes._columns.logo')
            ->editColumn('menu_name', function ($model) {
                return view('pages.themes._columns.menu', compact('model'))->render();
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw("name like ?", ["%{$keyword}%"]);
            })
            ->rawColumns(['action', 'menu_name', 'logo', 'name'])
            ->setRowId('id');
    }


    public function query(Theme $model): QueryBuilder
    {
        return $model->newQuery();
    }


    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('themes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'bSort' => false,
            ]);
    }


    public function getColumns(): array
    {
        return [
            Column::make('name')->orderable(false),
            Column::make('menu_name')->title('menu')->orderable(false),
            Column::make('logo')->orderable(false),
            Column::computed('action')
                ->exportable(false)
                ->orderable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Themes_' . date('YmdHis');
    }
}
