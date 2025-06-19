# StackReporter™
A Product from Gray Loon

## Prerequisites 

You must have an account in [StackReporter](https://stackreporter.io) and have a registered domain tied to your account. 

You will receive an **API Site Key** that you will need to place in your `.env` file:

```dotenv
STACKREPORTER_API_SITE_KEY="YOUR_KEY_HERE"
```

## Installation

Install the StackReporter package at the root level of your Laravel application:

```console
composer require grayloon/stackreporter-laravel
```

That's it, you're done! 

## Syncing

Based on your subscription tier, StackReporter will send a POST request to your website at a set interval to fetch any package updates.
