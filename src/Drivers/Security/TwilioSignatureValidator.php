<?php

namespace KattSoftware\Telephonify\Drivers\Security;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class TwilioSignatureValidator
{
    public function isSignatureValid($signature, $url, $authToken, array $parameters)
    {
        $expectedSignature = $this->computeSignature($url, $parameters, $authToken);

        return $signature === $expectedSignature;
    }

    /**
     * @param string $url
     * @param string[] $parameters
     * @param string $authToken
     * @return string
     */
    private function computeSignature($url, array $parameters, $authToken)
    {
        $data = $url;
        ksort($parameters);

        foreach ($parameters as $key => $value) {
            $data .= $key . $value;
        }

        return base64_encode(hash_hmac('sha1', $data, $authToken, true));
    }

}
