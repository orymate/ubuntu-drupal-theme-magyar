<?php
function ubuntumagyar_comment_submitted($comment) {
  return sprintf("<strong>%s</strong> – %s",
      theme('username', $comment, true),
      l(rich_format_date($comment->timestamp),
              $_GET['q'],
              array('attributes' => array(
                    'class' => 'permalink',
                    'title' => 'Permalink a hozzászólásra. Beküldés ideje: ' .
                               format_date($comment->timestamp)
                    ),
              'fragment' => 'comment-' . $comment->cid))
    );
}

function ubuntumagyar_node_submitted($node) {
  return t('!datetime – !username',
    array(
      '!username' => theme('username', $node),
      '!datetime' => format_date($node->created, 'large'),
    ));
}

/**
 * Theme a single comment block.
 *
 * @param $comment
 *   The comment object.
 * @param $node
 *   The comment node.
 * @param $links
 *   An associative array containing control links.
 * @param $visible
 *   Switches between folded/unfolded view.
 * @ingroup themeable
 */
function ubuntumagyar_comment_view($comment, $node, $links = array(), $visible = TRUE) {
  static $first_new = TRUE;

  $output = '';
  $comment->new = node_mark($comment->nid, $comment->timestamp);
  if ($first_new && $comment->new != MARK_READ) {
    // Assign the anchor only for the first new comment. This avoids duplicate
    // id attributes on a page.
    $first_new = FALSE;
    $output .= "<a id=\"new\"></a>\n";
  }

  #$output .= "<a id=\"comment-$comment->cid\"></a>\n";

  // Switch to folded/unfolded view of the comment
  if ($visible) {
    $comment->comment = check_markup($comment->comment, $comment->format, FALSE);

    // Comment API hook
    comment_invoke_comment($comment, 'view');

    $output .= theme('comment', $comment, $node, $links);
  }
  else {
    $output .= theme('comment_folded', $comment);
  }

  return $output;
}
function ubuntumagyar_nextcommentid()
{
  static $n = 1;
  return $n++;
}

/**
 * Format a username.
 *
 * @param $object
 *   The user object to format, usually returned from user_load().
 * @return
 *   A string containing an HTML link to the user's page if the passed object
 *   suggests that this is a site user. Otherwise, only the username is returned.
 */
function ubuntumagyar_username($object, $long = false) {

  if ($object->uid && $object->name) {
    // Shorten the name when it is too long or it will break many tables.
    if (drupal_strlen($object->name) > 20) {
      $name = drupal_substr($object->name, 0, 18) .'…';
    }
    else {
      $name = $object->name;
    }

    if (user_access('access user profiles')) {
      $output = l($name, 'user/'. $object->uid, array('attributes' => array('title' => t('View user profile.'))));
    }
    else {
      $output = check_plain($name);
    }
    if ($long) {
      switch ($object->uid) {
	case 10:
        $output .= ' <a href="http://barack.fsf.hu" title="Rendszeradminisztrátor"><img src="/' . $GLOBALS['theme_path'] . '/barack.png" alt="barack" /></a>';
	break;
	case   2: //gsuveg
        case   3: //phanatic
        case   8: //kg 
        case  10: //atya
        case  14: //maat
        case  29: //toros
        case  64: //zaivaldi
        case 2711: //ulysses
	case 4737: //bogi
        $output .= ' – ' . l('ubuntu.hu szerkesztő', 'kapcsolat', array('attributes' => array('class' => 'username-web')));
	break;
	case   357: // n-drew
        case  6870: // edyth
        #case  2711: // ulysses
        case 10393:  // test
        case   829: // bios007
        case   105: // sdc
	case  5936: // kockacukor
	case  3841: // gorkhaan
        case 17581: case 10460: case 9932: case 7047: case 8927: // node/20628
        $output .= ' – ' . l('moderátor', 'kapcsolat', array('attributes' => array('class' => 'username-mod')));
	break;
// http://ubuntu.hu/node/13105
	#case 2711:
	case 4323:
	case 1808:
	case 3420:
	case 153:
	case 708:
	case 16:
	case 268:
        $output .= ' – ' . l('magyar közösségi tag', 'http://ubuntu.hu/node/12220', array('attributes' => array('class' => 'username-mkt')));
	break;
      }
    }
  }
  else if ($object->name) {
    // Sometimes modules display content composed by people who are
    // not registered members of the site (e.g. mailing list or news
    // aggregator modules). This clause enables modules to display
    // the true author of the content.
    if (!empty($object->homepage)) {
      $output = l($object->name, $object->homepage, array('attributes' => array('rel' => 'nofollow')));
    }
    else {
      $output = check_plain($object->name);
    }

    $output .= ' ('. t('not verified') .')';
  }
  else {
    $output = variable_get('anonymous', t('Anonymous'));
  }

  return $output;
}
function rich_format_date ($t) {
  $c = time();
  define('egynap', 3600 * 24);

  if (($t - ($t % egynap)) == ($c - ($c % egynap))) { //ma
    return 'ma, ' . format_date($t, 'custom', 'G.i');
  }
  if (($t - ($t % egynap) + egynap) == ($c - ($c % egynap))) { //tegnap
    return 'tegnap, ' . format_date($t, 'custom', 'G.i');
  }
  if (date('Y', $t) == date('Y', $c)) { // iden
    if (date('W', $t) == date('W', $c)) { //a heten
      return format_date($t, 'custom', 'l, G.i');
    }
    return format_date($t, 'custom', 'F j. G.i');
  }
  return format_date($t, 'custom', 'Y. F j. G.i');
}

function phptemplate_body_attributes($is_front = false, $layout = 'none', $logged_in = false) {
  if ($is_front) {
    $body_id = $body_class = 'homepage';
  }
  else {
    // Remove base path and any query string.
    global $base_path;
    list(,$path) = explode($base_path, $_SERVER['REQUEST_URI'], 2);
    list($path,) = explode('?', $path, 2);
    $path = rtrim($path, '/');
    // Construct the id name from the path, replacing slashes with dashes.
    $body_id = str_replace('/', '-', $path);
    // Construct the class name from the first part of the path only.
    list($body_class,) = explode('/', $path, 2);
  }
  
  $body_id = 'page-'. $body_id;
  $body_class = 'section-'. $body_class;

  if ($logged_in) {
    $body_class .= ' user-in';  
  } else {
    $body_class .= ' user-out';
  }


  return " id=\"$body_id\" class=\"$body_class\"";
}

if (variable_get('test_site', false)) {
    drupal_set_html_head('<meta name="robots" content="noindex, nofollow" />');
}
