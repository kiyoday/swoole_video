<?php
namespace App\Lib\Upload;

class Video extends BaseType
{
    public $fileType = 'video';

    public $maxSize = 122;

    public $fileExtTypes = [
        'mp4',
        'x-flv',
    ];

}