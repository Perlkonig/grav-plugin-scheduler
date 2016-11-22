# Scheduler Plugin

The **Scheduler** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It's a simple content scheduler that hides/shows content in a page based on the date.

[A demo is available.](https://perlkonig.com/demos/scheduler)

## Installation

Installing the Scheduler plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install scheduler

This will install the Scheduler plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/scheduler`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `scheduler`. You can find these files on [GitHub](https://github.com/aaron-dalton/grav-plugin-scheduler) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/scheduler
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) plugins to operate.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/scheduler/scheduler.yaml` to `user/config/plugins/scheduler.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
active: false
```

* `enabled` turns the plugin off and on. If set to `false`, then no codes will processed at all.

* `active` lets you run the plugin on a page-by-page basis. Usually you set it to `false` in the main config, and then in pages with time-sensitive content, you put the following in the header:

  ```yaml
    scheduler:
      active: true
  ```

## Usage

This is a simple and naive content scheduler. It assumes that caching is disabled. While this plugin uses what looks like shortcodes, it does *not* extend the [Shortcode Core](https://github.com/getgrav/grav-plugin-shortcode-core) infrastructure. For maximum speed, it processes the page content *before* the Markdown and shortcode parsers see it.

Nesting is not currently supported.

The plugin looks for content surrounded by the following codes:

```
[scheduler ...]
Time-sensitive content
[/schedule]
```

The shortcode accepts two options:

* `notbefore` is the date before which the contained content should *not* be displayed.

* `notafter` is the date after which the contained content should *not* be displayed.

The dates are parsed by PHP's `strtotime` ([docs](https://secure.php.net/manual/en/function.strtotime.php)), so the dates can be in any format that function understands.

Here are a few sample codes:

  * `[scheduler notbefore="2016-01-01"]` (date without a time stamp; midnight assumed)
  * `[scheduler notafter="2016-01-01T09:00:00"]` (ISO format)
  * `[scheduler notbefore="2016-01-01 09:00:00"]` (almost ISO format)
  * `[scheduler notafter="1 December 2016"]` (text date)

[A live demo is available on my website.](https://perlkonig.com/demos/scheduler)

**NOTE:** This plugin uses regular expressions and is relatively fragile. The matching is case insensitive, but extraneous spaces at the beginning and end of tags will cause problems.

## Credits

This plugin was developed as a proof of concept for [issue #1176](https://github.com/getgrav/grav/issues/1176).

## Todo

* [ ] Add nesting support
