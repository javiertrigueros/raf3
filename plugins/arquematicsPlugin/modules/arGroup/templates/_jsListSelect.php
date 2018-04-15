<?php $activeListControl = isset($activeListControl) ? $sf_data->getRaw('activeListControl') : ''; ?>
<?php $selectContainer = isset($selectContainer) ? $sf_data->getRaw('selectContainer') : ''; ?>
<script type="text/javascript" >
    $(document).ready(function(){
        $('<?php echo $activeListControl ?>').selectpicker({
                    iconBase: 'fa',
                    tickIcon: 'fa-check',
                    hideDisabled: false,
                    appendContainer:'<?php echo $selectContainer ?>',
                    noneSelectedText : '<?php echo __('Select lists',array(),'wall') ?>',
                    noneResultsText : '<?php echo __('No list',array(),'wall') ?>',
                    countSelectedText: '<?php echo __('n lists selected',array(),'wall') ?>',
                    selectedTextFormat: 'count > 3'});
    });
</script>