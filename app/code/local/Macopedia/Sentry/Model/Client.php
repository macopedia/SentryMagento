<?php


class Macopedia_Sentry_Model_Client extends Raven_Client
{

    public function capture($data, $stack = null, $vars = null)
    {
        if (Mage::helper('macopedia_sentry')->reportMessage($data)) {
            return parent::capture($data, $stack, $vars);
        }
        return false;
    }

}
