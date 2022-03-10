<?php

namespace Dcat\Admin\MediaCenter\Http\Controllers;

use Dcat\Admin\MediaCenter\MediaCenterServiceProvider;
use Dcat\Admin\MediaCenter\Http\Extensions\Tools\UploadBtn;
use Dcat\Admin\MediaCenter\Http\Extensions\Actions\Restore;
use Dcat\Admin\MediaCenter\Http\Extensions\Actions\BatchRestore;
use Dcat\Admin\MediaCenter\Http\Extensions\Actions\ForceDelete;
use Dcat\Admin\MediaCenter\Http\Extensions\Actions\BatchForceDelete;
use Dcat\Admin\MediaCenter\Http\Extensions\Actions\BatchMove;
use Dcat\Admin\MediaCenter\Http\Extensions\Actions\CopyCode;
use Dcat\Admin\MediaCenter\Helpers\FileUtil;
use Dcat\Admin\MediaCenter\Models\Media;
use Dcat\Admin\MediaCenter\Models\MediaGroup;
use Dcat\Admin\MediaCenter\Renderable\MediaTable;
use Dcat\Admin\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Grid;
use Dcat\Admin\Form;

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
            $grid->model()->orderBy('id', 'desc');

            $grid->showBatchActions();
            $grid->tools(new UploadBtn());
            if (request('_scope_') == 'trashed') {
                $grid->disableDeleteButton();
                $grid->disableBatchDelete();
            }
            $grid->showColumnSelector();
            $grid->showCreateButton(false);
            $grid->disableEditButton();
            $grid->disableViewButton();
            $grid->disableQuickEditButton(false);
            $grid->setDialogFormDimensions('500px', '280px');

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
                    if (substr($preview, 0, 1) == "<") {
                        return $preview;
                    } else {
                        $img = '<img data-action="preview-img" src="' . $preview . '"';
                        $img .= ' style="max-width:48px;max-height:48px;cursor:pointer" class="img img-thumbnail">';
                        return $img;
                    }
                })
                ->width('50px');

            $grid->column('title','名称')->editable();
            $grid->column('group_id','分组')
                ->width('110px')
                ->display(function(){
                    return Media::getGroupTitle($this->mediaGroup);
                })->label('info')
                ->filterByValue();
            $grid->column('type','类型')
                ->width("100px")->label('#222')
                ->filter(
                    Grid\Column\Filter\In::make(FileUtil::getFileTypes())
                )
                ->filterByValue();
            $grid->column('size','大小')->display(function(){
                return FileUtil::getFormatBytes($this->size);
            })->width("80px");
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
/*
    public function fileSelector(Content $content)
    {
        return $content
            ->title(FileManagerServiceProvider::trans('file.files'))
            ->description(FileManagerServiceProvider::trans('file.list'))
            ->body($this->grid());
    }
*/

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
        return Form::make(new Media(), function (Form $form) {
            $form->text('title', '名称');
            $form->select('group_id', '分组')->options(MediaGroup::selectOptions(null, '不分组'));
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

    /**
     * 更新字段
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request){
        if ($request->title == null && $request->group_id == null){
            return JsonResponse::make()
                ->error(trans('admin.update_failed'))
                ->send();
        }
        if ($request->title != null){
            $data['title'] = $request->title;
        }
        if ($request->group_id != null){
            $data['group_id'] = $request->group_id;
        }

        $result = Media::where("id",$id)->update($data);
        if ($result){
            return JsonResponse::make()
                ->success(trans('admin.update_succeeded'))
                ->send();
        }

        return JsonResponse::make()
            ->error(trans('admin.update_failed'))
            ->send();

    }
/*
    public function getFileGroupList()
    {
        $data = FileGroup::selectOptions();
        $list = [];
        foreach ($data as $id=>$name)
        {
            if ($id == 0) {
                $group = ['id' => 0, 'name' => '全部文件'];
            }else{
                $group = ['id' => $id, 'name' => $name];
            }
            $list[] = $group;
        }
        return $this->jsonResponse($list);
    }

    public function getFileList(Request $request)
    {
        $msg = '';
        $data = FileModel::query()->orderBy('updated_at', 'DESC')->get();
        $list = [];
        if ($data != null) {
            foreach ($data as $item) {
                if ($item['type'] == 'image') {
                    $preview = '<span class="preview"><img src="' . Storage::url($item['path']) . '"></span>';
                } else {
                    $pictures = FileUtil::getFilePreview($item['type'], $item['path']);
                    $preview = '<span class="fileicon"><img src="/vendor/dcat-admin-extensions/' . $pictures . '"></span>';
                }
                $file = [
                    'id' => $item['id']
                    , 'preview' => $preview
                    , 'title' => $item['title']
                    , 'size' => FileUtil::getFormatBytes($item['size'])
                ];
                $list[] = $file;
            }
        }else{
            $msg = 'No files record!';
        }

        return $this->jsonResponse($list, $msg);
    }

    protected function jsonResponse($data, $msg = '')
    {
        $code = ($msg != '')?1:0;
        $result = [
            'code'  => $code
            ,'msg'  => $msg
            ,'data' => $data
        ];
        return json_encode($result,JSON_UNESCAPED_UNICODE);
    }
*/
}