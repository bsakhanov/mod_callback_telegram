# «Telegram Callback» Joomla module

**v1.1.0**

**Joomla 3.6 or later**

A feedback module sending the message in the telegram, the modern service of instant messages.

A message from the form module goes into pre-created bot.

The bot is created with another system of the bot.

### Description

The default field list is empty, because the need to fill it, then save. Supported field types: text, email, url, tel, password, textarea, select, checkbox, radio. Each field must have a name (for form creation) and name (the identification field in the message in the telegram).

You can also specify a title for the message telegram, if the header is not specified, the default string of the form "Message from the website {url}".

The module supports overriding the templates of the messages sent to telegram. Templates are located in the /layouts folder.

The module does not contain inline CSS and JS, but razmetka form template predstavlena in three versions: bootstrap2 (default), bootstrap3/4, uikit2, uikit3.

Message about successful sending of post or the error is displayed via the standard mechanism of system messages Joomla.

**ATTENTION!** Must be off `open_basedir` because it does not work used in the module curl.

---

### How to create a bot

- we find in the telegram bot named @BotFather (or follow the link http://t.me/botfather);
- enter the bot the command `/newbot` (or select it from the list of commands in a welcome message);
- consistently enter first name of the bot (any one will come up), then the system nickname, consisting of only Latin characters, digits and "_"symbol;
voila! we say that the bot created and offer a quick link to it as well &ndash; caution! - a security token, a kind of universal key to the created bot;
- open the newly created telegram bot and enter the command `/start`, thereby running the bot (this is important!).

In the options installed on site module "Telegram callback" input received when creating a bot token, then click the button "Get ID" chat &ndash; the appropriate box should be filled. If newly created bot was not running the command `/start`, nothing happens. ID chat cannot be obtained if the bot received reports from other sources, in addition to chat in telegram.
