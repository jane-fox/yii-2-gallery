<?php

namespace app\controllers;

use Yii;

use app\models\Post;

use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PostController implements the CRUD actions for Post model.
 */
class ProfileController extends Controller
{
	
	public function beforeAction($action)
    {
        // ...set `$this->enableCsrfValidation` here based on some conditions...
        // call parent method that will check CSRF if such property is true.
        return parent::beforeAction($action);
    }
	
	
    public function behaviors()
    {
		return [
			'access' => [

				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],

			],
		];
	}
	
	public function actionPosts($id){
		$user=User::find()->where(['=', 'username', $id])->one();
		if(empty($user)){
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		
		$posts=Post::find()->where(['=', 'deleted', false])->andWhere(['=', 'owner', $user->id])->orderBy('id')->all();
		
		$post_count=Post::find()->where(['=', 'deleted', false])->andWhere(['=', 'owner', $user->id])->count();
		$fave_count = Yii::$app->db->createCommand('select COUNT(*) from favorite INNER JOIN post ON favorite.post_id = post.id WHERE favorite.user_id = :user_id')->bindValue('user_id',$user->id)->queryAll();
		$fave_count=$fave_count[0]['count'];
		
		return $this->render('posts.twig', [
			'posts'  => $posts,
            'user' => $user,
			'post_count' => $post_count,
			'fave_count' => $fave_count
        ]);
	}
	
	public function actionFaves($id){
		$user=User::find()->where(['=', 'username', $id])->one();
		if(empty($user)){
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		
		$faves = Yii::$app->db->createCommand('select post.* from favorite INNER JOIN post ON favorite.post_id = post.id WHERE favorite.user_id = :user_id ORDER BY favorite.created_at DESC')->bindValue('user_id', $user->id)->queryAll();
		$faves = json_decode(json_encode($faves), FALSE);

		$post_count=Post::find()->where(['=', 'deleted', false])->andWhere(['=', 'owner', $user->id])->count();
		$fave_count = Yii::$app->db->createCommand('select COUNT(*) from favorite INNER JOIN post ON favorite.post_id = post.id WHERE favorite.user_id = :user_id')->bindValue('user_id',$user->id)->queryAll();
		$fave_count=$fave_count[0]['count'];
		
		return $this->render('faves.twig', [
			'faves'  => $faves,
            'user' => $user,
			'post_count' => $post_count,
			'fave_count' => $fave_count
        ]);
	}

	public function actionView($id){
		$user=User::find()->where(['=', 'username', $id])->one();
		if(empty($user)){
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		
		$recent_posts=Post::find()->where(['=', 'deleted', false])->andWhere(['=', 'owner', $user->id])->orderBy('id')->limit(4)->all();
		$recent_faves = Yii::$app->db->createCommand('select post.* from favorite INNER JOIN post ON favorite.post_id = post.id WHERE favorite.user_id = :user_id ORDER BY favorite.created_at DESC LIMIT 4')->bindValue('user_id', $user->id)->queryAll();
		$recent_faves = json_decode(json_encode($recent_faves), FALSE);

		$post_count=Post::find()->where(['=', 'deleted', false])->andWhere(['=', 'owner', $user->id])->count();
		$fave_count = Yii::$app->db->createCommand('select COUNT(*) from favorite INNER JOIN post ON favorite.post_id = post.id WHERE favorite.user_id = :user_id')->bindValue('user_id',$user->id)->queryAll();
		$fave_count=$fave_count[0]['count'];
		
		return $this->render('view.twig', [
            'user' => $user,
			'post_count' => $post_count,
			'fave_count' => $fave_count,
			
			'recent_posts' => $recent_posts,
			'recent_faves' => $recent_faves,

        ]);
    }
		
}
