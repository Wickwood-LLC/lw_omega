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
        } else if ($node->type == 'page') {
            drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/basic-page.css', array('group' => CSS_THEME));
        }
    } else if (arg(0) == 'users') {
        // User page
        drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/login.css', array('group' => CSS_THEME));
    } else if (arg(0) == 'user' && in_array(arg(1), array('login', 'password'))) {
        // Login page
        drupal_add_css(drupal_get_path('theme', 'lw_omega') . '/css/login.css', array('group' => CSS_THEME));
    }
    if (request_path() == 'contact-larry') {
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

function lw_omega_links($variables) {
  $links = (array) $variables['links'];
  $attributes = (array) $variables['attributes'];
  $heading = $variables['heading'];
  global $language_url;
  $output = '';
  if (!empty($links)) {

    // Treat the heading first if it is present to prepend it to the
    // list of links.
    if (!empty($heading)) {
      if (is_string($heading)) {

        // Prepare the array that will be used when the passed heading
        // is a string.
        $heading = array(
          'text' => $heading,
          // Set the default level of the heading.
          'level' => 'h2',
        );
      }
      $output .= '<' . $heading['level'];
      if (!empty($heading['class'])) {
        $output .= drupal_attributes(array(
          'class' => $heading['class'],
        ));
      }
      $output .= '>' . check_plain($heading['text']) . '</' . $heading['level'] . '>';
    }
    $output .= '<ul' . drupal_attributes($attributes) . '>';
    $num_links = count($links);
    $i = 1;
    foreach ($links as $key => $link) {
      $class = array(
        $key,
      );

      // Add first, last and active classes to the list of links to help out
      // themers.
      if ($i == 1) {
        $class[] = 'first';
      }
      if ($i == $num_links) {
        $class[] = 'last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || $link['href'] == '<front>' && drupal_is_front_page()) && (empty($link['language']) || $link['language']->language == $language_url->language)) {
        $class[] = 'active';
      }
      $output .= '<li' . drupal_attributes(array(
        'class' => $class,
      )) . '>';
      if (isset($link['href'])) {

        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      elseif (!empty($link['title'])) {

        // Some links are actually not links, but we wrap these in <span> for
        // adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }
      if ($i != $num_links) {
        $output .= "</li> | \n";  
      }
      else {
        $output .= "</li>\n";
      }
      $i++;
    }
    $output .= '</ul>';
  }
  return $output;
}

