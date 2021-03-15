<?php
/**
 * @author:  ccforevercn<1253705861@qq.com>
 * @link     http://ccforever.cn
 * @license  https://github.com/ccforevercn
 * @day:     2020/8/21
 */
namespace App\CcForever\traits;

use App\CcForever\service\Time;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\Image as ExceptionsUploads;

/**
 * 文件上传
 *
 * Trait UploadsValidateTrait
 * @package App\CcForever\traits
 */
trait UploadsValidateTrait
{
    //上传图片格式限制
    private $imageValidateExt = ['jpg' ,'jpeg' ,'png' ,'gif'];

    //上传图片的Mime类型限制
    private $imageValidateMime = ['image/jpeg' ,'image/gif' ,'image/png'];

    //上传图片的大小限制
    private $imageValidateSize = 2097152;

    // 文件内容非法字符检测
    private $ILLEGAL_CHAR = ['php', 'eval', 'system', 'jsp', 'net'];

    /**
     * 检测非法字符
     *
     * @param object $file
     * @return bool
     */
    private function illegalCharacter(object $file): bool
    {
        // 只读方式打开文件
        $fp = fopen($file->getPathname(),'r');
        ob_clean(); // 清空当前缓冲区的数据
        flush(); // 刷新输出缓冲
        $stream = fread($fp, $file->getSize()); // 读取文件流
        fclose($fp); // 关闭文件
        // 验证文件流中是否存在非法字符
        foreach ($this->ILLEGAL_CHAR as &$value){if(strpos($stream, $value)){return false;}}
        return true;
    }

    /**
     * 上传
     *
     * @param object $file
     * @param string $path
     * @return array
     * @throws ExceptionsUploads
     */
    private function upload(object $file, string $path): array
    {
        // 检测非法字符
        $result = $this->illegalCharacter($file);
        if(!$result){ throw new ExceptionsUploads('上传图片文件有非法字符'); }
        $originalName = $file->getClientOriginalName(); // 获取上传文件名称
        $ext = $file->getClientOriginalExtension(); // 获取上传文件后缀
        // 判断上传图片文件类型
        if(!in_array($ext, $this->imageValidateExt)){throw new ExceptionsUploads('上传文件图片格式错误：'. $ext);}
        $realPath = $file->getRealPath(); // 获取上传文件路径
        $mimeType = $file->getClientMimeType(); // 获取上传文件的Mime类型
        // 判断上传图片文件Mime类型
        if(!in_array($mimeType, $this->imageValidateMime)){throw new ExceptionsUploads('上传文件图片Mime格式错误：'. $ext);}
        // 获取上传图片文件大小
        $size = $file->getSize();
        if($this->imageValidateSize < $size){throw new ExceptionsUploads('上传文件图片大小不能超过：'. $this->imageValidateSize);}
        // 获取上传图片路径
        $directorySeparator = '/';
        $newFilePath = (new Time())->path($path, 2, $directorySeparator);
        $newFileName = $newFilePath . md5((new Time())->millisecond()). '.' . $ext;
        //上传图片
        if (Storage::disk('upload')->put($newFileName, file_get_contents($realPath))) {
            return ['name' => $originalName, 'path'=> $directorySeparator .'upload'. $newFileName];
        }
        throw new ExceptionsUploads('上传文件图片失败！！！');
    }

    /**
     * 删除
     *
     * @param string $path
     * @throws ExceptionsUploads
     */
    private function remove(string $path): void
    {
        // 数据库记录删除完成
        try{
            $path = str_replace('/upload', '', $path);
            Storage::disk('upload')->delete($path);
        }catch(\Exception $exception){
            throw new ExceptionsUploads($exception->getMessage());
        }
    }

}
