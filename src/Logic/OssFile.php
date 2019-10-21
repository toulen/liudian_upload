<?php
namespace Liudian\Upload\Logic;

use Liudian\Admin\Helper\CommonReturn;
use OSS\Core\OssException;
use OSS\OssClient;

class OssFile
{

    use CommonReturn;

    // 授权访问阿里云文件
    public function aliyun($file){
        $timeout = config('liudian_upload.aliyun.accessTimeout');
        $accessKeyId = config('liudian_upload.aliyun.accessKeyId');
        $accessKeySecret = config('liudian_upload.aliyun.accessKeySecret');
        $endpoint = config('liudian_upload.aliyun.endpoint');
        $bucket= config('liudian_upload.aliyun.bucket');
        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $signedUrl = $ossClient->signUrl($bucket, $file, $timeout);

            return self::returnOkArr([
                'url' => $signedUrl,
                'file' => $file,
                'timeout' => time() + $timeout
            ]);
        } catch(OssException $e) {

            return self::returnErrorArr($e->getMessage());
        }
    }
}