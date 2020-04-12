<?php

/**
 * this will filter value of provided parameter from request
 * @param type $input
 * @param type $parameter
 * @return type - value
 */
function filterParameter($input, $parameter) {
    return filter_input($input, $parameter, FILTER_SANITIZE_STRING);
}

/**
 *
 * @param type $input
 * @param type $parameter
 * @return type - value
 */
function filterArrayParameter($input, $parameter) {
    return filter_input($input, $parameter, FILTER_DEFAULT);
}

/**
 * this will filter value of provided parameter from request
 * @param type $input
 * @param type $parameter 
 * @return type - value
 */
function filterParameterFromJSON($parameter) {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, TRUE);

    if (isset($data[$parameter])) {
        return $data[$parameter];
    }
    return "";
}
