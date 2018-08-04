<?php
/**
 * Created by PhpStorm.
 * User: jakubidziak
 * Date: 30.03.2018
 * Time: 10:21
 */

class Macopedia_Sentry_Block_Sentry extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
        if (!Mage::getStoreConfigFlag('macopedia_sentry/general/enable')) {
            return '';
        }
        return parent::_toHtml();
    }

    public function getAppUrl()
    {
        return Mage::getStoreConfig('macopedia_sentry/general/app_url');
    }

    public function getEnvType()
    {
        return Mage::getStoreConfig('macopedia_sentry/general/env_type');
    }

    public function getRavenUrl()
    {
        return Mage::getStoreConfig('macopedia_sentry/general/raven_js_url');
    }

    public function getOptions() {
        $options = ['environment' => $this->getEnvType() ];
        if(!empty($whiteListRaw = Mage::getStoreConfig('macopedia_sentry/general/whitelist_urls'))) {
            $whiteLists = explode(PHP_EOL, $whiteListRaw);
            $options['whitelistUrls'] = array_map('trim', $whiteLists);
        }
        if(!empty($ignoreListRaw = Mage::getStoreConfig('macopedia_sentry/general/ignore_textlist'))) {
            $ignoreLists = explode(PHP_EOL, $ignoreListRaw);
            $options['ignoreErrors'] = array_map('trim', $ignoreLists);
        }
        return json_encode($options, JSON_UNESCAPED_SLASHES);
    }
}
