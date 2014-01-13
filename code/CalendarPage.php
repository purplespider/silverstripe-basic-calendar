<?php

class CalendarPage extends Page {
	
	static $description = "Provides an interface to add calendar events";
	
	public static $db = array(
		"EventTabFirst" => "Boolean",
		"ManageAllEvents" => "Boolean"
	);
	
	public static $has_many = array(
		"Events" => "CalendarEntry"
	);
	
	static $icon = 'basic-calendar/images/date';
	
	function getCMSFields() {
		$fields = parent::getCMSFields();

		if ($this->EventTabFirst) {
			$fields->insertBefore(new Tab('Events'), 'Main');
		}
		
		$config = GridFieldConfig_RecordEditor::create();
		$gridField = new GridField("Events", "Upcoming Events", $this->Events()->where("Date >= CURRENT_DATE OR Date IS NULL"), $config);
		$fields->addFieldToTab("Root.Events", $gridField);
		
		$config = GridFieldConfig_RecordEditor::create();
		$config->removeComponentsByType('GridFieldAddNewButton');
		$gridField = new GridField("PastEvents", "Past Events", $this->Events()->where("Date < CURRENT_DATE"), $config);
		$fields->addFieldToTab("Root.PastEvents", $gridField);
		
		$fields->addFieldToTab("Root.Main", new CheckboxField("EventTabFirst","CMS: Set Events Tab as Default"));
		$fields->addFieldToTab("Root.Main", new CheckboxField("ManageAllEvents","Template: Display Events from other pages too"));
				
		return $fields;
	}

}

class CalendarPage_Controller extends Page_Controller {

	public static $allowed_actions = array();
	
	public function init() {
		if (Director::fileExists(project() . "/css/calendar.css")) {
			Requirements::css(project() . "/css/calendar.css");
		} else {
			Requirements::css("basic-calendar/css/calendar.css");
		}
		parent::init();
    }

	function getEvents($dates = "all") {
		$where = null;
		$filter = array();
		
		if ($dates == "future") {
			$where = "Date >= CURRENT_DATE OR Date IS NULL";
		} else if ($dates == "past") {
			$where = "Date < CURRENT_DATE";
		}
		
		if (!$this->ManageAllEvents) {
			$filter =  array("CalendarPageID"=>$this->ID);
		}
		return GroupedList::create(CalendarEntry::get()->filter( $filter )->Sort('Date, Time')->where($where) );
	}
	
	function ShowPast() {
		return isset($_GET['past']) ? true : false;
	}
	
	// THIS PAGE'S ENTRIES
	function getFutureCalendarEntries() {
		$entries = GroupedList::create(CalendarEntry::get()->filter( array("CalendarPageID"=>$this-ID) )->Sort('Date, Time')->where("Date >= CURRENT_DATE OR Date IS NULL") );
		return $entries;
	}
	
	function getGroupedPastCalendarEntries() {
		$entries = GroupedList::create(CalendarEntry::get()->filter( array("CalendarPageID"=>$this-ID) )->Sort('Date, Time')->where("Date < CURRENT_DATE") );
		return $entries;
	}
	
	function getGroupedCalendarEntries() {
		$entries = GroupedList::create(CalendarEntry::get()->filter( array("CalendarPageID"=>$this-ID) )->Sort('Date, Time') );
		return $entries;
	}
	
	// ALL ENTRIES - FROM ALL PAGES
	function getAllGroupedFutureCalendarEntries() {
		$entries = GroupedList::create(CalendarEntry::get()->Sort('Date, Time')->where("Date >= CURRENT_DATE OR Date IS NULL") );
		return $entries;
	}
	
	function getAllGroupedPastCalendarEntries() {
		$entries = GroupedList::create(CalendarEntry::get()->Sort('Date, Time')->where("Date < CURRENT_DATE") );
		return $entries;
	}
	
	function getAllGroupedCalendarEntries() {
		$entries = GroupedList::create(CalendarEntry::get()->Sort('Date, Time') );
		return $entries;
	}
 
}

?>