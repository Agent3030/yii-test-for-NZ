<?php

namespace app\controllers;

use Yii;
use app\models\Categories;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Categories model.
 */
class CategoryController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Categories::find(),
        ]);
        $categories = new Categories;
        $tree= $categories->getChildCategory();

        //VarDumper::dump($tree, 100, true);


        return $this->render('index', compact('dataProvider', 'tree'));
    }

    /**
     * Displays a single Categories model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Categories();
        $tree=$model->getChildCategory(true);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           if($model->parent_id==null) {
               $model-> parent_id=0;
               $model->save();
           }


            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create',
             compact('model', 'tree'));
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $tree=$model->getChildCategory(true);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->parent_id==null) {
                $model-> parent_id=0;
                $model->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', compact('model', 'tree'));
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();
        $this->deleteChildren($model-> id);


        return $this->redirect(['index']);
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function deleteChildren($id){

        $children = Categories::find()-> where(['parent_id'=> $id])-> all();
        //VarDumper::dump($children, 10, true);
        foreach ($children as $child) {

            $this-> deleteChildren($child->id);
            $child->delete();

        }
    }
}
