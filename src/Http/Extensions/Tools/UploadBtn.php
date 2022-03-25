<?php

namespace Dcat\Admin\MediaCenter\Http\Extensions\Tools;

use Dcat\Admin\MediaCenter\Helpers\FileUtil;
use Dcat\Admin\MediaCenter\MediaCenterServiceProvider;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Tools\AbstractTool;
use Dcat\Admin\Support\JavaScript;
use Illuminate\Support\Facades\Storage;
use Dcat\Admin\Admin;

class UploadBtn extends AbstractTool
{
    /**
     * 上传服务
     * @var string
     */
    protected $uploadService;

    protected $view = 'dcat-admin.media-center::_upload_btn';

    protected $limit = 10; // 最多一次10个文件

    protected $type = 'blend'; // 不限文件类型

    /**
     * @return mixed
     */
    public function defaultDirectory()
    {
        return config('admin.upload.disk');
    }


    public function render()
    {
        $this->uploadService = MediaCenterServiceProvider::setting('uploadService');
        $pathUrl = Storage::disk($this->defaultDirectory())->url('');

        $valideType = array_keys(FileUtil::getFileTypes());
        $ext = '';
        if( in_array($this->type, $valideType) ) {
            $ext = implode(",", FileUtil::getTypes($this->type));
        }else{
            $this->type = "blend";
        }

        $options = [
            'mode' => 'single',
            'config' => [
                'uploadService'=> $this->uploadService,
                'pathUrl'   => $pathUrl,
                'length' => $this->limit,
                'type' => $this->type,
                'ext' => $ext,
            ],
            'uploader' => []
        ];

        // 限制上传文件
        if ($this->type != 'blend'){
            $accept = [
                'extensions'=> str_replace('|',',',$valideType[$this->type]),
                'mimeTypes'=> $this->type
            ];
            $options['uploader']['accept'] = $accept;
        }

        $viewData = [
            'options'       => JavaScript::format($options)
        ];
        Admin::requireAssets(['@mselector']);
        //Admin::requireAssets(['@mcupload']);
        return view($this->view, $viewData);
    }

}
