<?php

namespace im\cms\models;

use im\cms\components\layout\Layout;
use im\cms\Module;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%templates}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $layout_id
 * @property Layout $layout
 * @property WidgetArea[] $widgetAreas
 */
class Template extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%templates}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['layout_id'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('template', 'ID'),
            'name' => Module::t('template', 'Name'),
            'layout_id' => Module::t('template', 'Layout'),
        ];
    }

    /**
     * @return Layout
     */
    public function getLayout()
    {
        return Yii::$app->layoutManager->getLayout($this->layout_id);
    }

    /**
     * @param Layout $layout
     */
    public function setLayout(Layout $layout)
    {
        $this->layout_id = $layout->id;
    }

    /**
     * Returns array of available layouts (except default)
     * @return array
     */
    public function getLayoutsList()
    {
        $layouts = ArrayHelper::map(Yii::$app->layoutManager->getLayouts(), 'id', 'name');
        $defaultLayout = Yii::$app->layoutManager->getDefaultLayout();
        unset($layouts[$defaultLayout->id]);
        return $layouts;
    }

    /**
     * Returns default layout
     * @return Layout
     */
    public function getDefaultLayout()
    {
        return Yii::$app->layoutManager->getDefaultLayout();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidgetAreas()
    {
        return $this->hasMany(WidgetArea::className(), ['template_id' => 'id']);
    }
}
