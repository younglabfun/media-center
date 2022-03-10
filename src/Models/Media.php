<?php

namespace Dcat\Admin\MediaCenter\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dcat\Admin\Traits\Resizable;

class Media extends Model
{
	use HasDateTimeFormatter,
		Resizable,
		SoftDeletes;

	protected $table = 'medias';
	
	protected $fillable = [
		'file_name', 'path', 'title', 'size', 'type', 'meta'
		,'group_id','updated_at', 'public'
	];

	public function mediaGroup()
	{
		return $this->belongsTo(MediaGroup::class,'group_id');
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

/*
INSERT INTO `files` (`group_id`, `path`, `type`, `title`, `file_name`, `size`, `meta`, `show`, `created_at`, `updated_at`, `deleted_at`) VALUES
(0, 'upload_files/823451502e0bf6f2adc6ee828f27e03a.jpg', 'image', 'test file', 'fojing.jpg', 1231231, 'image/jpg', 1, NULL, NULL, NULL);

 * */
