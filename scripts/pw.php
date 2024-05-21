<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\FormSignup;
use app\models\User;
use yii\base\Security;
use yii\helpers\Url;

echo password_hash("12345", PASSWORD_DEFAULT);