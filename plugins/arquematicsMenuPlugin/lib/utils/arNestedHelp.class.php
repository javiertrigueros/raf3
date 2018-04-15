<?php
/**
 * Ayuda para trabajar con nested sets
 * 
 * @package         arquematicsPlugin
 * @subpackage      utils
 * @author          Javier trigueros
 */
class arNestedHelp
{
   
   static public function toHierarchy($collection, $depth_key = 'depth')
   {
        // Trees mapped
        $trees = array();
        $l = 0;

        if (count($collection) > 0) {
                // Node Stack. Used to help building the hierarchy
                $stack = array();

                foreach ($collection as $node) 
                {
                        if ($node && is_array($node))
                        {
                            $item = $node;
                            $item['children'] = array();
                        }//es un objecto de Doctrine
                        else
                        {
                            $item = $node->getData();
                            $item['children'] = array();
                        }
                        
                       

                        // Number of stack items
                        $l = count($stack);

                        // Check if we're dealing with different levels
                        while($l > 0 && $stack[$l - 1][$depth_key] >= $item[$depth_key]) {
                                array_pop($stack);
                                $l--;
                        }

                        // Stack is empty (we are inspecting the root)
                        if ($l == 0) {
                                // Assigning the root node
                                $i = count($trees);
                                $trees[$i] = $item;
                                $stack[] = & $trees[$i];
                        } else {
                                // Add node to parent
                                $i = count($stack[$l - 1]['children']);
                                $stack[$l - 1]['children'][$i] = $item;
                                $stack[] = & $stack[$l - 1]['children'][$i];
                        }
                }
        }

        return $trees;
    }

    static public function nestify( $arrs, $depth_key = 'depth' )  
    {  
        $nested = array();  
        $depths = array();  
          
        foreach( $arrs as $key => $arr ) {  
            if( $arr[$depth_key] == 0 ) {  
                $nested[$key] = $arr;  
                $depths[$arr[$depth_key] + 1] = $key;  
            }  
            else {  
                $parent =& $nested;  
                for( $i = 1; $i <= ( $arr[$depth_key] ); $i++ ) {  
                    $parent =& $parent[$depths[$i]];  
                }  
                  
                $parent[$key] = $arr;  
                $depths[$arr[$depth_key] + 1] = $key;  
            }  
        }  
          
        return $nested;  
    }
    /**
     * 
     * devuelve el nivel del nodo
     * 
     * @param <collection|array $nodes>
     * @param <int $findNodeId>
     * @param <int $level>
     * @return <int>
     */
    static public function getTreeLevel($nodes, $findNodeId, $level = -1)
    {

        if ((count($nodes) > 0) && ($level < 0))
        {
            foreach($nodes as $node)
            {
                if ($node && is_object($node))
                {
                  $node = $node->getData();
                  $node['children'] = array();
                }
                
                if ($node['id'] === $findNodeId)
                {
                   $level = $node['level'];  
                }
                else 
                {
                  $level = arNestedHelp::getTreeLevel($node['children'], $findNodeId, $level);   
                }
            }
        }
        
        return $level;
    
    }
    /**
     * 
     * devuelve un array jerárquico
     * 
     * @param <collection|array $nodes>
     * @param <int $findNodeId>
     * @param <int $level>
     * @return <array $nodes>
     */
    static public function buildTree($nodes, $id = 0, $level = 0)
    {
        $tree = array();

        if (count($nodes) > 0)
        {
           
            foreach($nodes as $node)
            {
                if ($node && is_object($node))
                {
                  $node = $node->getData();
                }
                
                $node['level'] = $level;
                
                if($node['parent'] == $id)
                {
                    array_push($tree, $node); 
                }
            }

            for($i = 0; $i < count($tree); $i++)
            {
                $tree[$i]['children'] = arNestedHelp::buildTree($nodes, $tree[$i]['id'], $level +1);
            }

            return $tree;
        }
        
        return $tree;
    }
    /*
 //--Flat Structure--//
$Nodes = array(
            array("Name" => "Section One", "ID" => "1", "ParentID" => "0"),
            array("Name" => "Section Two", "ID" => "2", "ParentID" => "0"),
            array("Name" => "My Game", "ID" => "3", "ParentID" => "2"),
            array("Name" => "Child", "ID" => "4", "ParentID" => "3"),
            array("Name" => "Section Three", "ID" => "5", "ParentID" => "0")
            );

//--Show Flat Structure--//
print_r($Nodes);

//--Call Build Tree (returns structured array)
$TheTree = BuildTree($Nodes);

//--This function converts flat structure into an array--//
function BuildTree($TheNodes, $ID = 0)
    {
    $Tree = array();

    if(is_array($TheNodes))
        {
        foreach($TheNodes as $Node)
            {
            if($Node["ParentID"] == $ID)
                array_push($Tree, $Node);
            }

        for($x = 0; $x < count($Tree); $x++)
            {
            $Tree[$x]["Children"] = BuildTree($TheNodes, $Tree[$x]["ID"]);
            }

        return($Tree);
        }
    }

//-Show Array Structure--//
print_r($TheTree);

//--Print the Categories, and send their children to DrawBranch--//
    //--The code below allows you to keep track of what category you're currently drawing--//
printf("<ul>");
foreach($TheTree as $MyNode)
    {
    printf("<li>{$MyNode['Name']}</li>");
    if(is_array($MyNode["Children"]) && !empty($MyNode["Children"]))
        DrawBranch($MyNode["Children"]);
    }
printf("</ul>");

//--Recursive printer, should draw a child, and any of its children--//
function DrawBranch($Node)
    {
    printf("<ul>");

    foreach($Node as $Entity)
        {
        printf("<li>{$Entity['Name']}</li>");

        if(is_array($Entity["Children"]) && !empty($Entity["Children"]))
            DrawBranch($Entity["Children"]);
        }

    printf("</ul>");
    }
*/
    
 public static function dataToArray($data)
 {
     
    foreach($data as $menuItem) 
    {
        print_r($menuItem); 
    }
     
 }

  public static function dataToArray2($data)
  {
    $rows = array();
    $stack = array();

    $lft = 0;   // Left value
    $rgt = 0;   // Right value
    $plvl = -1; // Previous node level

    $id = 0;
    
    foreach($data as $line) 
    {
       
        $id++;
        $lvl = $line['depth'];

        // Skip empty/faulty lines
        if (trim($id) != '')
        {
            if ($lvl > $plvl) 
            {
                $lft++;
                $rgt = 0;
                array_push($stack, $id);
            }
            else if ($lvl == $plvl) 
            {
                $pid = array_pop($stack);
                $rows[$pid][2] = $rows[$pid][1] + 1;
                $lft = $lft + 2;
                $rgt = 0;
                array_push($stack, $id);
            }
            else 
            {
                $lft = $lft + ($plvl - $lvl) + 2;

                $diff = $plvl - $lvl + 1;
                
                for($n = 0; $n < $diff; $n++) 
                {
                    $pid = array_pop($stack);
                    $rows[$pid][2] = $lft - $diff + $n;
                }
                array_push($stack, $id);
            }

            $rows[$id] = array($id, $lft, $rgt);
            $plvl = $lvl;
      
        }
  }

        $plvl++;
        $cnt = count($rows) * 2;
        $leftovers = count($stack);

        for($n = 0; $n < $leftovers; $n++) 
        {
            $pid = array_pop($stack);
            $rows[$pid][2] = $cnt - $plvl-- + $n;
        }

        return $rows;
    }
}


