<?php

namespace Dcat\Admin\MediaCenter\Http\Extensions\Actions;

use Dcat\Admin\Grid\BatchAction;
use Dcat\Admin\MediaCenter\Models\MediaGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Dcat\Admin\Widgets\Modal;
use Dcat\Admin\MediaCenter\Http\Extensions\Form\BatchMoveForm;

class BatchMove extends BatchAction
{

    protected $title = '<a><i class="feather icon-folder"></i> 移动分组</a>';

    protected $model;

    // 注意构造方法的参数必须要有默认值
    public function __construct(string $model = null)
    {
        $this->model = $model;
    }

    public function render()
    {
        $form = BatchMoveForm::make();

        return Modal::make()
            ->title('批量移动')
            ->body($form)
            ->onLoad($this->getModalScript())
            ->button($this->title);
    }


    public function getModalScript()
    {
        return <<<JS
// 获取选中的ID数组
var key = {$this->getSelectedKeysScript()}
$('#batchmoveid').val(key);
JS;

    }
}
