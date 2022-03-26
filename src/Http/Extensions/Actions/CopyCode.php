<?php
namespace Jyounglabs\Http\Extensions\Actions;

use Dcat\Admin\Grid\RowAction;
use Illuminate\Support\Facades\Storage;

class CopyCode extends RowAction
{
    protected $model;
    protected $code;

    // 注意构造方法的参数必须要有默认值
    public function __construct(string $model = null, string $code = '')
    {
        $this->model = $model;
        $this->code = $code;
    }

    public function title()
    {
        if ($this->code == 'html'){
            $this->title = ' <i class="fa fa-code" title="复制Html代码"></i> ';
        }else if ($this->code == 'markdown'){
            $this->title = ' <i class="feather icon-hash" title="复制Markdown代码"></i> ';
        }else{
            $this->title = ' <i class="fa fa-link" title="复制链接"></i> ';
        }
        return $this->title;
    }

    public function html()
    {
        $id = $this->getKey();
        $content = $this->getContent($this->row->type, Storage::url($this->row->path), $this->row->title);

        // 这里需要添加一个class, 和script方法对应
        $this->setHtmlAttribute([
            'data-id' => $id,
            'data-toggle' => 'tooltip',
            'data-placement' => 'bottom',
            'data-content' => $content,
            'title' => '复制完成',
            'class' => 'grid-check-row tooltip-show'
        ]);

        return parent::html();
    }

    protected function getContent($type, $path, $title)
    {
        switch ($this->code) {
            case 'html':
                if ($type == 'image') {
                    $content = '<img src="' . $path . '" title="' . $title . '">';
                } else {
                    $content = '<a href="' . $path . '" title="' . $title . '" target="_blank">' . $title . '</a>';
                }
                break;
            case 'markdown':
                $content = '[' . $title . '](' . $path . ')';
                if ($type == 'image') {
                    $content = '!' . $content;
                }
                break;
            default:
                $content = $path;

        }
        return $content;
    }

    public function script()
    {
        return <<<JS
$('.grid-check-row').on('click', function () {
    var content = $(this).attr('data-content');
    var tmp = $('<input>');
    $("body").append(tmp);
    tmp.val(content).select();
    document.execCommand("copy");
    tmp.remove();

    $(this).tooltip('show');
    //console.log('response', response, target);
});
JS;
        $this->response()->success('成功！');
    }
}
