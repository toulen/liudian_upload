<?php
namespace Liudian\Upload\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use OSS\Core\OssException;
use OSS\OssClient;

class UploadController extends Controller
{

    use ControllerFoundation, CommonReturn;

    public function ajax(Request $request){

        $uploadType = config('liudian_upload.upload_type');

        return $this->$uploadType($request);
    }

    /**
     * 本地文件系统
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function filesystem($request){

        try {

            $path = 'public/uploads';

            $type = Input::get('type', 'image');

            $path .= '/' . $type;

            $path = $request->file('upload')->store($path);

            return self::returnOkJson([

                'url' => Storage::disk('local')->url($path)
            ]);

        }catch(\Exception $e){

            return self::returnErrorJson($e->getMessage());
        }
    }

    /**
     * 阿里云简单上传
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function aliyun($request){
        $type = Input::get('type', 'image');
        $timeout = config('liudian_upload.aliyun.accessTimeout');
        $accessKeyId = config('liudian_upload.aliyun.accessKeyId');
        $accessKeySecret = config('liudian_upload.aliyun.accessKeySecret');
        $endpoint = config('liudian_upload.aliyun.endpoint');
        $bucket= config('liudian_upload.aliyun.bucket');
        $object = config('liudian_upload.aliyun.path') . '/' . $type. '/';

        $filePath = $request->file('upload');
        $fileName = md5(rand(0, 999999) . '_' . microtime(true));
        $object .= $fileName . '.' . $filePath->extension();
        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $obj = $ossClient->uploadFile($bucket, $object, $filePath);
            $signedUrl = $ossClient->signUrl($bucket, $object, $timeout);
            return self::returnOkJson([
                'url' => $signedUrl,
                'file' => $object,
                'timeout' => time() + $timeout
            ]);
        } catch(OssException $e) {
            return self::returnErrorJson($e->getMessage());
        }
    }
}