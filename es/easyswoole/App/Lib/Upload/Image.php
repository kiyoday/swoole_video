<?php
namespace App\Lib\Upload;

class Image extends BaseType
{
    public $fileType = 'image';

    public $maxSize = 122;

    public $fileExtTypes = [
        'jpg',
        'jpeg',
        'png',
    ];

}