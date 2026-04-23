<?php

namespace KriticalIT\OpenGoogleBot\services;

use Craft;
use craft\base\Component;

/**
 * GoogleBotValidator Service
 * Verifies if a request is genuinely from Googlebot using a double-DNS lookup.
 * This is the only spoof-proof method recommended by Google.
 *
 * @author GonGarce
 */
class GoogleBotValidatorService extends Component
{
    private const CACHE_PREFIX = 'opengooglebot_';

    private const VERIFIED = '1';

    private const FAILED = '0';

    /**
     * Main validation logic
     *
     * @param  string|null  $ip  Optional IP to check (defaults to requester)
     */
    public function isGoogleBot(?string $ip = null): bool
    {
        $ip = $ip ?? Craft::$app->getRequest()->getUserIP();

        // 1. Initial User-Agent check (Prevents DNS lookups for normal users)
        $userAgent = Craft::$app->getRequest()->getUserAgent();
        if (empty($userAgent) || stripos($userAgent, 'Googlebot') === false) {
            return false;
        }

        // 2. Cache check (Using 0/1 to avoid boolean false issues)
        $cacheKey = self::CACHE_PREFIX.$ip;
        $cachedValue = Craft::$app->getCache()->get($cacheKey);

        if ($cachedValue !== false) {
            return $cachedValue === self::VERIFIED;
        }

        // 3. REVERSE DNS: Get the hostname associated with the requester's IP
        $hostname = gethostbyaddr($ip);

        // 4. VERIFY HOSTNAME: It must end in .googlebot.com
        $isGoogleDomain = preg_match('/\.googlebot\.com$/i', $hostname);

        if (! $isGoogleDomain) {
            $this->_setCache($cacheKey, self::FAILED);

            return false;
        }

        // 5. FORWARD DNS: Get the IP associated with that hostname
        // This confirms the IP isn't just spoofing a PTR record
        $verifiedIp = gethostbyname($hostname);

        if ($verifiedIp === $ip) {
            $this->_setCache($cacheKey, self::VERIFIED);

            return true;
        }

        $this->_setCache($cacheKey, self::FAILED);

        return false;
    }

    /**
     * Persistent cache for 24h
     */
    private function _setCache(string $key, string $value): void
    {
        Craft::$app->getCache()->set($key, $value, 86400);
    }
}
