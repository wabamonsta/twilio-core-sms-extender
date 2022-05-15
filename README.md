# Twilio Core SMS - Extender

Simple SMS sender using Twilio Core functionality.

## Plugin Dependencies

- PHP
- WordPress
- Link Twilio Core plugin: https://wordpress.org/plugins/wp-twilio-core/

## USAGE

```php
[tcs_sms
buttonvalue='Button message to display'
redirect='url of page to redirect to once message is sent'
message='Message to be sent along with URL youd like the receiver to selet'
]
```

## Shortcode Parameters

| Parameter     | Action                                                    |
| ------------- | :-------------------------------------------------------- |
| `buttonvalue` | allow user to set the button label that will be displayed |
| `redirect`    | Set redirect URL once message is sent                     |
| `message`     | message to send to the reciever                           |
