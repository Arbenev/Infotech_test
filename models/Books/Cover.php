<?php

namespace app\models\Books;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Cover
 *
 * @author acround
 */
class Cover extends Model
{

    /**
     * @var UploadedFile
     */
    public $cover;

    public function rules()
    {
        return [
            [['cover'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload($id = null)
    {
        if ($this->validate()) {
            $baseName = $this->getBaseName($id);
            $this->cover->saveAs(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . \Yii::$app->params['coverDirectory'] . $baseName . '.' . $this->cover->extension);
            return $baseName;
        } else {
            return false;
        }
    }

    private function getBaseName($id = 0)
    {
        if ($id) {
            while (strlen($id) < 4) {
                $id = '0' . $id;
            }
            $baseName = 'book_' . $id;
        } else {
            $baseName = $this->cover->baseName;
        }
    }
}
