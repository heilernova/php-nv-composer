<?php
namespace PhpNv\Config;


class Settings
{
    public Application $application;
    public Debug $debug;
    public Databases $databases;

    public function __construct($settings)
    {
        $this->application = new Application($settings['application']);
        $this->debug = new Debug($settings['debug']);
        $this->databases = new Databases($settings['databases']);
    }
}