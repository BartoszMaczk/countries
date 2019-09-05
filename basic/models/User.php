<?php

namespace app\models;
use phpDocumentor\Reflection\Types\This;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{

    public static function tableName()
    {
        return 'users';
    }
    public function rules()
    {
        return [
            [['username', 'password', 'name', 'surname', 'email', 'activKey', 'activationstatus'], 'required'],
            ['email', 'email'],
            [['username', 'email'], 'unique'],
            [['age'], 'integer', 'max' => 99],
            [['age'], 'integer', 'min' => 0],
            [['username', 'name', 'surname'], 'string', 'max' => 20],
            [['activationstatus'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'username',
            'password' => 'password',
            'name' => 'name',
            'surname' => 'surname',
            'email' => 'email',
            'age' => 'age',
            'activKey' => 'activKey',
            'activationstatus' => 'activationstatus',
        ];
    }

    public function relations()
    {
        return array(
            'codeRel'=>array(self::HAS_MANY, 'Visited', 'id'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        foreach (self::$users as $user) {
//            if ($user['accessToken'] === $token) {
//                return new static($user);
//            }
//        }
//
//        return null;
        throw new NotSupportedException();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username'=>$username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
//        return $this->authKey;
        throw new NotSupportedException();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
//        return $this->authKey === $authKey;
        throw new NotSupportedException();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public function validateActivationStatus(){
        return $this->activationstatus == 1;
    }

    public function getVisited(){
        return $this->hasMany(Visited::className(), ['id' => 'id']);
    }

    public function getCountry(){
        return $this->hasMany(Country::className(), ['idFlag'=>'idFlag'])->via('visited');
    }
}
