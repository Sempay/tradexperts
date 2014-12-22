<div id="hc_full_comments"> 
<?php foreach($all_comments as $comment):?> 
<div style='position:relative;padding:5px;font-size:12px;'>
  <div style='position:absolute; width:36px'>
    <?php
      if (!empty($comment->picture) && is_numeric($comment->picture) && $file = file_load($comment->picture)) {
        echo '<img width="36px" src="'.file_create_url($file->uri).'" alt="" />';
      }
    ?>
  </div>
  <div style='margin-left:50px;'>
    <div style='float:left;margin-right:5px;color:#3B5998;font-size: 11px;font-family: tahoma,verdana,arial,sans-serif;font-weight: bold;'><?php echo $comment->name;?></div>
    <div style='color: gray;font-size:10px;'><?php echo date('Y-m-d H:i:s',$comment->created);?></div>
    <div style='padding:5px;'><?php echo $comment->comment_body_value;?></div>
  </div>
</div>
<?php endforeach;?>
</div>