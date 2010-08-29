<div class="comment<?php 
    print ($comment->new) ? ' comment-new' : '';
    print ' '. $status;
    print ' '. $zebra; ?>" id="comment-<?php print $comment->cid ?>">
  <?php print $picture ?>
  <div class="comment-bar">
    <?php if ($submitted): ?>
      <span class="submitted">
        <?php print $submitted; ?>
        <?php 
          if ($comment->pid)
            print '<span class="comment-op"> – ' 
            . l('előzmény', $_GET['q'], 
                array('attributes' => 
                  array('class' => 'comment-parent-link'),
                  'fragment' => 'comment-' . $comment->pid)) 
            . '</span>';
        ?>
      </span>
    <?php endif; ?>

    <?php if ($comment->new) : ?>
      <span class="new" id="comment-new-<?php print ubuntumagyar_nextcommentid(); ?>"><?php print drupal_ucfirst($new) ?></span>
    <?php endif; ?>
  </div>

  <div class="content">
    <?php print $content ?>
    <?php if ($links): ?>
      <div class="links">
        <?php print $links ?>
      </div>
    <?php endif; ?>
    <div class="comment-signature">
      <?php if ($signature): ?>
        <?php print str_replace('href', 'rel="nofollow" href', $signature) ?>
      <? else: ?>
        <p>&nbsp;</p>
      <? endif; ?>
    </div>
  </div>
</div>
