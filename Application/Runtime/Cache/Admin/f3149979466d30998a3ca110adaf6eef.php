<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html> 
<head>  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
    <title>多文件上传</title>  
    <script src="/api/Public/jquery/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/api/Public/layer/layer.js"></script> 
    <script src="/api/Public/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/api/Public/uploadify/uploadify.css" />  
</head>  
 
<body>  
    <h1>上传图片</h1>  
    <form>  
        <div id="queue"></div>  
        <input id="file_upload" name="file_upload" type="file" multiple="true" /> 
    </form>  
      
    <script type="text/javascript">  
        $(function() {
            $("#file_upload").uploadify({
                'swf'             : '/api/Public/uploadify/uploadify.swf',
                'uploader'        : '/api/Public/uploadify/uploadify.php',
                'onUploadSuccess' : function(file, data, response) {
                    alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
                }
            });
        });
    </script>
</body>  
</html>