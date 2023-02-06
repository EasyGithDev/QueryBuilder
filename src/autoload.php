<?php

namespace QueryBuilder;


spl_autoload_register(function ($classname) {

    $classname =    str_replace(__NAMESPACE__, '', $classname);
    $classname =    str_replace('\\', '', $classname);
    $classname =    mb_strtolower($classname);

    include __DIR__ . '/' . $classname . '.php';
});
