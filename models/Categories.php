<?php

namespace app\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $namec
 * @property string $link
 * @property int $parent_id
 * @property int $created_at
 * @property int $updated_at
 */
class Categories extends \yii\db\ActiveRecord
{
    public $children;
    protected $tree = [];
    protected $categories;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 256],
            [['link'], 'string', 'max' => 1024],
        ];
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'link' => 'Link',
            'parent_id' => 'Parent ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return CategoriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoriesQuery(get_called_class());
    }

    /**
     * @return array
     */

    public function getChildCategory($select=false)
    {
        $this->categories = static::find()-> all();
        $this-> tree =$this->createTree($this->categories, $select);

        return $this->tree;


    }

    /**
     * creates tree of categories based on parent_id
     * @param Categories collection  $models
     * @param int $rootId
     * @return array
     */

    private function createTree($models,$select= false)
    {
        $tree = [];

        foreach ($models as  $id => $node) {
                if($node->parent_id== 0) {
                    $node->children = $this->getChildren($node, $select);
                    if($select == false) {
                        $tree['categories'][$node->id] = ['id' => $node->id, 'name' => $node->name, 'link' => $node->link, 'children' => $node->children];
                    } else {
                        $tree['categories'][$node->id] = [$node->id => $node->name,'children' => $node->children];
                    }
                }
        }
        return $tree;
    }

    /**
     * recursively get children based on id
     * @param Categories $node
     * @return array
     */
    private function getChildren ($node, $select= false )
    {
        $tree = [] ;
        $childArr=[];
        $id = $node->id;
        $children = static::find()->where(['parent_id' => $id])->All();
        foreach ($children as $child) {
            if($select == false){
            $childArr[]=['id' =>$child-> id, 'name'=>$child-> name, 'link'=>$child->link,  'children'=>$this->getChildren($child)];
            } else{
                $childArr[]=[$child->id =>$child->name, 'children'=>$this->getChildren($child, $select)];

            }
        }
            $tree[] = $childArr;
        return $tree;
    }


}