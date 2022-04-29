<style>
</style>


<div class="dcat-box custom-data-table dt-bootstrap4">

    @include('admin::grid.table-toolbar')

    {!! $grid->renderFilter() !!}

    {!! $grid->renderHeader() !!}

    <div class="table-responsive table-wrapper mt-1">
        <ul class="mailbox-attachments clearfix {{ $grid->formatTableClass() }} p-0"  id="{{ $tableId }}">
            @foreach($grid->rows() as $row)
                <li>
                    <span class="mailbox-attachment-icon has-img">
                        {!! $row->column('path') !!}
                    </span>
                    <div class="mailbox-attachment-info">
                        <div class="d-flex justify-content-between item">
                            {!! $row->column('id') !!} / {!! $row->column('size') !!}
                        </div>

                        <div class="d-flex justify-content-between item">
                            {!! $row->column('title') !!}
                        </div>

                        <span class="d-flex justify-content-between" style="margin-top: 5px">
                            {!! $row->column(Dcat\Admin\Grid\Column::SELECT_COLUMN_NAME) !!}
                            <div>{!! $row->column(Dcat\Admin\Grid\Column::ACTION_COLUMN_NAME) !!}</div>
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {!! $grid->renderFooter() !!}

    @include('admin::grid.table-pagination')

</div>
