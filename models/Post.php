<?php

namespace app\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $file
 * @property integer $owner
 * @property string $type
 * @property integer $created_at
 * @property string $text
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }
	
	public function getOwner(){
		$user=User::find()->where(['=', 'id', $this->owner])->one();
		return $user;
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['file', 'text'], 'string'],
            [['type', 'tags', 'thumb'], 'string'],
            [['owner', 'created_at', 'views'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => 'File',
            'owner' => 'Owner',
            'type' => 'Type',
            'created_at' => 'Created At',
            'text' => 'Text',
            'tags' => 'Tags',
            'views' => "Views",
            'thumb' => 'Thumbnail'
        ];
    }
}
