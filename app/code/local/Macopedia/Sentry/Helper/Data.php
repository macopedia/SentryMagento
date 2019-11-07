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
            $ignoreList = explode("\r\n", Mage::getStoreConfig('macopedia_sentry/patch/ignore_strings'));
            if (is_array($ignoreList) && count($ignoreList) > 0) {
                if (is_array($message)
                    && isset($message['exception'])
                    && is_array($message['exception'])
                    && isset($message['exception']['values'])
                    && is_array($message['exception']['values'])
                ) {


                    foreach ($message['exception']['values'] as $key => $messageData) {
                        if (isset($messageData['stacktrace'])) {
                            unset($messageData['stacktrace']);
                        }
                        if (!$this->ignoreInString(implode(' ', $messageData), $ignoreList)) {
                            unset($message['exception']['values'][$key]);
                        }
                    }
                    if (count($message['exception']['values']) == 0) {
                        return false;
                    }

                } elseif (isset($message['message'])) {
                    if (!$this->ignoreInString($message['message'], $ignoreList)) {
                        return false;
                    }
                }
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
