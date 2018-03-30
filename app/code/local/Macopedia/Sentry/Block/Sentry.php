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
}
