<?php

namespace Dcat\Admin\MediaCenter\Http\Controllers;

use Illuminate\Routing\Controller;
use Dcat\Admin\MediaCenter\Models\MediaGroup;
use Dcat\Admin\MediaCenter\MediaCenterServiceProvider;
use Dcat\Admin\Form;
use Dcat\Admin\Tree;
use Dcat\Admin\Widgets\Form as WidgetForm;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Widgets\Box;

class MediaGroupController extends Controller
{

    public function index(Content $content)
    {
        return $content
            ->title(MediaCenterServiceProvider::trans('media.media_group'))
            ->description(MediaCenterServiceProvider::trans('media.list'))
            ->body(function (Row $row){
                $row->column(7, $this->treeView());
                $row->column(5, function (Column $column) {
                    $form = new WidgetForm();

                    $form->select('parent_id', trans('admin.parent_id'))->default(0)->options(MediaGroup::getOptions());
                    $form->text('title', trans('admin.name'));
                    $form->number('order', trans('admin.order'));
                    $form->width(9, 2);

                    $column->append(Box::make(trans('admin.new'), $form));
                });
            });
    }

    protected function treeView()
    {
        return new Tree(new MediaGroup(), function (Tree $tree) {

            $tree->disableCreateButton();
            $tree->disableQuickCreateButton();
            $tree->disableEditButton();
            $tree->showQuickEditButton();
            $tree->setDialogFormDimensions('500px', '400px');
            $tree->maxDepth(2);

            $tree->branch(function ($branch) {

                $payload = "<strong>{$branch['title']}</strong>";
                $data = $branch->loadCount('media');
                $payload.= " (".$data['media_count'].")";
                return $payload;
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new MediaGroup(), function (Form $form) {
            $form->select('parent_id', trans('admin.parent_id'))->options(MediaGroup::getOptions());
            $form->text('title');
            $form->number('order');
        });
    }

    public function store()
    {
        return $this->form()->store();
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
            ->title(MediaCenterServiceProvider::trans('media.media_group'))
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->form()->destroy($id);
    }

}
