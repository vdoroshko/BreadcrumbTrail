<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Include the BreadcrumbTrail class
 */
require_once '../BreadcrumbTrail.php';

/**
 * Instantiate the BreadcrumbTrail object
 */
$breadcrumbTrail = &new BreadcrumbTrail;

/**
 * Check if the breadcrumb trail is empty
 */
$breadcrumbTrail->isEmpty();

/**
 * Push some items onto the breadcrumb trail
 */
$item = &new BreadcrumbItem('https://developer.mozilla.org/en-US/', 'Mozilla Developer Network');
$numItems = $breadcrumbTrail->push($item);

/**
 * Check whether a return value from the push() method is a
 * BreadcrumbTrail_Error object or not
 */
if (BreadcrumbTrail::isError($numItems)) {
    die($numItems->getMessage());
}

$item = &new BreadcrumbItem('https://developer.mozilla.org/en-US/docs/MDN', 'The MDN project');

$numItems = $breadcrumbTrail->push($item);
if (BreadcrumbTrail::isError($numItems)) {
    die($numItems->getMessage());
}

/**
 * Pop an item from the breadcrumb trail
 */
$item = $breadcrumbTrail->pop();

/**
 * Check whether a return value from the pop() method is a
 * BreadcrumbTrail_Error object or not
 */
if (BreadcrumbTrail::isError($item)) {
    die($item->getMessage());
}

/**
 * Push the popped item back to the breadcrumb trail
 */
$numItems = $breadcrumbTrail->push($item);
if (BreadcrumbTrail::isError($numItems)) {
    die($numItems->getMessage());
}

/**
 * Get an item from the end of the breadcrumb trail without removing it
 */
$topItem = $breadcrumbTrail->top();

/**
 * Check whether a return value from the top() method is a
 * BreadcrumbTrail_Error object or not
 */
if (BreadcrumbTrail::isError($topItem)) {
    die($topItem->getMessage());
}

/**
 * Get the number of items in the breadcrumb trail
 */
$numItems = $breadcrumbTrail->count();

/**
 * Check if the breadcrumb trail contains the given item
 */
$item = &new BreadcrumbItem('https://developer.mozilla.org/en-US/docs/MDN');
$result = $breadcrumbTrail->contains($item);

/**
 * Check whether a return value from the contains() method is a
 * BreadcrumbTrail_Error object or not
 */
if (BreadcrumbTrail::isError($result)) {
    die($result->getMessage());
}

?>
