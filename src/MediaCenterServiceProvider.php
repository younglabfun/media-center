<?php

namespace Dcat\Admin\MediaCenter;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\MediaCenter\Http\Extensions\Form\MediaSelector;

class MediaCenterServiceProvider extends ServiceProvider
{
	protected $js = [
    ];
	protected $css = [
	];
	// 定义菜单
	protected $menu = [
		[
			'title' => 'Media Center',
			'uri'   => '',
			'icon'  => 'fa fa-play-circle-o',
		],
		[
			'parent' => 'Media Center',
			'title'  => 'Media List',
			'uri'    => 'media-center',
			'icon'  => 'fa fa-file-image-o',
		],
		[
			'parent' => 'Media Center',
			'title'  => 'Group List',
			'uri'    => 'media-group',
			'icon' 	 =>	'fa fa-folder-open-o'
		],
	];

	public function settingForm()
	{
		return new Setting($this);
	}

	// 公共资源文件
	public function init()
	{
		parent::init();
		Admin::requireAssets('dcat-admin.media-center');
		Admin::asset()->alias('@mselector', [
			'js' => [
				// 支持使用路径别名
				'@extension/dcat-admin/media-center/js/webuploader.min.js',
				'@extension/dcat-admin/media-center/js/upload.js',
				'@extension/dcat-admin/media-center/js/jquery.splitter-0.14.0.js',
			],
			'css' => [
				'@extension/dcat-admin/media-center/css/webuploader.css',
				'@extension/dcat-admin/media-center/css/jquery.splitter.css',
				'@extension/dcat-admin/media-center/css/index.css',
			],
		]);

		// 注册文件选择组件
		Form::extend('mediaSelector', MediaSelector::class);
	}
}
