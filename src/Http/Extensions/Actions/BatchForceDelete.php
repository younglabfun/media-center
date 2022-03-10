<?php

namespace Dcat\Admin\MediaCenter\Http\Extensions\Actions;

use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BatchForceDelete extends BatchAction
{

    protected $title = '<i class="feather icon-trash-2"></i> 彻底删除';

    protected $model;

    // 注意构造方法的参数必须要有默认值
    public function __construct(string $model = null)
    {
        $this->model = $model;
    }

    public function handle(Request $request)
    {
        $model = $request->get('model');

        foreach ((array) $this->getKey() as $key) {
            $data = $model::withTrashed()->findOrFail($key);
            Storage::delete($data['path']);
            $model::withTrashed()->findOrFail($key)->forceDelete();
        }

        return $this->response()->success('已删除')->refresh();
    }

    public function confirm()
    {
        return ['确定全部删除吗？'];
    }

    public function parameters()
    {
        return [
            'model' => $this->model,
        ];
    }
}
