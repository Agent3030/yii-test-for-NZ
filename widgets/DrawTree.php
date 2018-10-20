<?php
/**
 * Created by PhpStorm.
 * User: agent3030
 * Date: 18.10.18
 * Time: 11:31
 */

namespace app\widgets;


use yii\base\Widget;

/**
 * widget to draw category tree
 * Class DrawTree
 * @package app\widgets
 */

class DrawTree extends Widget
{
    public $tree;
    private $html;

    public function init()
    {

    }

    public function run()
    {
        $this->html .= "<div class='categories tree'>";
        $this->html .= $this->DrawTree($this->tree);

        $this->html .= "</div>";
        return $this->html;
    }

    /**
     * draws tree from $tree array by children branch
     * @param $children
     * @return string
     */
    protected function DrawTree($tree)
    {
        $html = '';
        $html .= "<ul>";
        if (is_array($tree)) {
            foreach ($tree as $child) {



                if (is_array($child)) {
                    $html .= $this->DrawBranch($child);
                    }
            }
        }

        $html .= "</ul>";

        return $html;
        }

    /**
     * recursevely draws children branches og categories tree
     * @param $branch
     * @return string
     */
        protected function DrawBranch($branch)
        {
            $html = '';
            foreach ($branch as $child) {
                if (is_array($child) && array_key_exists('id', $child))

                    if (array_key_exists('name', $child) && array_key_exists('link', $child)) {
                        $html .= "<li class='branch'><a href=" . $child['link'] . "/>" . $child['name'] . "</a><a href='category/edit/".$child['id']."'><i class='glyphicon glyphicon-pencil'></i> </a></a><a href='category/delete/".$child['id']."' data-method= 'post'><i class='glyphicon glyphicon-trash'></i> </a></li>";

                            if (array_key_exists('children', $child)) {

                               foreach($child['children'] as $item){

                                   $html .= "<li class='branch'><ul>";
                                   $html .= $this->DrawBranch($item);
                                   $html .= "</ul></li>";
                               }

                            }

                    }

                }

            return $html;
        }



}