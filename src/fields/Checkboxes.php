<?php

namespace lewisjenkins\craftdynamicfields\fields;

use lewisjenkins\craftdynamicfields\CraftDynamicFields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

class Checkboxes extends Field
{
     
    public $checkboxOptions = '';

    public static function displayName(): string
    {
        return Craft::t('craft-dynamic-fields', 'Checkboxes (dynamic)');
    }

    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'craft-dynamic-fields/_components/fields/Checkboxes_settings',
            [
                'field' => $this,
            ]
        );
    }

    public function getInputHtml($value, ElementInterface $element = null): string
    {

		$view = Craft::$app->getView();
		$templateMode = $view->getTemplateMode();
		$view->setTemplateMode($view::TEMPLATE_MODE_SITE);
	
		$variables['element'] = $element;
		$variables['this'] = $this;
		
		$options = json_decode('[' . $view->renderString($this->checkboxOptions, $variables) . ']', true);
		
		$view->setTemplateMode($templateMode);

		foreach ($options as $key => $option) :

		    if ($this->isFresh($element) ) :
		    	if (!empty($option['default'])) :
		    		$value[] = $option['value'];
				endif;
			endif;
			
			// unset($options[$key]['checked']);

		endforeach; 

        return Craft::$app->getView()->renderTemplate('craft-dynamic-fields/_includes/forms/checkboxGroup', [
            'name' => $this->handle,
            'values' => $value,
            'options' => $options,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if (is_string($value)) {
            $value = Json::decodeIfJson($value);
        } else if ($value === null && $this->isFresh($element)) {
            $value = [];
        }

        return (array)$value;
    }
}
