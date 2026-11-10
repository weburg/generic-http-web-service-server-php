<?php
/*
Validator usage

Built-ins:

$name = arg("name", "Unknown", "is_string");

is_int
is_string
is_bool
is_array
is_object
is_null

Custom in-line:

$age = arg("age", 0, function (mixed $value): bool {
    return is_int($value) && $value >= 18;
});
*/

function arg(string $argName, mixed $default = "", ?callable $validatorFunction = null): mixed {
    if (!isset($GLOBALS[$argName])) {
        return $default;
    }

    if ($validatorFunction !== null && !$validatorFunction($GLOBALS[$argName])) {
        return $default;
    }

    return $GLOBALS[$argName];
}
?>