<?php $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null; ?>

<a href="<?php echo url_for('@ar_blog_event_edit?page_back='.arMenuInfo::ADMINEVENTS.'&id='.$a_event->getId()); ?>">
    <?php echo $a_event->title ?>
</a>