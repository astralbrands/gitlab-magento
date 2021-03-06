<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   RocketWeb
 * @package    RocketWeb_ShoppingFeeds
 * @copyright  Copyright (c) 2016 RocketWeb (http://rocketweb.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     RocketWeb
 */


/**
 * Adminhtml system config attributes array field renderer
 *
 * @category   RocketWeb
 * @package    RocketWeb_ShoppingFeeds
 */
class RocketWeb_ShoppingFeeds_Block_Adminhtml_System_Config_Form_Field_Mapproductcolumns
    extends RocketWeb_ShoppingFeeds_Block_Adminhtml_System_Config_Form_Field_Mapcolumns
{
    private $_attribute_codes = array();
    protected $_addDelete = true;

    public function __construct($attributes=array())
    {
        $feed = Mage::registry('rocketshoppingfeeds_feed');
        $providerData = $feed->hasProviderData() ? $feed->getProviderData() : null;
        $orderDisable = false;
        if (!is_null($providerData) && is_array($providerData) && isset($providerData['disable_columns_sort_order'])) {
            $orderDisable = true;
            $this->_addDelete = false;
        }

        $this->addColumn('order', array(
            'label' => Mage::helper('adminhtml')->__('Order'),
            'style' => 'width:30px',
            'class' => 'validate-greater-than-zero grid-order' . ($orderDisable ? '" readonly="readonly' : '')
        ));

        $this->addColumn('column', array(
            'label' => Mage::helper('adminhtml')->__('Feed Column'),
            'style' => 'width:200px',
        ));

        $this->addColumn('attribute', array(
            'label' => Mage::helper('adminhtml')->__('Map To'),
            'style' => 'width:300px',
        ));

        $this->addColumn('param', array(
            'label' => Mage::helper('adminhtml')->__('Options'),
            'style' => 'min-width:320px',
            'class' => 'input-text'
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Column');

        parent::__construct();
        if (!is_null($providerData) && is_array($providerData) && isset($providerData['hide_columns_add_button'])) {
            $this->_addButtonLabel = null;
        }
        $this->setTemplate('rocketshoppingfeeds/system/config/form/field/array.phtml');


        // Set parameters to be replaced in the frontend row template

        $this->setData($attributes);

        $this->setGroupName('columns');
        $this->setFieldName('columns_map_product_columns');

        if(!$this->getName() && $this->getFieldName()) {
            $this->setName($this->getFieldName());
        }
        if(!$this->getElement()) {
            $this->setElement($this);
        }
    }

    /**
     * Crates array of select values: attributes and directives for dropdowns.
     * [code] => [label]
     *
     * @return array
     */
    protected function _getProductAttributesCodes()
    {
        if (empty($this->_attribute_codes)) {

            $feed = Mage::registry('rocketshoppingfeeds_feed');
            $exclude_attributes = $feed ? $feed->getData('exclude_attributes') : array();

            $config = Mage::getModel('eav/config');
            $attributes_codes = $config->getEntityAttributeCodes('catalog_product', new Varien_Object(array('store_id' => $feed->getStoreId())));

            foreach ($attributes_codes as $attribute_code) {
                if (array_search($attribute_code, $exclude_attributes) !== false) {
                    continue;
                }
                $attribute = $config->getAttribute('catalog_product', $attribute_code);
                if ($attribute !== false && $attribute->getAttributeId() > 0) {
                    $this->_attribute_codes[$attribute->getAttributeCode()] = addslashes($attribute->getFrontend()->getLabel() . ' (' . $attribute->getAttributeCode() . ')');
                }
            }
            asort($this->_attribute_codes);

            // add directives
            $directives = $feed->getData('directives');
            foreach ($directives as $code => $arr) {
                $directives[$code] = $arr['label'];
            }

            $this->_attribute_codes = array_merge($directives, $this->_attribute_codes);
            foreach ($this->_attribute_codes as $key => $value) {
                $this->_attribute_codes[$key] = addslashes($value);
            }
        }

        return $this->_attribute_codes;
    }

    /**
     * Render array cell for prototypeJS template
     *
     * @param string $columnName
     * @return string
     */
    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column = $this->_columns[$columnName];
        $inputName = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

        if ($column['renderer']) {
            return $column['renderer']->setInputName($inputName)->setColumnName($columnName)->setColumn($column)
                ->toHtml();
        }

        $html = '';

        switch ($columnName) {
            case 'column':
                $html .= $this->_renderColumnCell($columnName);
                break;
            case 'order':
                $html .= $this->_renderTextCell($columnName);
                break;
            case 'attribute':
                $html .= '<select name="' . $inputName . '" ' . (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . '>';
                $html .= '<optgroup label="--------------- ' . $this->__('Directives') . ' ---------------"></optgroup>';
                $optg = false;
                foreach ($this->_getProductAttributesCodes() as $value => $label) {
                    if (!$optg && strpos($label, "(") !== false) {
                        $html .= '<optgroup label="--------------- ' . $this->__('Attributes') . ' ---------------"></optgroup>';
                        $optg = true;
                    }
                    $html .= '<option label="' . $label . '" value="' . $value . '">' . $label . '</option>';
                }
                $html .= '</select>';
                break;
        }

        return $html;
    }

    /**
     * This method gets overwritten in Block_Adminhtml_System_Config_Form_Field_Mapemptycolumns
     * @param $columnName
     * @return string
     */
    protected function _renderColumnCell($columnName)
    {
        return $this->_renderTextCell($columnName);
    }

    /**
     * @param $columnName
     * @return string
     */
    protected function _renderTextCell($columnName)
    {
        $column = $this->_columns[$columnName];
        $inputName = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

        return '<input type="text" name="' . $inputName . '" value="#{' . $columnName . '}" ' .
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
            (isset($column['class']) ? $column['class'] : 'input-text') . '"' .
            (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . '/>';
    }

    /**
     * @return string
     */
    public function configToJson()
    {
        $feed = Mage::registry('rocketshoppingfeeds_feed');

        $source_models = array();
        $directives = $feed->getData('directives');
        foreach ($directives as $directive => $obj) {
            if (array_key_exists('source_model', $obj)) {
                $model = Mage::getModel($obj['source_model']);
                if (array_key_exists('param', $obj)) {
                    $model->setParam($obj['param']);
                }
                $source_models[$directive] = $model->toHtml();
            }
        }

        $configs = $feed->getConfig($this->getFieldName(), false);
        if ($configs != null && is_array($configs) && count($configs) > 0) {
            foreach ($configs as $config) {
                $attribute = isset($config['attribute']) ? $config['attribute'] : null;
                if (isset($directives[$attribute]) && isset($directives[$attribute]['source_model']) && $directives[$attribute]['source_model'] != 'rocketshoppingfeeds/source_directive_helper') {
                    // We have a value inside the DB, so need for default value to show!
                    unset($directives[$attribute]['param']);
                }
            }
        }

        $ret = array('attributes' => $this->getMapColumns(),
            'directives' => $directives,
            'source' => $source_models);

        return Zend_Json::encode($ret);
    }

    /**
     * @param $column
     * @return bool
     */
    public function allowFillParams($column)
    {
        return $column == 'attribute';
    }

    /**
     * Overwritten to remove label cell if label is empty
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $id = $element->getHtmlId();
        $label = trim($element->getLabel());
        $html = '<tr id="row_' . $id . '">';
        if (!empty($label)) {
            $html .= '<td class="label"><label for="' . $id . '">' . $element->getLabel() . '</label></td>';
        }

        //$isDefault = !$this->getRequest()->getParam('website') && !$this->getRequest()->getParam('store');
        $isMultiple = $element->getExtType() === 'multiple';

        // replace [value] with [inherit]
        $namePrefix = preg_replace('#\[value\](\[\])?$#', '', $element->getName());

        $options = $element->getValues();

        $addInheritCheckbox = false;
        if ($element->getCanUseWebsiteValue()) {
            $addInheritCheckbox = true;
            $checkboxLabel = Mage::helper('adminhtml')->__('Use Website');
        } elseif ($element->getCanUseDefaultValue()) {
            $addInheritCheckbox = true;
            $checkboxLabel = Mage::helper('adminhtml')->__('Use Default');
        }

        if ($addInheritCheckbox) {
            $inherit = $element->getInherit() == 1 ? 'checked="checked"' : '';
            if ($inherit) {
                $element->setDisabled(true);
            }
        }

        $html .= empty($label) ? '<td colspan="2" class="value">' : '<td class="value">';
        $html .= $this->_getElementHtml($element);
        if ($element->getComment()) {
            $html .= '<p class="note"><span>' . $element->getComment() . '</span></p>';
        }
        $html .= '</td>';

        if ($addInheritCheckbox) {

            $defText = $element->getDefaultValue();
            if ($options) {
                $defTextArr = array();
                foreach ($options as $k => $v) {
                    if ($isMultiple) {
                        if (is_array($v['value']) && in_array($k, $v['value'])) {
                            $defTextArr[] = $v['label'];
                        }
                    } elseif ($v['value'] == $defText) {
                        $defTextArr[] = $v['label'];
                        break;
                    }
                }
                $defText = join(', ', $defTextArr);
            }

            // default value
            $html .= '<td class="use-default">';
            $html .= '<input id="' . $id . '_inherit" name="' . $namePrefix . '[inherit]" type="checkbox" value="1" class="checkbox config-inherit" ' . $inherit . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" /> ';
            $html .= '<label for="' . $id . '_inherit" class="inherit" title="' . htmlspecialchars($defText) . '">' . $checkboxLabel . '</label>';
            $html .= '</td>';
        }

        $html .= '<td class="scope-label">';
        if ($element->getScope()) {
            $html .= $element->getScopeLabel();
        }
        $html .= '</td>';

        $html .= '<td class="">';
        if ($element->getHint()) {
            $html .= '<div class="hint" >';
            $html .= '<div style="display: none;">' . $element->getHint() . '</div>';
            $html .= '</div>';
        }
        $html .= '</td>';

        $html .= '</tr>';
        return $html;
    }

    /**
     * Fill in the field_name data that will be used in row template evaluation
     *
     * @param Varien_Object
     */
    protected function _prepareArrayRow(Varien_Object $row)
    {
       $row->addData(array('field_name' => $this->getFieldName(),
                           'group_name' => $this->getGroupName()));
    }
}