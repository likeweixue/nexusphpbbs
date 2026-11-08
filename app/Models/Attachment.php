<?php

namespace App\Models;

class Attachment extends ModelTable
{
    public static string $table = 'attachments';

    const IMG_EXTENSIONS = ['jpeg', 'jpg', 'png', 'gif', 'webp', 'bmp', 'tif', 'tiff', 'heic'];
}
