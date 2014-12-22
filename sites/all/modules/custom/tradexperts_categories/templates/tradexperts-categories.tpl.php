<div class="categories-body-content">
<div class="image-categories">
  <?php foreach ($select as $key => $value) { ?>
  <div class="categories-news-picture">
    <?php
      $ways = array(
      	'style_name' => '100x52sc', 
      	'path' => $value->uri,
      	);
      print l(theme('image_style', $ways), 'node/' . $value->nid, array('html' => TRUE));
     	?>
      </div>
    <div class="categories-news-title">
      <?php print l($value->title, 'node/' . $value->nid); ?>
    </div>
    <br>
   <?php } ?>
  </div>
</div>