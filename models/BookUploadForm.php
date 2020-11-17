<?php


namespace app\models;


use yii\base\Model;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

class BookUploadForm extends Model
{
    /**
     * @var UploadedFile
     */

    public $src;

    public function rules()
    {
        return [
            [['src'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif, jpeg, ico', 'maxFiles' => 1,'maxSize' => 1024 * 1024 * 2],
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function upload()
    {
        if ($this->validate()) {
            $path = 'uploads';
            $picture_name = \Yii::$app->security->generateRandomString(14) . '.' . $this->src->extension;

            if (FileHelper::createDirectory($path))
                if ($this->src->saveAs($path . '/' . $picture_name))
                    return $this->savePicture($path, $picture_name);
        }

        return false;
    }

    /**
     * @param $path
     * @param $picture_name
     *
     * @return bool
     */
    private function savePicture($path, $picture_name)
    {
        Image::thumbnail($path . '/' . $picture_name, 300, 300)
            ->save($path . '/' . $picture_name, ['quality' => 100]);

        return $path . '/' . $picture_name;
    }

}