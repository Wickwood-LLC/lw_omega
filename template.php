<?php

/**
 * @file
 * Template overrides as well as (pre-)process and alter hooks for the
 * LW Omega theme.
 */

function lw_omega_breadcrumb($variables)
{
    $breadcrumb = $variables['breadcrumb'];

    if (!empty($breadcrumb) && (count($breadcrumb) > 2)) {
        // Show only if there are more than 2 links
        // Provide a navigational heading to give context for breadcrumb links to
        // screen-reader users. Make the heading invisible with .element-invisible.
        $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

        $output .= '<div class="breadcrumb">' . implode(' » ', $breadcrumb) . '</div>';
        return $output;
    }
}

/**
 * Implements hook_preprocess_page()
 */
function lw_omega_preprocess_page(&$vars)
{
    if (arg(0) == 'calendar') {
        // Calendar page
        drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/calendar.css', array('group' => CSS_THEME));
    } else if (arg(0) == 'media-kit') {
        // Media kit page
        drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/media-kit.css', array('group' => CSS_THEME));
    } else if ((arg(0) == 'node' && preg_match('/^\d+$/', arg(1)) && empty(arg(2)))) {
        // Node view page.
        // Get node being displayed.
        $node = menu_get_object();
        if ($node->type == 'published_books') {
            drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/published-books.css', array('group' => CSS_THEME));
        } else if ($node->type == 'published_article_listing') {
            drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/published-article.css', array('group' => CSS_THEME));
        }
    } else if (arg(0) == 'users') {
        // User page
        drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/login.css', array('group' => CSS_THEME));
    } else if (arg(0) == 'user' && arg(1) == 'login') {
        // Login page
        drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/login.css', array('group' => CSS_THEME));
    } else if (request_path() == 'contact-larry') {
        // Contact page
        drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/contact.css', array('group' => CSS_THEME));
    }
}

/**
 * Implements hook_preprocess_maintenance_page()
 */
function lw_omega_preprocess_maintenance_page()
{
    drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/maintenance.css', array('group' => CSS_THEME));
}

function lw_omega_views_pre_render(&$view)
{
    if (in_array($view->name, array('nodequeue_1', 'nodequeue_2'))) {
        drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/nodequeue.css', array('group' => CSS_THEME));
    }
    if ($view->name == 'list_of_published_articles') {
        drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/published-articles-view.css', array('group' => CSS_THEME));
    }
}
