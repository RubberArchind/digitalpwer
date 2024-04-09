<?php

namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

class HelloWidget extends Widget
{
    public $route;
    public $items;
    public $currentPath;  
    public $svg;

    public function init()
    {
        parent::init();
        if ($this->route === null) {
            $this->route = 'dashboard';
        }
    }

    public function run()
    {
        if($this->route->count() == $this->items->count()){
            $toRender = '<nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">';
            $route = $this->route;
            $items = $this->items;
            $svgs = $this->svg;
            for($i=0; $i<$route; $i++){
                $toRender = $toRender . sprintf('<li class=" ">
                <a href="%s" class="svg-icon">
                    %s
                    <span class="ml-4">%s</span>
                </a>
            </li>', $route[$i], $svgs[$i], $items[$i]);
            }
            $toRender = $toRender."</ul>
            </nav>";
            
            return Html::encode($toRender);
        }else{
            return Html::encode("");
        }
    }
}

?>