<?php
namespace Jyounglabs\Http\Controllers;

use Jyounglabs\Models\Media;
use Jyounglabs\Helpers\FileUtil;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\HasUploadedFile;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Jyounglabs\MediaCenterServiceProvider;

class UploadController
{
    use HasUploadedFile;

    public function handle(Request $request)
    {
        // 获取上传的文件
        $files = $request->file();
        if ($files == null) {
            return $this->responseErrorMessage(MediaCenterServiceProvider::trans('media.upload_error_none'));
        }
        foreach ($files as $file) {
            $uploadData = $this->upload($file);
        }
        return !$uploadData
            ? $this->responseErrorMessage('文件上传失败')
            : JsonResponse::make()
                ->success(MediaCenterServiceProvider::trans('media.upload_sucesses'))
                ->data($uploadData)
                ->refresh();
    }

    /**
     * for markdown editor
     * @param Request $request
     * @return array
     */
    public function markdownUpload(Request $request)
    {
        // 获取上传的文件
        $files = $request->file();
        if ($files == null) {
            return ['success' => 0];
        }
        foreach ($files as $file)
        {
            $uploadData = $this->upload($file);
        }

        return ['success' => 1, 'url' => $uploadData['url']];

    }

    /**
     * @return mixed
     */
    public function defaultDirectory()
    {
        return config('admin.upload.disk');
    }

    protected function upload(UploadedFile $file)
    {

        $mimeType = $file->getMimeType();
        $typeInfo = $this->_getTypeInfoByMimeType($mimeType);

        // 配置项 是否使用文件夹
        $folderName = MediaCenterServiceProvider::setting('folderName');
        if (!$folderName) {
            $folder = date("Y-m-d");
        }else{
            $folder = './';
        }

        // 配置项 是否使用随机名称
        $uniqueName = MediaCenterServiceProvider::setting('uniqueName');

        $fileName = $file->getClientOriginalName();
        if(!$uniqueName) {
            // 使用原文件名
            if (!$this->checkFile($fileName))
            {
                $nameArr = explode('.', $fileName);
                $fileName = $nameArr[0].'_'.time().'.'.$nameArr[1];
                //dump($fileName);
            }
            $path = $file->storeAs($folder, $fileName);
        }else{
            $path = $file->store($folder, $fileName);
        }

        $dir = $this->defaultDirectory();
        $fileType = FileUtil::getFileType(Storage::disk($dir)->url($path));

        $meta = $this->_getMeta($file, $fileType, $typeInfo['suffix']);

        $data = [
            'path'          => $path
            ,'title'        => $fileName
            ,'file_name'    => $fileName
            ,'size'         => $file->getSize()
            ,'meta'         => json_encode($meta)
            ,'type'         => $fileType
            ,'created_at'   => date("Y-m-d H:i:s")
        ];

        $insertId = Media::query()->insertGetId($data);
        if ($insertId) {
            $result = [
                'id'        => $insertId,
                'path'      => $path,
                'name'      => $fileName,
                'fileType'  => $fileType,
                'url'       => public_path('uploads').'/'.$path,
                'thumbnail' => FileUtil::getFilePreview($fileType, $path)
            ];
            return $result;
        }
        return false;
    }

    private function checkFile($fileName)
    {
        $result = Media::where('path', 'like', '%'.$fileName.'%')->count();
        if ($result != 0){
            return false;
        }
        return true;
    }

    private function _getTypeInfoByMimeType($mt)
    {
        $arr = explode('/', $mt);
        return [
            'type' => $arr[0],
            'suffix' => $arr[1]
        ];
    }

    private function _getMeta($file, $getFileType, $format)
    {
        switch ($getFileType) {
            case 'image':
                $manager = new ImageManager();
                $image = $manager->make($file);
                $meta = [
                    'format' => $format,
                    'suffix' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'width' => $image->getWidth(),
                    'height' => $image->getHeight()
                ];
                break;
            case 'pdf':
            case 'zip':
            case 'word':
            case 'ppt':
            case 'xsl':
            case 'video':
            case 'audio':
            case 'code':
            case 'text':
                $meta = [
                    'format' => $format,
                    'suffix' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'width' => 0,
                    'height' => 0
                ];
                break;
            default :
                $meta = [
                    'format' => $format,
                    'suffix' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'width' => 0,
                    'height' => 0
                ];;
        }
        return $meta;
    }
}
