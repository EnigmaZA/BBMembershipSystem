<?php namespace BB\Helpers;

use Intervention\Image\Facades\Image;

class UserImage {

    protected static $bucket = 'buildbrighton-bbms-dev';

    public function __construct()
    {

    }

    public function uploadPhoto($userId, $filePath)
    {
        $tmpFilePath = storage_path("tmp")."/".$userId.".png";
        $tmpFilePathThumb = storage_path("tmp")."/".$userId."-thumb.png";

        //Generate the thumbnail and larger image
        Image::make($filePath)->fit(500)->save($tmpFilePath);
        Image::make($filePath)->fit(200)->save($tmpFilePathThumb);

        $s3 = \AWS::get('s3');
        try {
            $s3->putObject(array(
                'Bucket'        => self::$bucket,
                'Key'           => \App::environment().'/user-photo/'.md5($userId).'.png',
                'Body'          => file_get_contents($tmpFilePath),
                'ACL'           => 'public-read',
                'ContentType'   => 'image/png'
            ));
        } catch(Exception $e) {
            \Log::exception($e);
            throw new UserImageFailedException();
        }

        try {
            $s3->putObject(array(
                'Bucket'        => self::$bucket,
                'Key'           => \App::environment().'/user-photo/'.md5($userId).'-thumb.png',
                'Body'          => file_get_contents($tmpFilePathThumb),
                'ACL'           => 'public-read',
                'ContentType'   => 'image/png'
            ));
        } catch(Exception $e) {
            \Log::exception($e);
            throw new UserImageFailedException();
        }

        \File::delete($tmpFilePath);
        \File::delete($tmpFilePathThumb);
    }

    public static function imageUrl($userId)
    {
        return "https://s3-eu-west-1.amazonaws.com/".self::$bucket."/".\App::environment().'/user-photo/'.md5($userId).'.png';
    }

    public static function thumbnailUrl($userId)
    {
        return "https://s3-eu-west-1.amazonaws.com/".self::$bucket."/".\App::environment().'/user-photo/'.md5($userId).'-thumb.png';
    }

} 