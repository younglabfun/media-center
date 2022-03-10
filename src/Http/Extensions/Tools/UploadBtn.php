<?php

namespace Dcat\Admin\MediaCenter\Http\Extensions\Tools;

use Dcat\Admin\MediaCenter\MediaCenterServiceProvider;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Tools\AbstractTool;
use Dcat\Admin\Widgets\Modal;

class UploadBtn extends AbstractTool
{

    public function render()
    {
        //Admin::script($this->script());
        $lang = [
           'drag_file_here' => MediaCenterServiceProvider::trans('media.drag_file_here')
           ,'select_file' => MediaCenterServiceProvider::trans('media.select_file')
           ,'select_file2' => MediaCenterServiceProvider::trans('media.select_file2')
           ,'uploader_delete' => MediaCenterServiceProvider::trans('media.uploader_delete')
           ,'uploader_right' => MediaCenterServiceProvider::trans('media.uploader_right')
           ,'uploader_left' => MediaCenterServiceProvider::trans('media.uploader_left')
        ];

        $viewData = [
            'lang'  => $lang,
        ];

        $modal = Modal::make()
        ->lg()
        ->title(trans('admin.upload'))
        ->centered() // 设置弹窗垂直居中
        ->body(view('dcat-admin.media-center::_upload', $viewData))
        ->button(view('dcat-admin.media-center::_upload_btn'));

        $modal->onHide(
            <<<JS
        //location.reload(); 
JS
        );

        return $modal;
    }
}