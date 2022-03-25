<style>
    .file{
        border: 1px solid #eeeeee;
        margin: 7px;
        text-align: center;
        padding: 1rem !important;
        cursor: pointer;
    }
    .file-view img{
        width: 100px;
        margin: 5px;
    }
    .file-view i{
        font-size: 100px !important;
        margin: 5px 12px;
    }
    .file-title div{
        padding: 0;
        margin:0;
    }
    .file-title span{
        width: 80px;
        overflow: hidden;
        display: block;
        word-break: break-all;

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
<div class="dcat-box">

    <div class="d-block pb-0">
        @include('admin::grid.table-toolbar')
    </div>
    {!! $grid->renderFilter() !!}

    {!! $grid->renderHeader() !!}

    <div class="{!! $grid->formatTableParentClass() !!}" id="{{ $tableId }}">
        <div class="d-flex flex-wrap">
            @foreach($grid->rows() as $row)
                <label class="file" id="file_{!! $row->column('id') !!}">
                    <div class="file-view">{!! $row->column('view_path') !!}</div>
                    <div class="file-title row">
                        <div class="col-2">
                            <input id="{!! $row->column('id') !!}" data-id="{!! $row->column('id') !!}" data-name="{!! $row->column('file_name') !!}" data-path="{!! $row->column('path') !!}" data-type="{!! $row->column('type') !!}" type="checkbox">
                        </div>
                        <div class="col-10">
                            <span>{!! $row->column('title') !!}</span>
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
