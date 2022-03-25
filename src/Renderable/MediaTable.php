<?php

namespace Dcat\Admin\MediaCenter\Renderable;

use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;
use Dcat\Admin\MediaCenter\Models\Media;
use Dcat\Admin\MediaCenter\Helpers\FileUtil;
use Dcat\Admin\MediaCenter\Http\Extensions\Actions\CopyCode;

class MediaTable extends LazyRenderable
{

    public function grid(): Grid
    {
        //$groupId = $this->group_id;

        return Grid::make( new Media(), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->disableRefreshButton();
            $grid->disableCreateButton();

            $grid->column('view_path', "文件")
                ->display(function() {
                    $preview = FileUtil::getFilePreview($this->type, $this->path);
                    if (substr($preview, 0, 1) == "<") {
                        return $preview;
                    } else {
                        $img = '<img data-action="preview-img" src="' . $preview . '"';
                        $img .= ' style="cursor:pointer" class="img img-thumbnail">';
                        return $img;
                    }
                });

            $grid->disableViewButton();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
            $grid->disableActions();

            $grid->filter(function($filter){
                $filter->panel();
                $filter->like('title', '名称')->width(3);
                $filter->in('type', '文件类型')->multipleSelect(FileUtil::getFileTypes())->width(6);
            });
            $grid->quickSearch(['file_name', 'title', 'type']);

            $grid->simplePaginate();
            $grid->view('dcat-admin.media-center::_page');
            $grid->paginate(25);
        });
    }

}
