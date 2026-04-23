# Open Google Bot Plugin for Craft CMS

[![MIT License](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Craft CMS](https://img.shields.io/badge/Craft%20CMS-5.x-green)](https://craftcms.com/)

This plugin provides a robust method to identify requests originating from Googlebot, allowing you to serve protected or premium content exclusively to search engine crawlers.

## Description

The Open Google Bot plugin helps protect your valuable content by verifying the authenticity of incoming requests. It leverages Google's recommended method: a double DNS lookup to confirm that the IP address making the request actually belongs to Googlebot. This prevents simple user-agent spoofing and ensures that only legitimate Google crawlers can access specific sections of your site.

## Features

- **Googlebot Verification:** Accurately identifies Googlebot requests using a double DNS lookup.
- **Craft CMS Integration:** Seamlessly integrates with your Craft CMS project via Twig variables.
- **Caching:** Implements caching for IP lookups to improve performance.

## Installation

1. **Install via Composer:**
   ```bash
   composer require kritical-it/open-google-bot
   ```
1. **Follow Craft CMS Plugin Installation:**
   After installing via Composer, Craft CMS should automatically detect the plugin. You may need to run the `php craft install/plugin open-google-bot` command or visit the Plugins page in your Craft CMS control panel to complete the installation.

## Usage

Once installed, the plugin exposes a Twig variable `openGoogleBot` that allows you to check if the current request is from Googlebot.

You can use it in your Twig templates like this:

```twig
{% set protectedContent = 'This is premium content only for Googlebot.' %}

{% if craft.openGoogleBot.get().isGoogleBot() %}
    {{ protectedContent }}
{% else %}
    {# Optional: Content for non-Googlebot users, e.g., a message asking them to log in or subscribe #}
    <p>Please log in or subscribe to view this content.</p>
{% endif %}
```

The `KriticalIT\OpenGoogleBot\services\GoogleBotValidatorService` class provides the `isGoogleBot(?string $ip = null): bool` method.

## Important Note on Cloaking and Structured Data

When using this plugin to serve different content to Googlebot than to human users, you **must** ensure that you are not violating Google's guidelines against cloaking. While this plugin helps verify Googlebot's identity, it does not manage the content itself.

**Crucially, ensure that any structured data (like Schema.org markup) you provide on your pages is identical for both Googlebot and human users.** Serving different content to Googlebot while providing structured data that reflects that different content can lead to Google penalties for cloaking.

Always refer to Google's Webmaster Guidelines for the most up-to-date information on what is considered acceptable.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## Support

For issues, feature requests, or contributions, please visit the [GitHub repository](https://github.com/kritical-it/open-google-bot).
