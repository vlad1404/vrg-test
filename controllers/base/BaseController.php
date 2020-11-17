<?php
namespace app\controllers\base;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

class BaseController extends Controller
{
    const ACTION_LOGIN = [
        'create',
        'update',
        'delete'
    ];

    public function beforeAction($action)
    {
        if(in_array($action->id, self::ACTION_LOGIN) && Yii::$app->user->isGuest){
            throw new HttpException(403);
        }
        return parent::beforeAction($action);
    }
}