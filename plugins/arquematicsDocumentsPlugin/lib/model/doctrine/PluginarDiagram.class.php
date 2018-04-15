<?php

/**
 * PluginarDiagram
 * 
 * 
 * Arquematics 2012
 *
 * 
 * @package    Arquematics
 * @subpackage model
 * @author     Javier Trigueros Martinez de los Huertos javiertrigueros@arquematics.com
 * @version    0.1
 */
abstract class PluginarDiagram extends BasearDiagram
                                implements iFileResource
{

    const SVG           = 20;
    const WIREFRAME     = 30;
    const MINDMAPS      = 40;
    const BPMN          = 50;
    const EPC           = 60;
    const UML           = 70;
    const RAWCHART      = 80;
    const SEQUENCE      = 90;
    const USECASE       = 100;
    const SIMPLE_IMAGE  = 110;
    const PETRI         = 120;
    
    
    static protected $types = array(
        
         'paint' => array( 'id' => self::SIMPLE_IMAGE,
                           'name' => 'paint',
                           'template' => 'simpleImage',
                           //'out' => 'data:image/png;base64',
                           'out' => 'data:image/png;base64',
                           'class' => 'ar-icon-image ar-icon-big',
                           'classMini' => 'ar-icon-small-image ar-icon-small',
                           'extra' => false),
        
         'mindmaps' => array('id' => self::MINDMAPS,
                            'name' => 'mindmaps',
                            'template' => 'mindmap',
                            'out' => 'data:image/png;base64',
                            'class' => 'ar-icon-minmaps ar-icon-big',
                            'classMini' => 'ar-icon-minmaps ar-icon-small',
                            'extra' => false),
        
         'wireframe' => array('id' =>           self::WIREFRAME,
                            'name' =>           'wireframe',
                            'template' =>       'wireframe',
                            'out' =>            'data:image/png;base64',
                            'class' =>          'ar-icon-wireframe ar-icon-big',
                            'classMini' =>      'ar-icon-wireframe ar-icon-small',
                            'extra' => false),
        
         'svg' => array('id' =>                 self::SVG,
                        'name' =>               'svg',
                        'template' =>           'svg',
                        'out' =>                'svg',
                        'class' =>              'ar-icon-image ar-icon-big',
                        'classMini' =>          'ar-icon-image ar-icon-small',
                        'extra' => false),
        
         'rawchart' => array('id' =>            self::SVG,
                        'name' =>               'rawchart',
                        'template' =>           'rawchart',
                        'out' =>                'svg',
                        'class' =>              'ar-icon-bar-chart ar-icon-big',
                        'classMini' =>          'ar-icon-bar-chart ar-icon-small',
                        'extra' => false),
        
         'bpmn' => array('id' =>                self::BPMN,
                        'name' =>               'bpmn',
                        'template' =>           'diagram',
                        'out' =>                'svg',
                        'class' =>              'ar-icon-bpmn ar-icon-big',
                        'classMini' =>          'ar-icon-bpmn ar-icon-small',
                        'extra' =>              '/bpmn1.1/bpmn1.1.json'),
        
         'epc' => array('id' =>                 self::EPC,
                        'name' =>               'epc',
                        'template' =>           'diagram',
                        'out' =>                'svg',
                        'class' =>              'ar-icon-epc ar-icon-big',
                        'classMini' =>          'ar-icon-epc ar-icon-small',
                        'extra' =>              '/epc/epc.json'),
        
         'uml' => array('id' =>                 self::UML,
                       'name' =>                'uml',
                       'template' =>            'diagram',
                       'out' =>                 'svg',
                       'class' =>               'ar-icon-uml ar-icon-big',
                       'classMini' =>           'ar-icon-uml ar-icon-small',
                       'extra' =>               '/uml2.2/uml2.2.json'),
        
         'umlsequence' => array('id' =>         self::SEQUENCE,
                                'name' =>       'umlsequence',
                                'template' =>   'diagram',
                                'out' =>        'svg',
                                'class' =>      'ar-icon-sequence ar-icon-big',
                                'classMini' =>  'ar-icon-sequence ar-icon-small',
                                'extra' =>      '/umlsequence/umlsequence.json'),
        
         'umlusecase' => array('id' =>          self::USECASE,
                               'name' =>        'umlusecase',
                               'template' =>    'diagram',
                               'out' =>         'svg',
                               'class' =>       'ar-icon-usecase ar-icon-big',
                               'classMini' =>   'ar-icon-usecase ar-icon-small',
                               'extra' =>       '/umlusecase/umlusecase.json'),
        
         'petrinet' => array('id' =>            self::PETRI,
                            'name' =>           'umlsequencepetri',
                            'template' =>       'diagram',
                             //TODO: No icon 
                            'out' =>            'svg',
                            'class' =>          'ar-icon-usecase ar-icon-big',
                            'classMini' =>      'ar-icon-usecase ar-icon-small',
                            'extra' =>          '/petrinets/petrinet.json'
         )
        /*
         'interactionpetrinets' => '/coloredpetrinets/coloredpetrinet.json',
         'timeline' => '/umlactivity/umlactivity.json',
         'umlstate' => '/umlstate/umlstate.json',
         'umlusecase' => '/umlusecase/umlusecase.json',
         'letsdance' => '/letsdance/letsdance.json',
         'treeGraph' => '/treeGraph/treeGraph.json',
         'kmnets' => '/kmnets/kmnets.json',
         'aress' => '/aress/aress.json',
         'b3mn' => '/b3mn/b3mn.json',
         'fmcblockdiagram' => '/fmcblockdiagram/fmcblockdiagram.json',
         'ibpmn' => '/ibpmn/ibpmn.json',
         'trackerworkflow' => '/trackerworkflow/trackerworkflow.json'*/
        );
    
    public function isSelfUser($userProfileId)
    {
       return  ($this->getUserId() === $userProfileId);
    }
     /**
     * true si el documento actual esta compartido para el
     * usuario
     * 
     * @param <int $profileId>
     * @return <boolean> : true si esta compartido
     */
    public function hasShareDoc($profileId)
    {
       return Doctrine_Core::getTable('arDiagram')
             ->hasShareDoc($profileId, $this->getId()); 
    }
    
    public function getPathAndFile($size = 'original')
    {
         return sfConfig::get('app_aToolkit_writable_dir').DIRECTORY_SEPARATOR.'arDiagram'.DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR.$this->getFileName();
    }
    
    public function getBaseName()
    {
        $filenameitems = explode(".", $this->getFileName());
        return $filenameitems[0];
    }
    
    public function getExtension()
    {
        $filenameitems = explode(".", $this->getFileName());
        return $filenameitems[count($filenameitems) - 1];
    }
    /**
     * array con los tipos de diagramas habilitados
     * 
     * @return <array of string>
     */
    public static function getEnabled()
    {
        $editorEnabledList = sfConfig::get( 'app_arquematics_editor_enabled');
   
        $ret = array();
        
        if ($editorEnabledList && (count($editorEnabledList) > 0))
        {
            $diagramKeyList = array_keys(arDiagram::$types);
            $order = 0;
            
            foreach ($editorEnabledList as $editorEnabled)
            {
               if ($editorEnabled && in_array($editorEnabled, $diagramKeyList))
               {
                   //añade el orden
                   $ret[] = array_merge(arDiagram::$types[$editorEnabled], array('order' => $order));
               }
               //si no se puede editar con este modulo
               //no se asigna su orden
               $order++;
            }
            
        }
        /*
        //ordena por el orden en el que estan en app.yml
        if ($ret && (count($ret) > 0))
        {
            usort($ret, function($a, $b) {
                return $a['order'] - $b['order'];
            });
        }*/
        
        return $ret;
    }
    /**
     * mira si es un nombre valido de diagrama
     * 
     * @param <string $name>
     * @return boolean 
     */
    public static function isNameType($name)
    {
        $diagramKeyList = array_keys(arDiagram::$types);
        
        return in_array($name, $diagramKeyList);
    }
    /**
     * 
     * devuelve la informacion para visualizar/editar/mostrar icono del diagrama
     * 
     * @param <string $name>
     * @return <array>
     */
    public static function getTypeByName($name)
    {
       return  isset(arDiagram::$types[$name])?arDiagram::$types[$name]:false;
    }
    
    
    public function getInfo()
    {
      return isset(arDiagram::$types[$this->getType()])?arDiagram::$types[$this->getType()]:array();
    }
    
    /**
     * carga la informacion para mostrar el 
     * pass encriptado del objeto
     * 
     * @param <int $profileId>
     */
    public function loadPass($profileId)
    {
        
        $this->EncContent = Doctrine_Core::getTable('arDiagramEnc')
                ->retrieveByProfileId($this->getId(),$profileId);
    }
    
    
     /**
     * devuelve un array para serializar JSON
     * @return array
     */
    public function getDocInfo($profileId)
    {
        $trash = Doctrine::getTable('arDiagramTrash')
                    ->hasDocProfile($this->getId(), $profileId);
        
        $isFavorite = Doctrine::getTable('arDiagramFavorite')
                        ->hasDocProfile($this->getId(), $profileId);
        
        if (sfConfig::get('app_arquematics_encrypt', false))
        {
          $this->EncContent = Doctrine_Core::getTable('arDiagramEnc')
                                ->retrieveByProfileId($this->getId(),$profileId);  
        
          return array('id' => $this->getGuid(),
                'title'  => $this->getTitle(),
                'created' => $this->getCreatedAt(),
                'updated' => $this->getUpdatedAt(),
                'diagramType' => $this->getType(),
                'json'    => $this->getJson(),
                'isFavorite' => ($isFavorite)? 1:0,
                'trash' => ($trash)? 1:0,
                'dataImage' => $this->getDataImage(),
                //despues de create (POST) o update (PUT) enviar el dato
                //nunca compartimos 0, 
                'share' => 0,
                'pass' => $this->EncContent->getContent());
        }
        else
        {
             return array('id' => $this->getGuid(),
                'title'  => $this->getTitle(), 
                'created' => $this->getCreatedAt(),
                'updated' => $this->getUpdatedAt(),
                'diagramType' => $this->getType(),
                'json'    => $this->getJson(),
                'isFavorite' => ($isFavorite)? 1:0,
                'trash' => ($trash)? 1:0,
                'dataImage' => $this->getDataImage(),
                //despues de create (POST) o update (PUT) enviar el dato
                //nunca compartimos 0, 
                'share' => 0,
                'pass' => false);
        }     
    }
    
    /**
     * lista con las ids para las que ha sido
     * codificado el diagrama
     * 
     * @return <array>
     */
    public function getUserEncodedIds()
    {
        return Doctrine_Core::getTable('arDiagramEnc')
                ->retrieveUserById($this->getId());
    }
    
     public function conditionalDelete($userProfileId)
    {
        $conn = $this->getTable()->getConnection();
        $conn->beginTransaction();
        
        try
        {
            //crea el documento borrado si procede
            if (!Doctrine_Core::getTable('arDiagramDelete')
                        ->hasUserAndDoc($userProfileId, $this->getId(), $conn))
            {
                $arDiagramDelete = new arDiagramDelete();
                $arDiagramDelete->setUserId($userProfileId);
                $arDiagramDelete->setDiagramId($this->getId());
                $arDiagramDelete->save($conn);
            }
            
            $conn->commit();
            
            return true;
        }
        catch (Exception $e)
        {
            $conn->rollBack();
            throw $e;
        
            return false;
        }
       
    }

}