<?php

declare(strict_types=1);

function recursiveArraySearch(array $contents, array $searchElements) : mixed
{
    // get the end of the array to find what the user is ultimately looking for
    $finalSearchElement = $searchElements[array_key_last($searchElements)];

    // return if the element exists at the current level
    if (array_key_exists($finalSearchElement, $contents)) {
        return $contents[$finalSearchElement];
    }
    else {
        try {
            // reset the contents array to the nested array found with the first element in the searchElements
            $contents = $contents[$searchElements[0]];
        }
        catch (\Exception) {
            throw new Exception("Error while searching for '{$searchElements[0]}' element. Please ensure that config element exists.");
        }
        // drop the first element from the search elements for the next go around
        array_shift($searchElements);

        // rerun it
        return recursiveArraySearch($contents, $searchElements);
    }
}

function config(string $configPath) {
    $searchElements = explode('.', $configPath);

    // error if config directory doesn't exist
    if (!$configFiles = scandir(dirname(__DIR__) . '/config')) {
        throw new \Exception('Config files not found');
    }
    // error if config file cannot be found
    else if (!in_array("$searchElements[0].php", $configFiles)) {
        throw new \Exception('Config file not found');
    }
    // error if config path does not have a specified config element to return
    else if (count($searchElements) < 2) {
        throw new \Exception('Please supply a config element to return');
    }

    // get the initial array
    $contents = include dirname(__DIR__) . '/config/' . $searchElements[0] . '.php';
    // strip the first element because that's the filename. We don't need that after this step
    array_shift($searchElements);

    return recursiveArraySearch($contents, $searchElements);
}

/** Dump and Die */
function dd(mixed ...$vars) : void {
    echo "<pre style='color: green; font-size: 12px; line-height: 16px; background-color: #1c1c1c; padding: 10px;'>";
    var_dump($vars);
    echo "</pre>";
    die(0);
}