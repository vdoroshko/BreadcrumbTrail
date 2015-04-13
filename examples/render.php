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
 * Items to push onto the breadcrumb trail
 */
$items = array(
    new BreadcrumbItem('https://developer.mozilla.org/en-US/', 'Mozilla Developer Network'),
    new BreadcrumbItem('https://developer.mozilla.org/en-US/docs/MDN', 'The MDN project'),
    new BreadcrumbItem('https://developer.mozilla.org/en-US/docs/MDN/Getting_started', 'Getting started on MDN')
);

/**
 * Push the items onto the breadcrumb trail
 */
for ($i = 0; $i < count($items); $i++) {
    $result = $breadcrumbTrail->push($items[$i]);
    if (BreadcrumbTrail::isError($result)) {
        die($result->getMessage());
    }
}

/**
 * Get an item from the end of the breadcrumb trail
 */
$topItem = $breadcrumbTrail->top();
if (BreadcrumbTrail::isError($topItem)) {
    die($topItem->getMessage());
}

/**
 * Enclose the breadcrumb trail links in a paragraph tag
 */
echo '<p>';

/**
 * Iterate through the breadcrumb trail
 */
$breadcrumbTrail->rewind();
while ($breadcrumbTrail->valid()) {
    /**
     * Get current item
     */
    $currentItem = $breadcrumbTrail->current();

    /**
     * Determine whether the current item is equal to the item from the end of the
     * breadcrumb trail
     */
    $result = $currentItem->equals($topItem);
    if (BreadcrumbTrail::isError($result)) {
        die($result->getMessage());
    }

    /**
     * Render the current item
     */
    if ($result) {
        echo $currentItem->title, "</p>\n";
    } else {
        echo '<a href="', $currentItem->url, '">', $currentItem->title, "</a> &rarr;\n  ";
    }

    /**
     * Go to next item
     */
    $breadcrumbTrail->next();
}

?>
