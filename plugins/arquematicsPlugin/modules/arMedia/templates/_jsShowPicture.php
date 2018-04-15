<?php $pictureId = isset($pictureId) ? $sf_data->getRaw('pictureId') : null; ?>

<script type="text/javascript">
    $(document).ready(function()
    {
        $('#<?php echo $pictureId ?>').picture({inlineDimensions: true});
    });
 </script>
