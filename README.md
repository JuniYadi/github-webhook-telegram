# GitHub WebHook to Telegram Notification

## How to Install
1. You need create a new bot telegram, and get API Keys
2. Chat a new bot or Invite a new bot to group
3. Get Chat ID From `https://api.telegram.org/bot<bot_api_keys>/getUpdates`
4. Open file `hook.php` then edit :
   1. `TELEGRAM_TOKEN` with your telegram api keys
   2. `TELEGRAM_MESSAGE_ID` with Chat ID
5. Set URL Webhook to You're GitHub
6. Enjoy

### Improved Sources From :
* https://github.com/wobondar/github-to-telegram