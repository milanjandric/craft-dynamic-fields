<?php

namespace lewisjenkins\craftdynamicfields\fields;

use lewisjenkins\craftdynamicfields\CraftDynamicFields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

class Radiobuttons extends Field
{
     
    public $radioOptions = '';

    public static function displayName(): string
    {
        return Craft::t('craft-dynamic-fields', 'Radio Buttons (dynamic)');
    }

    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'craft-dynamic-fields/_components/fields/Radiobuttons_settings',
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
		
		$options = json_decode('[' . $view->renderString($this->radioOptions, $variables) . ']', true);
		$view->setTemplateMode($templateMode);
		
		foreach ($options as $key => $option) :

		    if ($this->isFresh($element) ) :
		    	if (!empty($option['default'])) :
		    		$value = $option['value'];
				endif;
			endif;
			
			// unset($options[$key]['checked']);

		endforeach; 
	
        return Craft::$app->getView()->renderTemplate('craft-dynamic-fields/_includes/forms/radioGroup', [
            'name' => $this->handle,
            'value' => $value,
            'options' => $options,
        ]);
    }
}
