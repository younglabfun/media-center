<?php

namespace Jyounglabs;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Jyounglabs\Http\Extensions\Form\MediaSelector;

class MediaCenterServiceProvider extends ServiceProvider
{
	protected $js = [
        //'js/index.js',
    ];
	protected $css = [
		//'css/index.css',
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
        Admin::requireAssets('jyounglabs.media-center');
        Admin::asset()->alias('@mselector', [
            'js' => [
                // 支持使用路径别名
                '@extension/jyounglabs/media-center/js/webuploader.min.js',
                '@extension/jyounglabs/media-center/js/mcselector.js',
                '@extension/jyounglabs/media-center/js/spotlight.bundle.js',
            ],
            'css' => [
                '@extension/jyounglabs/media-center/css/webuploader.css',
            ],
        ]);

        // 注册文件选择组件
        Form::extend('mediaSelector', MediaSelector::class);
    }
}
