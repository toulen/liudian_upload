<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<a href="javascript:;" class="uploadBtn">上传</a>
</body>
<script>
    $('.uploadBtn').h5upload({
        fileTypeExts: 'jpg,png,gif,jpeg',
        url: "{{route('upload_ajax')}}",
        fileObjName: 'upload',
        fileSizeLimit: 10 * 1024 * 1024,
        formData: {'_token': '{{ csrf_token() }}', 'type': 'image'},

        //进度监控
        onUploadProgress: function (file, data) {
            $('.uploadBtn').html('上传中...');
        },

        // 上传成功的动作
        onUploadSuccess: function (file, res) {
            res = JSON.parse(res);
        }
    });
</script>
</html>