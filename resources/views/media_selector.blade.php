<style>
.img-thumbnail{
    width:100px;
    height:100px;
    cursor:pointer;
    object-fit: cover;
}
.file-icon{
    display: flex;
    font-size: 85px;
    justify-content: center;
}
.btn-light {
    color: #cccccc !important;
}
.btn-light:focus, .btn-light:hover {
    background-color: #EEE !important;
    box-shadow: 0 0px 0px 0px rgba(0,0,0,.1),0 0px 0px 0 rgba(0,0,0,.1),0 0px 0px 0px rgba(0,0,0,.1) !important;
    color: #9e9e9e !important;
}
.select2-container {
    display: flex;
}
</style>

<div class="{{$viewClass['form-group']}} {{ $class }}">

    <label for="{{$column}}" class="{{$viewClass['label']}} control-label">{!! $label !!}</label>

    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        <div class="input-group" id="mediaSelector">
            <input type="text" class="form-control" id="{{ $name }}" readonly>
            <div class="input-group-append">
                <div class="btn btn-info" id="uploadBtn">
                    <i class="feather icon-upload"></i> &nbsp;
                    {{Dcat\Admin\MediaCenter\MediaCenterServiceProvider::trans('media.upload')}}</div>
            </div>
            <div class="input-group-append" style="margin-left:5px;" {!! $attributes !!}>
            </div>
            <div class="input-group-append">
                {!! $dialog !!}
            </div>
        </div>
        <input type="hidden" class="form-control" name="{{ $name }}" value="{{$value}}" id="field_{{ $name }}">
        @include('admin::form.help-block')
        <ul class="d-flex flex-wrap list-inline help-block field_{{$name}}_display">
        </ul>
    </div>
</div>

<script require="@mselector" init="{!! $selector !!}">
    var dialogId = $('{!! $dialogSelector !!}').attr('id');

    (function( $ ){
        // 当domReady的时候开始初始化

        $(function() {
            var options = {!! $options !!};
            var inputId = '{{ $name }}';
            var inputVal = '{!! $value !!}';
            console.log(inputVal);

            // uploader
            var opts = $.extend({
                fieldId: inputId,
                fieldVal: inputVal,

                selector: {
                    dialog: '[data-id="' + dialogId + '"]',
                    dialogId: dialogId,
                    @if(isset($max))
                    multiple: true,
                    max: {{ $max }},
                    @endif
                }
            }, options);
            opts.uploader = $.extend({
                server: '/admin/uploadSerives',
                pick: {
                    id: '#uploadBtn',
                    style: "",
                },
                formData: {
                    _token: '{{csrf_token()}}'
                },
            }, opts.uploader);

            var uploader = new Uploader(opts);
            uploader.build();

        });
    })( jQuery );
</script>