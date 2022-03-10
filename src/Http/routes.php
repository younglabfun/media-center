<?php

use Dcat\Admin\MediaCenter\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::resource('media-center', Controllers\MediaCenterController::class);
Route::resource('media-group', Controllers\MediaGroupController::class);
/* upload services */
Route::any('uploadSerives', Controllers\UploadController::class.'@handle');
// for markdown editor
Route::any('mdUploadSerives', Controllers\UploadController::class.'@markdownUpload');
/* selector */
//Route::get('getFileList', Controllers\FileManagerController::class.'@getFileList');
//Route::get('getFileGroupList', Controllers\FileManagerController::class.'@getFileGroupList');