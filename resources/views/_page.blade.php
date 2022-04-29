<style>
    .file{
        border: 1px solid #eeeeee;
        margin: 3px;
        text-align: center;
        padding: 5px !important;
        cursor: pointer;
        max-width: 150px;
    }
    .file-view img{
        width: 100%;
    }
    .file-info{
        padding: 5px;
    }
    .file-info div{
        padding: 5px 0;
        overflow: hidden;
        white-space: nowrap;
    }
    /* custom */
    .btn-mini{
        height: 30px !important;
    }
    .select2-search--inline {
        display: inline-block;
        padding: 0.1rem 0rem;
    }
    .select2-container .select2-search--inline .select2-search__field{
        margin-top: 0px;
    }
    .select2-container--default .select2-dropdown .select2-search__field:focus, .select2-container--default .select2-search--inline .select2-search__field:focus{
        border: 0px;
    }
</style>
<div class="dcat-box custom-data-table dt-bootstrap4">
    @include('admin::grid.table-toolbar')
    {!! $grid->renderFilter() !!}

    {!! $grid->renderHeader() !!}

    <div class="{!! $grid->formatTableParentClass() !!}" id="{{ $tableId }}">
        <div class="d-flex flex-wrap">
            @foreach($grid->rows() as $row)
                <label class="file" id="file_{!! $row->column('id') !!}">
                    <div class="file-view">{!! $row->column('view_path') !!}</div>
                    <div class="file-info">
                        <div class="item">{!! $row->column('title') !!}</div>
                        <div class="d-flex justify-content-between">
                            <input id="{!! $row->column('id') !!}" data-id="{!! $row->column('id') !!}" data-name="{!! $row->column('file_name') !!}" data-path="{!! $row->column('path') !!}" data-type="{!! $row->column('type') !!}" type="checkbox">
                            <span>{!! $row->column('id') !!} / {!! $row->column('size') !!}</span>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    {!! $grid->renderFooter() !!}

    {!! $grid->renderPagination() !!}

</div>
<script>
    Dcat.ready(function () {
        // 手动触发异步渲染事件
        $(document).trigger('table:load');
    });
</script>
