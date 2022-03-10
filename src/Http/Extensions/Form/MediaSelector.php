<?php

namespace Dcat\Admin\MediaCenter\Http\Extensions\Form;

use Dcat\Admin\MediaCenter\Helpers\FileUtil;
use Dcat\Admin\Form\Field\MultipleSelectTable;
use Dcat\Admin\Form\Field;
use Dcat\Admin\MediaCenter\Renderable\MediaGroupTable;
use Dcat\Admin\MediaCenter\Renderable\MediaTable;
use Dcat\Admin\MediaCenter\MediaCenterServiceProvider;
use Dcat\Admin\Support\JavaScript;
use Illuminate\Support\Facades\Storage;
use Dcat\Admin\Widgets\DialogTable;
use Dcat\Admin\Widgets\LazyTable;
use Dcat\Admin\Widgets\Modal;
use Dcat\Admin\Admin;

class MediaSelector extends Field
{
    /**
     * 上传服务
     * @var string
     */
    protected $uploadService = '/admin/uploadSerives';
    
    protected $view = 'dcat-admin.media-center::media_selector';

    protected $selectStyle = 'success';

    /**
     * @var array
     */
    protected $types = [];

    public function __construct($column, array $arguments)
    {
        parent::__construct($column, $arguments);
        $this->types = FileUtil::getFileTypes();
    }

    /**
     * @return mixed
     */
    public function defaultDirectory()
    {
        return config('admin.upload.disk');
    }

    public function render()
    {
        $pathUrl = Storage::disk($this->defaultDirectory())->url('');
        $length = isset($this->options['length']) && ! empty($this->options['length']) ? $this->options['length'] : 1;
        $type = isset($this->options['type']) && ! empty($this->options['type']) ? $this->options['type'] : 'blend';

        $valideType = array_keys($this->types);
        if( !in_array($type, $valideType) ){
            $type = "blend";
        }

        $options = array_merge(
            [
                'length' => $length,
                'type' => $type,
                'config' => [
                    'uploadService'=> $this->uploadService,
                    'pathUrl'   => $pathUrl
                ],
                'uploader' => []
            ],
            $this->options
        );
        // 限制上传文件
        if ($type != 'blend'){
            $types = FileUtil::getTypesData();
            $accept = [
                'extensions'=> str_replace('|',',',$types[$type]),
                'mimeTypes'=> $type
            ];
            $options['uploader']['accept'] = $accept;
        }

        $this->style = $this->selectStyle;

        $dialog = Modal::make()
            ->lg()
            ->title("选择媒体")
            ->body(MediaTable::make())
            ->footer($this->renderFooter())
            ->button($this->renderButton());

        // 向视图添加变量
        $this->addVariables([
            'selector'    => $this->getElementClassSelector(),
            'options'       => JavaScript::format($options),
            'dialog'         => $dialog,
            'dialogSelector' => $dialog->getElementSelector(),
        ]);

        return parent::render();
    }


    protected function renderButton()
    {
        return <<<HTML
<div class="btn btn-{$this->selectStyle}" id="selectorBtn">
    <i class="feather icon-file-plus"></i>&nbsp;选择
</div>
HTML;
    }

    /**
     * 弹窗底部内容构建.
     *
     * @return string
     */
    protected function renderFooter()
    {
        $submit = trans('admin.submit');
        $cancel = trans('admin.cancel');

        return <<<HTML
<button class="btn btn-primary btn-sm submit-btn" data-dismiss="modal" aria-label="Close" style="color: #fff">&nbsp;{$submit}&nbsp;</button>&nbsp;
<button class="btn btn-white btn-sm cancel-btn close" data-dismiss="modal" aria-label="Close">
&nbsp;{$cancel}&nbsp;</button>
HTML;
    }

}