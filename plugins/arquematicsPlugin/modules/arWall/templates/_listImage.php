<?php

$images = $message->getImages();

if ($images && (count($images) > 0))
    {
        ?>
        <script type="text/javascript">
            /*
            $(document).ready(function(){
                $('.group_<?php echo $message->getId() ?>').colorbox(
                        {rel:'group_<?php echo $message->getId() ?>',
                         diagram_editor:'<?php echo url_for("@diagram_edit?id=") ?>',
                         transition:"none",
                         photo: true,
                         current: "<?php echo __('image {current} of {total}',array(),'wall') ?>",
                         width:"85%",
                         height:"85%"});
                        });*/
             
        </script>
        <?php 
        
        
        echo "<div style='margin-top:10px;clear:both'>";
        foreach ($images as $image)
        { 
          include_partial('arWall/imageGroupItem',
                  array(
                      'image' => $image,
                      'class' => 'cboxElement group_'.$message->getId()
                      ));      
        }
        echo "</div>";
    }

 ?>