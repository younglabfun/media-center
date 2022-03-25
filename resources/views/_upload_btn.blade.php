<label class="btn btn-primary btn-mini btn-outline" id="uploadBtn"></label>
<script>
    (function( $ ){
        $(function() {
            var defaults = {!! $options !!};
            var options = {
                uploader: {
                    pick: {
                        id: '#uploadBtn',
                        label: '<i class="feather icon-upload"></i>&nbsp;上传文件',
                        style: "",
                    },
                    formData: {
                        _token: '{{csrf_token()}}'
                    },
                }
            };
            var setting = $.extend({}, defaults, options);
            var mc_uploader = new MCSelector(setting);
            mc_uploader.build();
        });
    })( jQuery );
</script>
