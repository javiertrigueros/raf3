<?php
$aGroups = $aUser->getGroups(); 
$has_groups =  ($aGroups && is_object($aGroups) && (count($aGroups) > 0));

if ($has_groups)
{
    if (isset($class) && $class)
    {
        echo "<li class='$class groups'>";
    }
    else
    {
        echo "<li class='groups'><a href='".url_for('@user_list')."'>";
    }
    $i = 0;
    foreach ($aUser->getGroups() as $group)
    {
        echo $group;
        $i++;
        if ($i < count($aGroups))
        {
         echo '&nbsp;-&nbsp;'; 
        }
        
    }
    echo "</a></li>";
}
