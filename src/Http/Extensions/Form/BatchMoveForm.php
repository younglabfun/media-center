<?php

namespace Dcat\Admin\MediaCenter\Http\Extensions\Form;

use Dcat\Admin\MediaCenter\Models\MediaGroup;
use Dcat\Admin\MediaCenter\Models\Media;
use Dcat\Admin\Widgets\Form;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Contracts\LazyRenderable;
use Illuminate\Http\Request;

class BatchMoveForm extends Form implements LazyRenderable
{
    use LazyWidget;
    /**
     * 接收表单提交
     * @param Request $request
     */
    public function handle(array $input)
    {
        $ids = explode(',', $input['id'] ?? null);
        if (!$ids)
        {
            $this->response()->error('参数错误!');
        }

        $data = ['group_id'=>$input['group_id']];
        Media::whereIn('id', $ids)->update($data);

        return $this->response()->success('移动成功')->refresh();
    }

    /**
     * 批量移动表单
     */
    public function form()
    {
        $this->select('group_id','媒体分组')->options(MediaGroup::selectOptions(null,'取消分组'));
        $this->hidden('id')->attribute('id', 'batchmoveid');

        $this->disableResetButton();
    }
}