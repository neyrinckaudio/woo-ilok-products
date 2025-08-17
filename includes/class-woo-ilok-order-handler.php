<?php

defined('ABSPATH') || exit;

class WooIlokOrderHandler
{
    public function __construct()
    {
        add_action('init', array($this, 'init'));
    }

    public function init()
    {
        // Order processing hooks will be added here
    }
}