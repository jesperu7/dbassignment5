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
		
		$elements = $this->xpath->query('/SkierLogs/Clubs/Club');	//Gets list of elements with xPath
		
		foreach($elements as $element){	//Loops through all elements
			$xElement = $element->getElementsByTagName("Name");		//Looks for "name" nodes
			$valueOfName = $xElement->item(0)->nodeValue;			//saves value of node
			
			$xElement = $element->getElementsByTagName("City");
			$valueOfCity = $xElement->item(0)->nodeValue;
			
			$xElement = $element->getElementsByTagName("County");
			$valueOfCounty = $xElement->item(0)->nodeValue;
			
			$nodeID = $element->getAttribute('id');	// Gets value of element attribute
			
			$tmp = new Club($nodeID, $valueOfName, $valueOfCity, $valueOfCounty);	//creates a temporary Club object
			array_push($clubs, $tmp);	//adds object to array
			
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
    
	$elements = $this->xpath->query('/SkierLogs/Skiers/Skier');	//Gets list of elements with xPath
		
		foreach($elements as $element){ //Loops through all elements
			$xElement = $element->getElementsByTagName("FirstName"); //Looks for "FirstName" nodes
			$valueOfFName = $xElement->item(0)->nodeValue;	//saves value of node
			
			$xElement = $element->getElementsByTagName("LastName");
			$valueOfLName = $xElement->item(0)->nodeValue;
			
			$xElement = $element->getElementsByTagName("YearOfBirth");
			$valueOfBirthyear = $xElement->item(0)->nodeValue;
			
			$nodeuserName = $element->getAttribute('userName'); // Gets value of element attribute
		
			$tmp = new Skier($nodeuserName, $valueOfFName, $valueOfLName, $valueOfBirthyear);	//creates a temporary Skier object
			
			$seasons = $this->xpath->query('/SkierLogs/Season');	//Gets lists of Season elements
			
			foreach ($seasons as $season) { //loops through seasons
				foreach ($season->getElementsByTagName("Skiers") as $aElement) { //loops through skiers
       
					foreach ($aElement->getElementsByTagName("Skier") as $skierElement) { //loops through skier
						if ($skierElement->getAttribute('userName') == $nodeuserName){	//finds correct user
							if($aElement->hasAttribute('clubId')){	//excludes user if it's not in a club
								$affiliation = new Affiliation($aElement->getAttribute('clubId'), $season->getAttribute('fallYear'));	//creates temporary affiliation object
								$tmp->addAffiliation($affiliation);	//adds affiliation to the temporary skier object
							}
							foreach($skierElement->getElementsByTagName('Log') as $log) { // loops thorug the logs
								$distance = array();
                    
								foreach ($log->getElementsByTagName('Entry') as $entry) { //loops through entrys
									$xDistance = $entry->getElementsByTagName("Distance");	//gets distance node
									$distance[] = $xDistance->item(0)->nodeValue;	//adds value of node to an array
								}
							}
                
							$tmp->addYearlyDistance($tmpSeason = new YearlyDistance($season->getAttribute('fallYear'),array_sum($distance))); //adds distance to skier object
						}
					}
				}
			}
			
			array_push($skiers, $tmp);	//pushes skier to the array
		}
        // TODO: Implement the function retrieving skier information,
        //       including affiliation history and logged yearly distances.
        return $skiers;
    }
}

?>