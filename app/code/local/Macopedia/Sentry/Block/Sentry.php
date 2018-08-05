<?php
/**
 * Created by PhpStorm.
 * User: jakubidziak
 * Date: 30.03.2018
 * Time: 10:21
 */

class Macopedia_Sentry_Block_Sentry extends Mage_Core_Block_Template
{
    protected $_config;

    protected function _toHtml()
    {
        if (!Mage::getStoreConfigFlag('macopedia_sentry/general/enable')) {
            return '';
        }
        $this->_config = Mage::getConfig()->loadModulesConfiguration('system.xml');
        return parent::_toHtml();
    }

    public function getAppUrl()
    {
        return Mage::getStoreConfig('macopedia_sentry/general/app_url');
    }

    public function getRavenUrl()
    {
        return Mage::getStoreConfig('macopedia_sentry/general/raven_js_url');
    }

    /**
     * Dynamic build of all raven.js options as defined in system.xml, under options group
     *
     * @return mixed
     */
    public function getOptions() {
        $node = $this->_config->getNode('sections/macopedia_sentry/groups/options/fields')->asArray();
        $allOptions = array_keys($node);
        foreach($allOptions as $option) {
            if($optionValue = $this->getOptionValue($option)) {
                $options[$option] = $optionValue;
            }
        }
        return str_replace('\\\\','\\',json_encode($options, JSON_UNESCAPED_SLASHES));
    }

    /**
     * Get the option value.
     * Result in array of items or string, depending on admin config value frontend_type
     *
     * @param $option
     * @return array|bool|string
     */
    public function getOptionValue($option)
    {
        if(!empty($rawData = trim(Mage::getStoreConfig('macopedia_sentry/options/'.$option)))) {
            try {
                $node = $this->_config->getNode('sections/macopedia_sentry/groups/options/fields/' . $option)->asArray();
                if ($node['frontend_type'] == 'textarea') {
                    $splitData = explode(PHP_EOL, $rawData);
                    return array_map('trim', $splitData);
                } else {
                    return $rawData;
                }
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        return false;
    }

}
