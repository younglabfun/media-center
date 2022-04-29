<?php

namespace Jyounglabs\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class FileUtil
{
    public static $fileTypes = [
        'image' => 'bmp|cgm|djv|djvu|gif|ico|ief|jp2|jpe|jpeg|jpg|mac|pbm|pct|pgm|pic|pict|png|pnm|pnt|pntg|ppm|qti|qtif|ras|rgb|svg|tif|tiff|wbmp|xbm|xpm|xwd',
        'audio' => 'mp3|wav|flac|3pg|aa|aac|ape|au|m4a|mpc|ogg',
        'video' => 'mkv|rmvb|flv|mp4|avi|wmv|rm|asf|mpeg',
        'code' => 'php|js|java|python|ruby|go|c|cpp|sql|m|h|json|html|aspx',
        'zip' => 'zip|tar\.gz|rar|rpm|7z',
        'text' => 'txt|pac|log|md',
        'word'  => 'doc|dot|docx|dotx|docm|dotm',
        'xls'  => 'xls|xlt|xla|xlsx|xltx|xlsm|xltm|xlam|xlsb',
        'ppt'   => 'ppt|pot|pps|ppa|pptx|potx|ppsx|ppam|pptm|potm|ppsm',
        'pdf'   => 'pdf',
    ];

    public static $thumbnailPath = 'thumbnails';
    public static $thumbnailSize = '180';

    public static function getTypesData()
    {
        return self::$fileTypes;
    }

    public static function getFileTypes()
    {
        $filetypes = array_keys(self::$fileTypes);
        $filterOptions = [];
        foreach ($filetypes as $item){
            $filterOptions[$item] = $item;
        }
        return $filterOptions;
    }

    public static function getTypes( $typeName )
    {
        $filetypes = array_keys(self::$fileTypes);
        if (in_array( $typeName, $filetypes))
        {
            $typestr = self::$fileTypes[$typeName];
            $types = explode("|", $typestr);
            return $types;
        }
        return "";
    }
    /**
     * @param $filePath | 文件绝对路径
     * @return bool|int|string
     */
    public static function getFileType($filePath)
    {
        foreach (self::$fileTypes as $type => $regex) {
            if (preg_match("/^($regex)$/i", self::getExtension($filePath)) !== 0) {
                return $type;
            }
        }
        return 'other';
    }

    public static function getFileSize($filePath)
    {
        return File::size($filePath);
    }

    public static function getBasename($filePath)
    {
        return File::basename($filePath);
    }

    public static function getExtension($filePath)
    {
        return File::extension($filePath);
    }

    public static function getFormatBytes($size)
    {
        $units = array(' B', ' KB', ' M', ' G', ' T');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $units[$i];
    }

    public static function getThumbnailName($filePath, $size)
    {
        $fileName = File::basename($filePath);
        $arr = explode('.', $fileName);
        $thumbnail = $arr[0].'_s'.$size.'.'.$arr[1];
        return $thumbnail;
    }

    public static function getFilePreview($fileType, $path)
    {
        switch ($fileType) {
            case 'image':
                $sourcePath = public_path('uploads') .'/'. $path;
                if (file_exists($sourcePath)){
                    // 缩略图
                    $thumbnailFile = self::$thumbnailPath.'/'.self::getThumbnailName($path, self::$thumbnailSize);
                    $thumbnailPath = public_path('uploads').'/'.$thumbnailFile;
                    if (!file_exists($thumbnailPath))
                    {
                        $thumbnailDir = public_path('uploads').'/'.self::$thumbnailPath;
                        if(!is_readable($thumbnailDir))
                            mkdir($thumbnailDir,0700);

                        $img = Image::make($sourcePath);
                        $img->fit(self::$thumbnailSize);
                        $img->save($thumbnailPath);
                    }
                    return Storage::url($thumbnailFile);
                }
                $preview = 'fa-file-image-o';
                break;

            case 'audio':
                $preview = 'fa-file-audio-o';
                break;
            case 'video':
                $preview = 'fa-file-video-o';
                break;
            case 'code':
                $preview = 'fa-file-code-o';
                break;
            case 'zip':
                $preview = 'fa-file-zip-o';
                break;
            case 'text':
                $preview = 'fa-file-text-o';
                break;
            case 'word':
                $preview = 'fa-file-word-o';
                break;
            case 'xls':
                $preview = 'fa-file-excel-o';
                break;
            case 'ppt':
                $preview = 'fa-file-powerpoint-o';
                break;
            case 'pdf':
                $preview = 'fa-file-pdf-o';
                break;
            default:
                $preview = 'fa-file-o';
        }

        return "<i class='fa ".$preview."' style='font-size:24px;'></i>";
    }

}
