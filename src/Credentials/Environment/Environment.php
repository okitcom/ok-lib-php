<?php

namespace OK\Credentials\Environment;

/**
 * Represents an environment of OK
 */
abstract class Environment
{

    /**
     * Environment path part
     * @return string
     */
    abstract function getPath();
}