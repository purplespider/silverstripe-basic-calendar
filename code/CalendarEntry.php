<?php

class CalendarEntry extends DataObject{

 	public static $db = array(
 		"Title" => "Text",
 		"Date" => "Date",
 		"Time" => "Text",
 		"Description" => "Text"
 	);
 	
 	static $has_one = array(
 		"CalendarPage" => "CalendarPage",
 		"Image" => "Image"
 	);
	
	public static $summary_fields = array(
    	"Date" => "Date",
    	"Title" => "Title"
    );
	
	static $default_sort = "Date ASC, Time ASC";
	
 	public function validate() {
        $result = parent::validate();
        if(!$this->Title) {
            $result->error('Title is required');
        }
        if(!$this->Date) {
            $result->error('Date is required');
        } 
        return $result;
    }
 	
 	function getCMSFields() {
		
		$datefield = new DateField('Date','Date (DD/MM/YYYY)*');
		$datefield->setConfig('showcalendar', true);
		$datefield->setConfig('dateformat', 'dd/MM/YYYY');
		
		$imagefield = new UploadField('Image','Image');
		$imagefield->allowedExtensions = array('jpg', 'gif', 'png');
		$imagefield->setFolderName("Managed/CalendarImages");
		$imagefield->setCanPreviewFolder(false);
		
		$fields = new FieldList(
			new TextField('Title',"Event Title*"),
			$datefield,
			new TextField('Time',"Time (HH:MM)"),
			new TextareaField('Description'),
			$imagefield
		);
		
		$this->extend('updateCMSFields', $fields);		
		return $fields;		
	}
	
	function getMonthDigit(){
	 	$date = strtotime($this->Date);
		return date('m',$date);
	}
	
	function getYear(){
		$date = strtotime($this->Date);
		return date('Y',$date);
	}
		
	function canCreate($members = null) {
		return true;
	}
	
	function canEdit($members = null) {
		return true;
	}
	
	function canDelete($members = null) {
		return true;
	}
	
	function canView($members = null) {
		return true;
	}
 	
}
?>