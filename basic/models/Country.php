<?php

namespace app\models;
use PHPUnit\Framework\Constraint\Count;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "country".
 *
 * @property int $idFlag
 * @property string $code
 * @property string $name
 * @property string $flag
 * @property string $languages
 * @property Visited[] $visiteds
 */
class Country extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'flag', 'languages'], 'required'],
            [['flag', 'languages'], 'string'],
            [['code'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 52],
            [['code', 'name', 'flag'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */


    public function getImageurl(){
        $url = YII::$app->request->baseUrl.'/flags/'.$this->flag;
        return Html::img($url, ['alt'=>"flaga $this->name", 'class'=>'flagIcon']);
    }

    public function getVisited(){
        return $this->hasMany(Visited::className(), ['idFlag' => 'idFlag']);
    }
    public function getUser(){
        return $this->hasMany(User::className(), ['id'=>'id'])->via('visited');
    }

    public static function countVisitors($country){
        return Visited::find()->where("idFlag=$country->idFlag")->count();
    }

    public function showFlagList(){
        $imgFiles = FileHelper::findFiles(Yii::getAlias('@webroot').'\\flags',['only'=>['*.png']]);
        $flagsList = [];
        foreach ($imgFiles as $flag){
            $explodeImg = explode('\\', $flag);
            $imgName = end($explodeImg);
            $url = YII::$app->request->baseUrl.'/flags/'.$imgName;
            $flagsList[$imgName] = Html::img($url, ['class'=>'flagIcon flagList']);
        }
        return $flagsList;
    }

    public function showUsersList(){
        $allUsers = User::find()->select('username, users.id')->all();
        $usersList = [];
        foreach ($allUsers as $user){
            $usersList[$user->id] = $user->username;
        }
        return $usersList;
    }

    public static function isVisited($idFlag)
    {
        $id = Yii::$app->user->getId();
        $list = User::find()
                ->where("users.id=$id")
                ->joinWith('country')->one();
        foreach($list->country as $country){
            if($country->idFlag == $idFlag){
                return true;
            }
        }
        return false;
    }

}
