<?php
function safe_redirect_target(?string $location, string $default = "index.php"): string
{
    if (
        $location === null ||
        $location === "" ||
        $location[0] !== "/" ||
        str_starts_with($location, "//") ||
        preg_match('#^https?:#i', $location)
    ) {
        return $default;
    }

    return $location;
}
