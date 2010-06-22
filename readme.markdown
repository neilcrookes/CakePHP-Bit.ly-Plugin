CakePHP Bit.ly Plugin
===============================

A CakePHP plugin for interacting with the <a href="http://code.google.com/p/bitly-api/wiki/ApiDocumentation">Bit.ly API</a>.

Provides a simple, familiar API for things like listing a user's links, fetching click stats for one of your links and creating a bit.ly (or j.mp) link.

The plugin contains controllers and views but they are really only included to demonstrate the plugin working. The interesting stuff is in the models and datasource. How you should use it therefore is by accessing the model methods directly from classes in your own application, see below for examples.

Dependencies
------------

  - <a href="http://github.com/neilcrookes/CakePHP-ReST-DataSource-Plugin">CakePHP Rest DataSource Plugin</a>

Installation
------------

  - Get the <a href="http://github.com/neilcrookes/CakePHP-ReST-DataSource-Plugin">CakePHP Rest DataSource Plugin</a> and add to plugins/rest
  - Get this plugin and add it to plugins/bitly
  - Login to bit.ly and get your <a href="http://bit.ly/a/your_api_key">apiKey</a> or if you don't have an account <a href="http://bit.ly/account/register">register</a>
  - Copy the $bitly property from plugins/bitly/config/bitly_config.php.default to you app/config/database.php file and add in your apiKey and login
  - Test by pointing your browser to 'http://your-host-name/bitly/bitly_links' or 'http://your-host-name/bitly/bitly_links/add' or 'http://your-host-name/bitly/bitly_links/view'

Usage
-----

Include the Bitly.BitlyLink model in you Controller::uses property or use ClassRegistry::init('Bitly.BitlyLink');

  - Retrieving your recent bitly links (gotten from you recent rss feed, which must be configured as public in bit.ly account area):

        BitlyLink::find('recent');

    Note, only the last 20 are available.

    Returns data such as:

        Array
        (
            [Rss] => Array
                (
                    [version] => 2.0
                    [Channel] => Array
                        (
                            [title] => Recent Bookmarks from neilcrookes on bit.ly
                            [link] => http://bit.ly/u/neilcrookes.rss
                            [description] => Links recently shortened by neilcrookes on bit.ly
                            [lastBuildDate] => Mon, 21 Jun 2010 20:05:45 GMT
                            [generator] => PyRSS2Gen-1.0.0
                            [docs] => http://blogs.law.harvard.edu/tech/rss
                            [Item] => Array
                                (
                                    [0] => Array
                                        (
                                            [title] => neilcrookes's CakePHP-Yahoo-Geo-Planet-Plugin at master - GitHub
                                            [link] => http://bit.ly/9c6oif
                                            [description] => neilcrookes shortened a link to this page on bit.ly: http://bit.ly/9c6oif Source: http://github.com/neilcrookes/CakePHP-Yahoo-Geo-Planet-Plugin See who else is talking about this page http://bit.ly/9c6oif+
                                            [author] => neilcrookes
                                            [comments] => http://bit.ly/9c6oif+
                                            [guid] => Array
                                                (
                                                    [value] => http://bit.ly/9c6oif+
                                                    [isPermaLink] => true
                                                )

                                            [pubDate] => Thu, 17 Jun 2010 23:53:29 GMT
                                        )


  - Retrieving someone else's recent bitly links (gotten from their recent rss feed, which must be configured as public in bit.ly account area):

        BitlyLink::find('recent', array(
          'conditions' => array(
            'login' => 'a bit.ly user name'
          )
        ));

    Note, only the last 20 are available.

  - Retrieving click stats for a bit.ly hash:

        BitlyLink::find('clicks', array(
          'conditions' => array(
            'hash' => 'a bit.ly hash',
          ),
        ));

    Returns data such as:

        Array
        (
            [clicks] => Array
                (
                    [0] => Array
                        (
                            [user_clicks] => 266
                            [global_hash] => dwnpIh
                            [hash] => dd2Ndi
                            [user_hash] => dd2Ndi
                            [global_clicks] => 270
                        )

                )

        )

  - Retrieving click stats for a bit.ly short url:

        BitlyLink::find('clicks', array(
          'conditions' => array(
            'shortUrl' => 'a bit.ly short url',
          ),
        ));

  - Creating a bit.ly link:

        BitlyLink::save(array(
          'BitlyLink' => array(
            'longUrl' => 'http://www.example.com',
          ),
        ));

    Returns data such as:

        Array
        (
            [long_url] => http://www.neilcrookes.com/2010/06/01/rest-datasource-plugin-for-cakephp/
            [url] => http://bit.ly/dd2Ndi
            [hash] => dd2Ndi
            [global_hash] => dwnpIh
            [new_hash] => 0
        )

  - Creating a j.mp link:

        BitlyLink::save(array(
          'BitlyLink' => array(
            'longUrl' => 'http://www.example.com',
            'domain' => 'j.mp'
          ),
        ));

To do
-----

Implement all the calls available on the <a href="http://code.google.com/p/bitly-api/wiki/ApiDocumentation">Bit.ly API</a>