<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "visited".
 *
 * @property int $idrel
 * @property int $id
 * @property int $idFlag
 *
 * @property User $id0
 * @property Country $flag
 */
class Visited extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visited';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idFlag'], 'required'],
            [['idFlag'], 'integer'],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id' => 'id']],
            [['idFlag'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['idFlag' => 'idflag']],
        ];
    }

    /**
     * {@inheritdoc}
     */

    public function getFlag(){
        return $this->hasOne(Country::className(), ['idFlag' => 'idFlag']);
    }
    public function getId0(){
        return $this->hasOne(User::className(), ['id' => 'id']);
    }

    public function beforeValidate(){
        if(is_array($this->id)) {
            $lastId = 1;
            foreach ($this->id as $oneId){
                if(sizeof($this->id) > 1) {
                    $newRecord = new Visited();
                    $newRecord->id = $oneId;
                    $newRecord->idFlag = $this->idFlag;
                    $newRecord->save();
                    unset($oneId);
                } else { $lastId = $oneId; }
            }
            $this->id = implode(', ', $this->id);
            $this->id = $lastId;
        }

        return parent::beforeValidate();
    }

}
