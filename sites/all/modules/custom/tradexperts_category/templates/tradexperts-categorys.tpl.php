<div class="category-body-content">
<div class="image-category">
  <?php foreach ($select as $key => $value) { ?>
  <div class="category-news-picture">
    <?php
      $ways = array(
      	'style_name' => '100x52sc', 
      	'path' => $value->uri,
      	);
      print theme('image_style', $ways);
     	?>
      </div>
    <div class="category-news-title">
      <?php print l($value->title, 'node/' . $value->nid); ?>
    </div>
    <br>
   <?php } ?>
  </div>
</div>