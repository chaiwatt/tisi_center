<?php


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 *
 */
class SHP {
    static function checkFileStorage($file_path)
    {//get file from storage

        $result = false;
        $public = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();

        if (is_file($public . $file_path)) {//ถ้ามีไลฟ์ที่พร้อมแสดงอยู่แล้ว
            $result = true;
        } else {

            $exists = File::exists($file_path);
            if ($exists) {//ถ้ามีไฟล์ใน storage
                $result = true;
            }
        }

        return true;

    }

    static function getFileStorage($file_path)
    {//get file from storage

        $result = '';
        $public = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();

        if (is_file($public . $file_path)) {//ถ้ามีไลฟ์ที่พร้อมแสดงอยู่แล้ว
            $result = Storage::disk('uploads')->url($file_path);
        } else {

            $exists = File::exists($file_path);
            if ($exists) {//ถ้ามีไฟล์ใน storage
                $stream = Storage::getDriver()->readStream($file_path);
                $byte_put = file_put_contents($public . $file_path, stream_get_contents($stream), FILE_APPEND);

                if ($byte_put !== false) {
                    $result = Storage::disk('uploads')->url($file_path);
                }
            }
        }

        return $result;

    }
}