<?php
/**
 * Created by PhpStorm.
 * User: jakubidziak
 * Date: 30.03.2018
 * Time: 10:31
 */

class Macopedia_Sentry_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Check if message needs to be ignored
     *
     * @param string $message
     * @return bool
     */
    public function reportMessage($message)
    {
        try {
            $message = substr(str_replace("\n",'',$message),0,255);
            $ignoreList = explode("\r\n", Mage::getStoreConfig('macopedia_sentry/patch/ignore_strings'));
            if (is_array($ignoreList) && count($ignoreList) > 0) {
                $result = $this->ignoreInString($message, $ignoreList);
                return $result;
            }
        } catch (Exception $e) {
            return true;
        }
        return true; // default - ignore none
    }

    /**
     * Check if any of the strings are in the message
     *
     * @param string $message
     * @param array $ignores
     * @return bool
     */
    private function ignoreInString($message, $ignores)
    {
        $quotedIgnoreArray = array_map(
            function ($ignore) {
                return preg_quote($ignore, '/');
            }, array_values($ignores)
        );
        $quotedIgnoreArray = array_filter($quotedIgnoreArray);
        $regexp = '/' . implode('|', $quotedIgnoreArray) . '/i';
        return !(bool)preg_match($regexp, $message);
    }

}
