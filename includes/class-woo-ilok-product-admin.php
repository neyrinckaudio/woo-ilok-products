<?php

defined('ABSPATH') || exit;

class WooIlokProductAdmin
{
    public function __construct()
    {
        add_action('init', array($this, 'init'));
    }

    public function init()
    {
        // Product admin interface hooks will be added here
    }
}