<?php

namespace MailKit;

/**
 * The admin class
 */
class Admin
{
    public function __construct()
    {
        new Admin\Menu();
        new Admin\PostType();
        new Admin\MetaBoxes();
    }
}
