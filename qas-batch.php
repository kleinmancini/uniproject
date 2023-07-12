<?php
class BulkSearchItem{
    var $fAddress;
    var $sVerifyLevel;
    var $sInputAddress;

    function BulkSearchItem($result){
        $this->sVerifyLevel = $result->VerifyLevel;
        $this->sInputAddress = $result->InputAddress;
    }
}
class BulkSearchResult{
    function BulkSearchResult($result){
		if (is_array($result->BulkAddress)){
			foreach ($result->BulkAddress AS $tBulkSearchItem){
				$this->bulkSearchItems[] = new BulkSearchItem($tBulkSearchItem);
			}
		}
	}
}
class QuickAddress{
    var $soap = NULL;
    function QuickAddress(){
        $this->soap=new SoapClient("http://sbqasdat.bskyb.com:2021/proweb.wsdl", array('exceptions' => 0));
    }
    function bulkSearch($asSearch){
		$this->sEngineType = "Verification";
        $this->sDataSetID = "GBR";
        $sSearchString   = "";
        $aEngineOptions = array(
            "_"       => $this->sEngineType,
            //"Flatten" => $this->bFlatten
		);
        # Build main search arguments
        $args = array(
            "Country" => $this->sDataSetID,
            "Engine"  => $aEngineOptions
		);

        if ($asSearch != ""){
            $asSearchTerm=array();

            $asSearchTerm["Search"] =$asSearch;
            $args["BulkSearchTerm"] = $asSearchTerm;
        }
        return (new BulkSearchResult($this->soap->DoBulkSearch($args)));
    }
} 

class bulkCheckAddresses{

	private $output=NULL;
	private $red = 0;
	private $orange = 0;
	private $green = 0;
	
	public function checkBulkAddress($sUserInput){
		set_time_limit(0);
		$asSplitInput=preg_split('/[\\r\\n\\\\]+/', trim($sUserInput));
		try{
			$qas=new QuickAddress();
			$result=$qas->bulkSearch($asSplitInput);
			$this->output .= '<br/>';
		}
		catch( Exception $e ){
			$sErrorInfo=$qas->getFaultString($sErrorInfo) . "<br />" . $qas->getSoapFault()->ErrorDetail;
		}
		foreach ($result->bulkSearchItems AS $item){
			if(strstr($item->sInputAddress, "ROI")){
				$this->output .= '<div class="batg">'.$item->sInputAddress.' - <b>ROI Address Found - Validation Skipped</b></div>';
				$this->green++;
			}
			else{
				switch ($item->sVerifyLevel) {
					case "Verified":
						$this->output .= '<div class="batg">'.$item->sInputAddress.' - <b>Verified!</b></div>';
						$this->green++;
					break;
					case "InteractionRequired":
						$this->output .= '<div class="batg">'.$item->sInputAddress.' - <b>Verified - QAS not 100% Confident!</b></div>';
						$this->green++;
					break;
					case "StreetPartial":
						$this->output .= '<div class="bato">'.$item->sInputAddress.' - <b>StreetPartial!</b></div>';
						$this->orange++;
					break;
					case "PremisesPartial":
						$this->output .= '<div class="bato">'.$item->sInputAddress.' - <b>PremisesPartial!</b></div>';
						$this->orange++;
					break;
					case "Multiple":
						$this->output .= '<div class="bato">'.$item->sInputAddress.' - <b>Multiple Addresses Returned!</b></div>';
						$this->orange++;
					break;
					case "None":
						$this->output .= '<div class="batr">'.$item->sInputAddress.' - <b>Returned No Results!</b></div>';
						$this->red++;
					break;
					default:
						$this->output .= '<div class="batr">'.$item->sInputAddress.' - <b>UNEXPECTED ERROR!</b></div>';
						$this->red++;
				}
			}
		}
		$this->output = substr($this->output, 5);
	}
	public function returnOutput(){
		return $this->output;
	}
	public function returnGreen(){
		return $this->green;
	}
	public function returnOrange(){
		return $this->orange;
	}
	public function returnRed(){
		return $this->red;
	}

	public function failedToValidate(){
		if(($this->red + $this->orange) > 0){
			return true;
		}
		else{
			return false;
		}
	}
}
?>