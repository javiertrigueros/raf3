<?php $pageBack = isset($pageBack)? $sf_data->getRaw('pageBack'): arMenuInfo::WALL ?>
<?php slot('global-head')?>
    <!-- Navbar -->
    <div class="ng-scope">
        <div class="tg_page_head ng-scope">
            <div  class="ar-header navbar navbar-static-top  navbar-inverse">
                <div class="container container-nav">
                    <?php include_component('arMenuAdmin','showBackButton', array('pageBack' => $pageBack)); ?>
                    <?php include_component('arMenuAdmin','showMainMenu'); ?>
                </div>
                <?php include_slot('global-head-extra') ?>
            </div>
        </div>
    </div>
    <!--/Navbar-->
<?php end_slot() ?>
