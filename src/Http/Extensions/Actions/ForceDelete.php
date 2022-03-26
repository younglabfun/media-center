<?php
namespace Jyounglabs\Http\Extensions\Actions;

use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ForceDelete extends RowAction
{
    protected $title = ' <i class="feather icon-trash-2" title="彻底删除"></i> ';

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
        $data = $model::withTrashed()->findOrFail($key);
        Storage::delete($data['path']);

        $model::withTrashed()->findOrFail($key)->forceDelete();

        return $this->response()->success('已删除')->refresh();
    }

    public function confirm()
    {
        return ['确定彻底删除吗？'];
    }

    public function parameters()
    {
        return [
            'model' => $this->model,
        ];
    }

}
