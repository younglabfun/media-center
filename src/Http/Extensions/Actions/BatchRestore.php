<?php

namespace Jyounglabs\Http\Extensions\Actions;

use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

class BatchRestore extends BatchAction
{

    protected $title = '<i class="feather icon-rotate-ccw"></i> 恢复';

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
            $model::withTrashed()->findOrFail($key)->restore();
        }

        return $this->response()->success('已恢复')->refresh();
    }

    public function confirm()
    {
        return ['确定恢复吗？'];
    }

    public function parameters()
    {
        return [
            'model' => $this->model,
        ];
    }
}
