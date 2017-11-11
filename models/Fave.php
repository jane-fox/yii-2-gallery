<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "favorite".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $post_id
 * @property integer $post_owner_id
 * @property integer $created_at
 */
class Fave extends \yii\db\ActiveRecord
{
	
   public static function primaryKey()
    {
        return static::getTableSchema()->primaryKey;
    }
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'favorite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Post ID',
            'post_id' => 'Post ID',
            'post_owner_id' => 'Post Owner ID',
            'created_at' => 'Created At'
        ];
    }
}
