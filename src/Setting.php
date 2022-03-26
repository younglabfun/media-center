<?php

namespace Jyounglabs;

use Dcat\Admin\Extend\Setting as Form;

class Setting extends Form
{
    public function title()
    {
        return $this->trans('media.title');
    }

    public function form()
    {
        $this->text('uploadService', '上传服务')->default('/admin/uploadSerives');
        $this->radio('folderName', '文件夹规则')->options([0=>'日期命名(年月日)',1=>'不分文件夹'])->default(0);
        $this->radio('uniqueName', '文件名规则')->options([0=>'原文件名',1=>'随机名称'])->default(0);
    }
}
