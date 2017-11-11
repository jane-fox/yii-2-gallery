<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use app\models\Fave;
use app\helpers\Thumbnail;
use app\helpers\Type;
use app\helpers\Upload;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
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

	/**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex($display="all")
    {
     /*   $dataProvider = new ActiveDataProvider([
            'query' => Post::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);*/
		header("location: /post/all");
		exit();
    }
	
	public function actionAll() {
		$posts=Post::find()->where(['=', 'deleted', false])->orderBy('id desc')->all();
		return $this->render('index.twig', [
            'posts' => $posts,
        ]);
	}
	

	public function actionVideos() {
		$posts=Post::find()->where(['=', 'deleted', false])->andWhere(['like', 'type', '%'."video".'%', false])->orderBy('id desc')->all();
		return $this->render('index.twig', [
            'posts' => $posts,
        ]);
	}

	public function actionFave($id) {
		$post=$this->findModel($id);
		
		if($post->owner == Yii::$app->user->identity->id){
			throw new ForbiddenHttpException("You cannot fave your own posts.");
		}
		
		$is_faved=Fave::find()->where(['=', 'post_id', $post->id])->andWhere(['=', 'user_id', Yii::$app->user->identity->id])->count() > 0 ? true : false;
		if($is_faved){
			//remove fave
			Fave::find()->where(['=', 'post_id', $post->id])->andWhere(['=', 'user_id', Yii::$app->user->identity->id])->one()->delete();
		} else {
			//create fave
			$model = new Fave();
			$model->post_owner_id = $post->owner;
			$model->user_id = Yii::$app->user->identity->id;
			$model->post_id = $id;
			$model->created_at = time();
			$model->save();
		}
		return $this->redirect(['/post/' . intval($id)]);

	}

	public function actionTest() {
		$model = new Post();

		return $this->render('test.twig', [
			'model' => $model,
		]);
	}


	public function actionPictures() {
		$posts=Post::find()->where(['=', 'deleted', false])->andWhere(['like', 'type', '%'."image".'%', false])->all();
		return $this->render('index.twig', [
            'posts' => $posts,
        ]);
	}

	public function actionUpload() {

		//Security checks first
		if (Yii::$app->request->isPost) {
				if (!Yii::$app->user->isGuest) {

					//Data looks good. Check filename then save.
					$upload = Upload::content($_FILES['file']);

					echo json_encode($upload);

					exit();


				//User not logged in, redirect
				} else {
					return $this->redirect(['/login']);
				}

		//If user isnt POSTing, redirect them to upload page.
		} else {
			return $this->redirect(['/upload']);
		}

	}



	/**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
		$model=$this->findModel($id);
		$model->views=$model->views+1;
		$model->save();
		
		$is_faved=Fave::find()->where(['=', 'post_id', $model->id])->andWhere(['=', 'user_id', Yii::$app->user->identity->id])->count() > 0 ? true : false;
		$faves=Fave::find()->where(['=', 'post_id', $model->id])->count();
		
        return $this->render('view.twig', [
			'type' => Type::for_model($model),
            'model' => $model,
			'is_faved' => $is_faved,
			'faves' => $faves
        ]);
    }
	

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $model = new Post();

		if (Yii::$app->request->isPost) {



			$model->load(Yii::$app->request->post());//load post params into model?
			$model->owner = Yii::$app->user->identity->id;



			if ($model->validate()) {
			
				if ($model->save()) {


					//	$model->file->saveAs('uploads/' . $model->file->name);
					return $this->redirect(['view', 'id' => $model->id]);
				}
			}
		}

		


		return $this->render('create.twig', [
			'model' => $model,
		]);

	}

	/**
	 * Updates an existing Post model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
     */
    public function actionUpdate($id)
    {
		
		$model = $this->findModel($id);
		
		if(Yii::$app->user->isGuest){
			throw new ForbiddenHttpException("Access denied.");
		}

		if($model->owner!==Yii::$app->user->identity->id && !Yii::$app->user->identity->isAdmin){ 
			throw new ForbiddenHttpException("Access denied.");
		}
		

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update.twig', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		
		$model=$this->findModel($id);

		if(Yii::$app->user->isGuest){
			throw new ForbiddenHttpException("Access denied.");
		}

		if($model->owner!==Yii::$app->user->identity->id && !Yii::$app->user->identity->isAdmin){ 
			throw new ForbiddenHttpException("Access denied.");
		}
		
		if (Yii::$app->request->isPost) {
			
			
			$model->deleted=true;
			$model->save();
			

			return $this->redirect(['index']);
		} else {
			//confirmation page
			return $this->render('delete.twig', [
                'model' => $model,
            ]);
		}
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

