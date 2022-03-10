<?php

namespace Dcat\Admin\MediaCenter\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Traits\ModelTree;
use Spatie\EloquentSortable\Sortable;

class MediaGroup extends Model implements Sortable
{
	use HasDateTimeFormatter;
    use SoftDeletes;
    use ModelTree {
        allNodes as treeAllNodes;
        ModelTree::boot as treeBoot;
        }

    protected $table = 'media_groups';

    protected $fillable = ['parent_id', 'name', 'order'];

    protected $sortable = [
        // 设置排序字段名称
        'order_column_name' => 'order',
        // 是否在创建时自动排序，此参数建议设置为true
        'sort_when_creating' => true,
    ];

    public function media()
    {
        return $this->hasMany(Media::class, 'group_id');
    }

    public static function getOptions($rootText = '')
    {
        $root = ($rootText=='')?trans('global.labels.root'):$rootText;
        $options = [0=>$root];

        $where = ['parent_id'=>0];
        $data = static::where($where)
            ->orderBy('order', 'asc')
            ->get();
        foreach ($data as $item)
        {
            $options[$item['id']] = $item['title'];
        }
        return $options;
    }
}
