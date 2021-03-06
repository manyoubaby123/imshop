<?php

namespace im\cms\widgets;

use im\cms\models\MenuItem;
use im\tree\components\TreeHelper;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class Menu extends Widget
{
    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];

    /**
     * @var string
     */
    public $itemView = 'menu/menu_item';

    /**
     * @inheritdoc
     */
    public $depth;

    /**
     * @var string this property allows you to customize the HTML which is used to generate the drop down caret symbol.
     */
    public $dropDownCaret;

    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, ['widget' => 'nav']);
        if ($this->dropDownCaret === null) {
            $this->dropDownCaret = Html::tag('span', '', ['class' => 'caret']);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $items = MenuItem::find()->where(['menu_id' => 1, 'status' => MenuItem::STATUS_ACTIVE])->with(['icon', 'activeIcon', 'video'])->all();
        if ($items) {
            $items = TreeHelper::buildNodesTree($items);
        }

        return $this->render('menu/menu', [
            'widget' => $this,
            'items' => $items
        ]);
    }

    public static function setMenuItemOptions(MenuItem $item, MenuItem $parent = null)
    {
        $itemOptions = ['tag' => 'li'];
        $linkOptions = [];
        if ($parent && $parent->items_display == MenuItem::DISPLAY_GRID) {
            $itemOptions['tag'] = 'div';
            $itemOptions['class'] = $parent->items_css_classes;
        }
        $itemOptions = array_merge_recursive($itemOptions, $item->getHtmlAttributes());
        $children = $item->children;
        if ($children) {
            switch ($item->items_display) {
                case MenuItem::DISPLAY_DROPDOWN:
                    Html::addCssClass($itemOptions, 'dropdown');
                    //$linkOptions['data-toggle'] = 'dropdown';
                    $linkOptions['aria-haspopup'] = "true";
                    $linkOptions['aria-expanded'] = "false";
                    Html::addCssClass($linkOptions, 'dropdown-toggle');
                    $item->label .= ' <span class="caret"></span>';
                    break;
                case MenuItem::DISPLAY_FULL_WIDTH_DROPDOWN:
                    Html::addCssClass($itemOptions, 'dropdown dropdown-full-width');
                    //$linkOptions['data-toggle'] = 'dropdown';
                    $linkOptions['aria-haspopup'] = "true";
                    $linkOptions['aria-expanded'] = "false";
                    Html::addCssClass($linkOptions, 'dropdown-toggle');
                    $item->label .= ' <span class="caret"></span>';
                    break;
                case MenuItem::DISPLAY_GRID:
                    Html::addCssClass($itemOptions, 'dropdown dropdown-full-width');
                    //$linkOptions['data-toggle'] = 'dropdown';
                    $linkOptions['aria-haspopup'] = "true";
                    $linkOptions['aria-expanded'] = "false";
                    Html::addCssClass($linkOptions, 'dropdown-toggle');
                    $item->label .= ' <span class="caret"></span>';
                    break;
            }
        }

        $item->setHtmlAttributes($itemOptions);
        $item->setLinkHtmlAttributes($linkOptions);
    }
}