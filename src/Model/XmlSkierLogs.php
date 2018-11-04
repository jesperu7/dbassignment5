<?php
/**
  * This file is a part of the code used in IMT2571 Assignment 5.
  *
  * @author Rune Hjelsvold
  * @version 2018
  */

require_once('Club.php');
require_once('Skier.php');
require_once('YearlyDistance.php');
require_once('Affiliation.php');



/**
  * The class for accessing skier logs stored in the XML file
  */  
class XmlSkierLogs
{
    /**
      * @var DOMDocument The XML document holding the club and skier information.
      */  
    protected $doc;
    
    /**
      * @param string $url Name of the skier logs XML file.
      */  
    public function __construct($url)
    {
        $this->doc = new DOMDocument();
        $this->doc->load($url);
		//$root = $this->doc->documentElement;
		
		$this->xpath = new DOMXpath($this->doc);
    }
    
    /**
      * The function returns an array of Club objects - one for each
      * club in the XML file passed to the constructor.
      * @return Club[] The array of club objects
      */
    public function getClubs()
    {
        $clubs = array();
		
		$elements = $this->xpath->query('/SkierLogs/Clubs/Club');
		
		foreach($elements as $element){
			$xElement = $element->getElementsByTagName("Name");
			$valueOfName = $xElement->item(0)->nodeValue;
			
			$xElement = $element->getElementsByTagName("City");
			$valueOfCity = $xElement->item(0)->nodeValue;
			
			$xElement = $element->getElementsByTagName("County");
			$valueOfCounty = $xElement->item(0)->nodeValue;
			
			$nodeID = $element->getAttribute('id');
			
			$tmp = new Club($nodeID, $valueOfName, $valueOfCity, $valueOfCounty);
			array_push($clubs, $tmp);
			
		}
		
        
        // TODO: Implement the function retrieving club information
        return $clubs;
    }

    /**
      * The function returns an array of Skier objects - one for each
      * Skier in the XML file passed to the constructor. The skier objects
      * contains affiliation histories and logged yearly distances.
      * @return Skier[] The array of skier objects
      */
    public function getSkiers()
    {
        $skiers = array();
    
        // TODO: Implement the function retrieving skier information,
        //       including affiliation history and logged yearly distances.
        return $skiers;
    }
}

?>