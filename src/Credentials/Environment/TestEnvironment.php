<?php
/**
 * Created by PhpStorm.
 * Date: 7/24/17
 */

namespace OK\Credentials\Environment;


class TestEnvironment extends Environment
{
    /**
     * Environment path part
     * @return string
     */
    function getPath() {
        return "test";
    }
}