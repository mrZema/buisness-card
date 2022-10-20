<?php

namespace common\widgets;

use Closure;
use Exception;
use yii\base\Model;
use yii\helpers\Html;
use kartik\base\Config;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use kartik\detail\DetailView as KartikDetailView;

class DetailView extends KartikDetailView
{
    /**
     * @var array|object the data model whose details are used to render and validate form in edit mode.
     * This can be a [[Model]] instance, an associative array, an object that implements [[Arrayable]] interface
     * or simply an object with defined public accessible non-static properties.
     *
     * Use this property if you do not have validation rules in main model. For instance when you have some forms
     * with its own validation rules for one main model.
     *
     * You can also specify property 'editModel' to use other model for particular attribute.
     */
    public $form = null;

    /**
     * Renders each form attribute
     *
     * @param array $config the attribute config
     *
     * @return mixed
     * @throws InvalidConfigException
     * @throws Exception
     */
    protected function renderFormAttribute($config)
    {
        if (empty($config['attribute'])) {
            return '';
        }
        $model = ArrayHelper::getValue($config, 'editModel', $this->form);
        if (!$model instanceof Model) {
            $model = $this->form ?? $this->model;
        }
        if (isset($config['updateMarkup'])) {
            $markup = $config['updateMarkup'];
            return $markup instanceof Closure ? $markup($this->_form, $this) : $markup;
        }
        $attr = ArrayHelper::getValue($config, 'updateAttr', $config['attribute']);
        $input = ArrayHelper::getValue($config, 'type', self::INPUT_TEXT);
        $fieldConfig = ArrayHelper::getValue($config, 'fieldConfig', []);
        $inputWidth = ArrayHelper::getValue($config, 'inputWidth', '');
        $container = ArrayHelper::getValue($config, 'inputContainer', []);
        if ($inputWidth != '') {
            Html::addCssStyle($container, "width: {$inputWidth}"); // deprecated since v1.7.7
        }
        $template = ArrayHelper::getValue($fieldConfig, 'template', "{input}\n{error}\n{hint}");
        $row = Html::tag('div', $template, $container);
        if (static::hasGridCol($container)) {
            $row = '<div class="row">' . $row . '</div>';
        }
        $fieldConfig['template'] = $row;
        if (substr($input, 0, 8) == "\\kartik\\") {
            Config::validateInputWidget($input, 'as an input widget for DetailView edit mode');
        } elseif ($input !== self::INPUT_WIDGET && !in_array($input, self::$_inputsList)) {
            throw new InvalidConfigException(
                "Invalid input type '{$input}' defined for the attribute '" . $config['attribute'] . "'."
            );
        }
        $options = ArrayHelper::getValue($config, 'options', []);
        $widgetOptions = ArrayHelper::getValue($config, 'widgetOptions', []);
        $class = ArrayHelper::remove($widgetOptions, 'class', '');
        if (!empty($config['options'])) {
            $widgetOptions['options'] = $config['options'];
        }
        if (Config::isInputWidget($input)) {
            $class = $input;
            return $this->_form->field($model, $attr, $fieldConfig)->widget($class, $widgetOptions);
        }
        if ($input === self::INPUT_WIDGET) {
            if ($class == '') {
                throw new InvalidConfigException("Widget class not defined in 'widgetOptions' for {$input}'.");
            }
            return $this->_form->field($model, $attr, $fieldConfig)->widget($class, $widgetOptions);
        }
        if (in_array($input, self::$_dropDownInputs)) {
            $items = ArrayHelper::getValue($config, 'items', []);
            return $this->_form->field($model, $attr, $fieldConfig)->$input($items, $options);
        }
        if ($input == self::INPUT_HTML5) {
            $inputType = ArrayHelper::getValue($config, 'inputType', self::INPUT_TEXT);
            return $this->_form->field($model, $attr, $fieldConfig)->$input($inputType, $options);
        }
        return $this->_form->field($model, $attr, $fieldConfig)->$input($options);
    }

    /**
     * Renders a single attribute item combination.
     *
     * @param array $attribute the specification of the attribute to be rendered.
     *
     * @return string the rendering result
     * @throws InvalidConfigException
     */
    protected function renderAttributeItem($attribute)
    {
        $labelColOpts = ArrayHelper::getValue($attribute, 'labelColOptions', $this->labelColOptions);
        $valueColOpts = ArrayHelper::getValue($attribute, 'valueColOptions', $this->valueColOptions);
        if (ArrayHelper::getValue($attribute, 'group', false)) {
            $groupOptions = ArrayHelper::getValue($attribute, 'groupOptions', []);
            $label = ArrayHelper::getValue($attribute, 'label', '');
            if (empty($groupOptions['colspan'])) {
                $groupOptions['colspan'] = 2;
            }
            return Html::tag('th', $label, $groupOptions);
        }
        if ($this->hideIfEmpty === true && empty($attribute['value'])) {
            Html::addCssClass($this->_rowOptions, 'kv-view-hidden');
        }
        if (ArrayHelper::getValue($attribute, 'type', 'text') === self::INPUT_HIDDEN) {
            Html::addCssClass($this->_rowOptions, 'kv-edit-hidden');
        }
        /**
         * issue #158 & enh #185
         */
        $value = $attribute['value'];

        if ($this->arrayValueToString && is_array($attribute['value'])) {
            $value = print_r($value, true);
        }

        if ($this->notSetIfEmpty && ($value === '' || $value === null)) {
            $value = null;
        }
        $dispAttr = $this->formatter->format($value, $attribute['format']);
        Html::addCssClass($this->viewAttributeContainer, 'kv-attribute');
        Html::addCssClass($this->editAttributeContainer, 'kv-form-attribute');
        $output = Html::tag('div', $dispAttr, $this->viewAttributeContainer) . "\n";
        if ($this->enableEditMode) {
            if (ArrayHelper::getValue($attribute, 'displayOnly', false)) {
                $editInput = $dispAttr;
            } else {
                $editInput = $this->renderFormAttribute($attribute);
                if ($hint = ArrayHelper::getValue($attribute, 'hint')) {
                    $editInput = $editInput . Html::tag('div', $hint, []);
                }
            }
            $output .= Html::tag('div', $editInput, $this->editAttributeContainer);
        }
        return Html::tag('th', $attribute['label'], $labelColOpts) . "\n" . Html::tag('td', $output, $valueColOpts);
    }
}
