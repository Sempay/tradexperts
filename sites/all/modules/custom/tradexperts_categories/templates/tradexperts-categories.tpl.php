<div class="categories-body-content">
<div class="image-categories">
  <?php foreach ($select as $key => $value) { ?>
  <div class="categories-news-picture">
    <?php
      $ways = array(
      	'style_name' => '100x52sc', 
      	'path' => $value->uri,
      	);
      print theme('image_style', $ways);
     	?>
      </div>
    <div class="categories-news-title">
      <?php print l($value->title, 'node/' . $value->nid); ?>
    </div>
    <br>
   <?php } ?>
  </div>
</div>