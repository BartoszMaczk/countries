<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;


class RegisterForm extends ActiveRecord
{
    public $name;
    public $surname;
    public $username;
    public $password;
    public $email;
    public $age;
    public $verifyCode;
    public $activation_key;

    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [['name', 'surname', 'username', 'password', 'email', 'age'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'captcha'],
            [['age'], 'integer', 'max' => 99],
            [['age'], 'integer', 'min' => 0],
            [['username', 'name', 'surname', 'password'], 'string', 'max' => 20],
            [['username', 'email'], 'unique'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    public function generateActivation_key(){
        $this->activation_key = sha1(mt_rand(10000, 99999).time().$this->email);
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            $this->generateActivation_key();
            $activation_url = Url::toRoute(['site/activate', 'key' => $this->activation_key, 'id' => $this->username], true);

            Yii::$app->mailer->compose('activation-link', ['name'=>$this->name, 'link'=>$activation_url])
                ->setTo([$this->email => $this->name])
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo($email)
                ->setSubject("Link aktywacyjny. Lista krajow.")
                ->send();

            return true;
        }
        return false;
    }
}
