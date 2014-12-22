<div class="brokers">
  <?php
  foreach ($brokers as $item):
  ?>
    <div class="broker-item">
    	<div class="icon">
        <a href="<?php print $item->url_address; ?>" rel="nofollow" target="_blank">
          <?php
            print theme('image_style', array(
              'style_name' => '31x32sc',
              'path'       => $item->uri,
            ));
          ?>
        </a>
      </div>
      <div class="link">
        <a href="<?php print $item->url_address; ?>" rel="nofollow" target="_blank">
          <?php print $item->title; ?>
        </a>
      </div>
    </div>
  <?php
  endforeach;
  ?>
</div>