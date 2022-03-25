<?php

namespace Dcat\Admin\MediaCenter\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\MediaCenter\Models\MediaGroup;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dcat\Admin\Traits\Resizable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
	use HasDateTimeFormatter,
		Resizable,
		SoftDeletes;

	protected $table = 'medias';

	protected $fillable = [
		'file_name', 'path', 'title', 'size', 'type', 'meta'
		,'updated_at', 'public'
	];

    /**
     * 定义关联模型
     * belongsToMany
     */
    public function mediaGroups(): BelongsToMany
    {
        $pivotTable = 'media_group_relation';
        $relatedModel = MediaGroup::class;

        return $this->belongsToMany($relatedModel, $pivotTable, 'media_id', 'group_id')
            ->withTimestamps();;
    }

	public static function getGroupTitle( $group )
	{
		if ($group == null){
			return '未分组';
		}else{
			return $group->title;
		}
	}

}
