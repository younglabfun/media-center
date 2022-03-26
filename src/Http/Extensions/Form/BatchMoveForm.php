<?php

namespace Jyounglabs\Http\Extensions\Form;

use Jyounglabs\Models\MediaGroup;
use Jyounglabs\Models\Media;
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
        if ($input['group_id'] == 0 )
        {
            foreach($ids as $mid) {
                $media = Media::find($mid);
                $media->mediaGroups()->detach();
            }
        }else {
            $group = MediaGroup::find($input['group_id']);
            $group->groupMedias()->toggle($ids); //同步关联
        }

        return $this->response()->success('移动成功')->refresh();
    }

    /**
     * 批量移动表单
     */
    public function form()
    {
        $this->select('group_id','媒体分组')
            ->options(MediaGroup::getOptions('清空分组'));
        $this->hidden('id')->attribute('id', 'batchmoveid');

        $this->disableResetButton();
    }
}
