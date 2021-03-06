<?php

namespace im\cms\components;

use im\cms\models\Template;
use im\cms\models\widgets\Widget;
use im\cms\models\widgets\WidgetArea;
use yii\base\Component;
use yii\base\Model;
use Yii;

/**
 * Class TemplateManager manages saving templates, widget areas and widgets.
 *
 * @package im\cms\components
 */
class TemplateManager extends Component
{
    /**
     * Populates template and related models with the data, validates and saves
     * @param Template $template
     * @param array $data
     * @return bool
     */
    public function saveTemplate(Template $template, $data)
    {
        if ($template->load($data)) {
            $widgetAreas = $this->loadWidgetAreas($data);
            $widgets = $this->loadWidgets($data, 'Widgets');
            $valid = true;
            $valid = $template->validate() && $valid;
            $first = reset($widgetAreas);
            $valid = Model::validateMultiple($widgetAreas, array_diff($first->activeAttributes(), ['template_id'])) && $valid;
            $valid = Model::validateMultiple($widgets) && $valid;
            if ($valid && $template->save(false)) {
                if ($widgetAreas) {
                    foreach ($widgetAreas as $area) {
                        $area->template_id = $template->id;
                        if ($widgets) {
                            $this->setWidgets($area, $widgets);
                        }
                        $area->save();
                    }
                }
                return true;
            } else {
                if ($widgetAreas) {
                    $template->populateRelation('widgetAreas', $widgetAreas);
                }
                if ($widgets) {
                    $this->linkWidgets($widgetAreas, $widgets);
                }
            }
        }
        return false;
    }

    /**
     * Loads widget areas from data array.
     *
     * @param array $data
     * @param string $formName
     * @return WidgetArea[]
     */
    public function loadWidgetAreas($data, $formName = null)
    {
        $widgetAreas = $this->getWidgetAreas($data, $formName);
        $loaded = [];
        if (Model::loadMultiple($widgetAreas, $data, $formName)) {
            $loaded = $widgetAreas;
        }

        return $loaded;
    }

    /**
     * Loads widgets from data array.
     *
     * @param array $data
     * @param string $formName
     * @return Widget[]
     */
    public function loadWidgets($data, $formName)
    {
        $widgets = $this->getWidgets($data, $formName);
        $loaded = [];
        if (!empty($data[$formName])) {
            /** @var LayoutManager $layoutManager */
            $layoutManager = \Yii::$app->get('layoutManager');
            foreach ($data[$formName] as $area => $areaWidgets) {
                foreach ($areaWidgets as $key => $widgetData) {
                    $widget = null;
                    if (!empty($widgetData['id']) && isset($widgets[$widgetData['id']])) {
                        $widget = $widgets[$widgetData['id']];
                    } elseif (isset($widgets[$area][$key])) {
                        $widget = $layoutManager->getWidgetInstance($widgetData['type']);
                    }
                    if ($widget && $widget->load($widgetData, '')) {
                        $widget->widgetArea = $area;
                        $widget->sort = $widgetData['sort'];
                        $loaded[] = $widget;
                    }
                }
            }
        }

        return $loaded;
    }

    /**
     * Returns default template for layout.
     *
     * @param string $layout
     * @return Template
     */
    public function getDefaultTemplate($layout)
    {
        return Template::find()->where(['layout_id' => $layout, 'default' => 1])->one();
    }

    /**
     * Link widgets with widget areas.
     *
     * @param WidgetArea[] $areas
     * @param Widget[] $widgets
     */
    protected function linkWidgets($areas, $widgets)
    {
        $widgetsByArea = [];
        foreach ($widgets as $widget) {
            $widgetsByArea[$widget->widgetArea][$widget->sort] = $widget;
        }
        foreach ($areas as $area) {
            if (!empty($widgetsByArea[$area->code])) {
                $area->populateRelation('widgets', $widgetsByArea[$area->code]);
            }
        }
    }

    /**
     * Sets widgets to widget areas.
     *
     * @param WidgetArea $area
     * @param Widget[] $widgets
     */
    protected function setWidgets($area, $widgets)
    {
        $widgetsByArea = [];
        foreach ($widgets as $widget) {
            $widgetsByArea[$widget->widgetArea][$widget->sort] = [$widget, 'sort' => $widget->sort];
        }
        if (!empty($widgetsByArea[$area->code])) {
            $area->widgets = $widgetsByArea[$area->code];
        }
    }

    /**
     * Gets widgets areas from data array.
     *
     * @param array $data
     * @param string $formName
     * @return WidgetArea[]
     */
    protected function getWidgetAreas($data, $formName = null)
    {
        if ($formName === null) {
            $area = new WidgetArea();
            $formName = $area->formName();
        }
        $areas = [];
        if (!empty($data[$formName])) {
            $pks = [];
            foreach ($data[$formName] as $key => $areaData) {
                if (is_string($key)) {
                    $areas[$key] = new WidgetArea();
                } else {
                    $pks[] = $key;
                }
            }
            if ($pks) {
                $areas = $areas + WidgetArea::find()->where(['id' => $pks])->indexBy('id')->all();
            }
        }

        return $areas;
    }

    /**
     * Gets widgets from data array.
     *
     * @param array $data
     * @param string $formName
     * @return Widget[]
     */
    protected function getWidgets($data, $formName)
    {
        $widgets = [];
        if (!empty($data[$formName])) {
            /** @var LayoutManager $layoutManager */
            $layoutManager = Yii::$app->get('layoutManager');
            $pks = [];
            foreach ($data[$formName] as $area => $areaWidgets) {
                foreach ($areaWidgets as $sort => $widgetData) {
                    if (!empty($widgetData['id'])) {
                        $pks[] = $widgetData['id'];
                    } elseif (!empty($widgetData['type'])) {
                        $widgets[$area][$sort] = $layoutManager->getWidgetInstance($widgetData['type']);
                    }
                }
            }
            if ($pks) {
                $widgets = $widgets + Widget::find()->where(['id' => $pks])->indexBy('id')->all();
            }
        }
        return $widgets;
    }
}