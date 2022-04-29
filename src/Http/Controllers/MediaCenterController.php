<?php

namespace Jyounglabs\Http\Controllers;

use Jyounglabs\MediaCenterServiceProvider;
use Jyounglabs\Http\Extensions\Tools\UploadBtn;
use Jyounglabs\Http\Extensions\Tools\WitchGridView;
use Jyounglabs\Http\Extensions\Actions\Restore;
use Jyounglabs\Http\Extensions\Actions\BatchRestore;
use Jyounglabs\Http\Extensions\Actions\ForceDelete;
use Jyounglabs\Http\Extensions\Actions\BatchForceDelete;
use Jyounglabs\Http\Extensions\Actions\BatchMove;
use Jyounglabs\Http\Extensions\Actions\CopyCode;
use Jyounglabs\Helpers\FileUtil;
use Jyounglabs\Models\Media;
use Jyounglabs\Models\MediaGroup;
use Dcat\Admin\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Grid;
use Dcat\Admin\Form;
use Dcat\Admin\Admin;

class MediaCenterController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title(MediaCenterServiceProvider::trans('media.title'))
            ->description(MediaCenterServiceProvider::trans('media.list'))
            ->body($this->grid());
    }

    public function grid()
    {
        return Grid::make(new Media(), function (Grid $grid) {
            if (request()->get('_view_') !== 'list') {
                $grid->view('jyounglabs.media-center::grid');
                $grid->setActionClass(Grid\Displayers\Actions::class);
            }
            Admin::css('@extension/jyounglabs/media-center/css/index.css');
            Admin::js('@extension/jyounglabs/media-center/js/spotlight.bundle.js');

            $grid->model()->orderBy('id', 'desc');
            $grid->tools(new WitchGridView());
            $grid->tools(new UploadBtn());
            $grid->showBatchActions();
            if (request('_scope_') == 'trashed') {
                $grid->disableDeleteButton();
                $grid->disableBatchDelete();
            }
            $grid->showCreateButton(false);
            $grid->disableEditButton();
            $grid->disableViewButton();
            $grid->disableQuickEditButton();

            $grid->quickSearch(['id', 'title']);
            $grid->filter(function ($filter) {
                $filter->like('path', '文件名');
                $filter->like('title', '名称');
                // 范围过滤器，调用模型的`onlyTrashed`方法，查询出被软删除的数据。
                $filter->scope('trashed', '回收站')->onlyTrashed();
            });

            $grid->column('path', "文件")
                ->display(function() {
                    $preview = FileUtil::getFilePreview($this->type, $this->path);
                    //dump($preview)
                    if (substr($preview, 0, 1) == "<") {
                        return $preview;
                    } else {
                        $img = '<img src="' . $preview . '" data-src="'.url('uploads').'/'.$this->path.'" class="spotlight">';
                        return $img;
                    }
                });

            $grid->column('title','名称')->editable();
            $grid->mediaGroups('分组')->pluck('name')->label()->filter();
            $grid->column('size','大小')->display(function(){
                return FileUtil::getFormatBytes($this->size);
            })->width("100px");
            $grid->column('created_at','添加时间');

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if (request('_scope_') == 'trashed') {
                    $actions->append(new Restore(Media::class));
                    $actions->append(new ForceDelete(Media::class));
                }else{
                    $actions->append(new CopyCode(Media::class));
                    $actions->append(new CopyCode(Media::class, 'html'));
                    $actions->append(new CopyCode(Media::class, 'markdown'));
                }
            });
            $grid->batchActions(function (Grid\Tools\BatchActions $batch) {
                if (request('_scope_') == 'trashed') {
                    $batch->add(new BatchRestore(Media::class));
                    $batch->add(new BatchForceDelete(Media::class));
                }else{
                    $batch->add(new BatchMove(Media::class));
                }
            });
        });
    }
    /**
     * Create interface.
     *
     * @param  Content  $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->title(MediaCenterServiceProvider::trans('media.title'))
            ->description(trans('admin.create'))
            ->body($this->form());
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(Media::with('mediaGroups'), function (Form $form) {
            $form->text('title', '名称');
            $form->tree('media_groups', '分组')
                ->nodes(function () {
                    $nodes = (new MediaGroup())->allNodes();
                    return $nodes;
                })
                ->customFormat(function ($v) {
                    if (!$v) return [];
                    return array_column($v, 'id');
                });
        });
    }


    /**
     * Edit interface.
     *
     * @param  mixed  $id
     * @param  Content  $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title(MediaCenterServiceProvider::trans('media.title'))
            ->description(trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        return $this->form()->update($id);
    }

    /**
     * 删除文件
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);

        Media::destroy(array_filter($ids));

        return JsonResponse::make()
            ->success(trans('admin.delete_succeeded'))
            ->refresh()
            ->send();
    }

}
