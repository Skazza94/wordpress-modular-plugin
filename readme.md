# WordPress Modular Plugin
Core of WordPress Modular Plugin (or **WPM**).

## Requirements
This is a Composer library and you need to include it into a "plugin" project. 

Boilerplate project can be found [here](https://github.com/Skazzino/wpm-boilerplate-plugin). 

You need to install [Composer](https://getcomposer.org/) before using this repository. On Ubuntu, you can use:
```
sudo apt-get install composer
```

## Contribute
Your help is essential to make WPM even better. 

So please fork it and do whatever you want! I'll review all pull requests and merge them, hoping to make this a really easy-to-use, complete and solid "framework". Thanks!

## What is it

Everyone's got its own way to produce WordPress plugins, there's not an official pattern to follow, so it can be hard 
to structure the code in the right way to be easily readable and modifiable (expecially while developing big plugins).

Some people tried to use an MVC approach, but it doesn't fully fits WP plugins logic. Other methods are out there, but nothing is satisfying me when it comes to develop a very big plugin.
So, I've decided to try something new.

This framework is based on the idea of "module", something that can be added/removed without compromising other plugin 
functionalities. 

For example, you have an *Admin* module and an *User* module, if you don't need the *User* module anymore, you
should be able to remove it without touching nothing in other plugin files or even in the *Admin* module.

To achieve this, all WordPress actions/filters/shortcodes/etc. should be defined in a dichiarative way, using an OOP approach.
So, in WPM, you can add/remove your module simply adding/removing a subfolder. You can also re-use some modules in other plugins
by simply copy/pasting the folder.

Sounds awesome, right? But there's much more to tell you, so here's the Features list!

## Features
  1. An **Application Context** where you can get and register Services dynamically.
        1. Lot of really useful services already bundled (discussed later).
        2. Create your own Services and register them in the framework using a configuration file.
  2. An **Object Container** using Symfony's [DependencyInjection](https://github.com/symfony/dependency-injection), accessible through the Application Context.
        1. Supported Object Multiple Instances or Singleton.
        2. Almost each object created in the framework is handled by the Container for best performances.
        3. Services use Proxies so they're loaded only when required.
  3. **Modules**, the core feature of this framework.
        1. Really simple creation method:
            - Create a new subfolder in the **modules** folder;
            - Create all the business logic without worrying about WordPress hooks;
            - Create a **config.yml** file (syntax discussed in Wikis) where you declare all the WP Hooks;
            - You're done. Framework and Hookers will take care of the rest.
        2. You can also add **Module Providers** which can load modules from other packages.
            - For example, you can create a Composer Package (which is an WPM Module);
            - You can hook it into your own plugin only specifing the ModuleProvider class in a configuration file!
  4. A lot of built-in and useful Services:
        1. **Cache**, based on Symfony's [Cache](https://github.com/symfony/cache).
        2. **Config**, based on Zend's [Config](https://github.com/zendframework/zend-config).
            1. Simplified method to access values using a "dotted notation":
                - For example, ``my_config_file.first_value.second_value``.
        3. **Environment** (to handle plugin constants), based on [DotEnv](https://github.com/vlucas/phpdotenv).
        4. **Filesystem**, a good FS abstraction using [Flysystem](https://github.com/thephpleague/flysystem).
            1. Easily declare a new Filesystem using a configuration file.
        5. **L10n**, abstraction from WordPress localization. 
            1. Registers the plugin text domain 
            2. Can be accessed to get translations easily.
        6. **Url**, build/parse and get URLs for locations.
        7. **View**, with multiple choices for views rendering:
            1. A robust template engine using [Twig 1.x](http://twig.sensiolabs.org/doc/1.x/) and [Timber](https://github.com/timber/timber) (for WP support).
            2. Plain HTML and PHP files support (with no template engines).   
  6. Helper functions to access Services functions in an easy way, for example:
        - ``config($key)`` to get a configuration value from a configuration file.
        - ``storage($name)`` to get a filesystem.
        - ``app($serviceName)`` to get a Service from the Application Context.
  
## What's missing
- [ ] All Flysystem drivers implemented into ``FilesystemManager``
- [ ] Use Cache Flag for more Services if possible
- [ ] WordPress Service should do something :(
- [ ] Better code comments
- [ ] Other?
