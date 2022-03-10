<?php
namespace Dcat\Admin\MediaCenter\Http\Extensions\Actions;

use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;

class Restore extends RowAction
{
    protected $title = ' <i class="feather icon-rotate-ccw" title="恢复"></i> ';

    protected $model;

    // 注意构造方法的参数必须要有默认值
    public function __construct(string $model = null)
    {
        $this->model = $model;
    }

    public function handle(Request $request)
    {
        $key = $this->getKey();
        $model = $request->get('model');

        $model::withTrashed()->findOrFail($key)->restore();

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