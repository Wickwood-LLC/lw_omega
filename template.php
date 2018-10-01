<?php

/**
 * @file
 * Template overrides as well as (pre-)process and alter hooks for the
 * LW Omega theme.
 */

function lw_omega_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb) && (count($breadcrumb) > 2 )) {	// Show only if there are more than 2 links
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode(' » ', $breadcrumb) . '</div>';
    return $output;
  }
}
