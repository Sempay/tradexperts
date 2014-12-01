<div class="tradexperts-slideshow <?php print $slider; ?>">
	<div class="slideshow-content">
		<?php
			foreach ($slides as $item):
		?>
				<div class="item">
					<h3 class="slide-item-title">
						<?php print $item['title']; ?>
					</h3>
					<?php
						if ($item['sub_title']) {
							print '<div class="slide-item-sub-title">' . $item['sub_title'] . '</div>';
						}
					?>
					<div class="slide-item-link">
						<?php print $item['link']; ?>
					</div>
					<?php
						print theme('image', array(
							'path' => $item['background_image'],
						));
					?>
				</div>
		<?php
			endforeach;
		?>
	</div>
</div>