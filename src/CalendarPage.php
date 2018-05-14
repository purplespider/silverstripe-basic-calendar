<?php

namespace PurpleSpider\BasicCalendar;

use Page;
use SilverStripe\ORM\FieldType\DBBoolean;
use PurpleSpider\BasicCalendar\CalendarEntry;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Control\Director;
use SilverStripe\View\Requirements;
use SilverStripe\ORM\GroupedList;
use PageController;

class CalendarPage extends Page
{

    private static $description = "Provides an interface to add calendar events";

    private static $db = array(
        "EventTabFirst" => DBBoolean::class,
        "ManageAllEvents" => DBBoolean::class
    );

    private static $has_many = array(
        "Events" => CalendarEntry::class
    );
    
    private static $table_name = 'CalendarPage';
    
    private static $icon = 'purplespider/basic-calendar:client/dist/images/date-file.gif';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        if ($this->EventTabFirst) $fields->insertBefore(new Tab('Events'), 'Main');

        $config = GridFieldConfig_RecordEditor::create();
        $config->getComponentByType(GridFieldSortableHeader::class)->setFieldSorting([
            'niceDate' => 'Date',
        ]);
        $gridField = new GridField("Events", "Upcoming Events", $this->Events()->where("Date >= CURRENT_DATE OR Date IS NULL"), $config);
        $fields->addFieldToTab("Root.Events", $gridField);

        $config = GridFieldConfig_RecordEditor::create();
        $config->removeComponentsByType(GridFieldAddNewButton::class);
        $gridField = new GridField("PastEvents", "Past Events", $this->Events()->where("Date < CURRENT_DATE"), $config);
        $fields->addFieldToTab("Root.PastEvents", $gridField);
        
        

        

        return $fields;
    }
    
    function getSettingsFields() {
        $fields = parent::getSettingsFields();
        $fields->addFieldToTab("Root.Settings", new CheckboxField("EventTabFirst", "CMS: Set Events Tab as Default"));
        $fields->addFieldToTab("Root.Settings", new CheckboxField("ManageAllEvents", "Template: Display Events from other pages too"));
        return $fields;
    }
}

class CalendarPageController extends PageController
{

    private static $allowed_actions = array();

    public function getEvents($dates = "all", $order = "normal")
    {
        $where = null;
        $sortOrder = null;
        $filter = array();

        if ($dates == "future") {
            $where = "Date >= CURRENT_DATE OR Date IS NULL";
        } elseif ($dates == "past") {
            $where = "Date < CURRENT_DATE";
        }
        
        if ($order == "normal") {
          $sortOrder = "Date, Time";
        } else if ($order == "reverse") {
          $sortOrder = "Date DESC, Time";
        }

        if (!$this->ManageAllEvents) {
            $filter =  array("CalendarPageID"=>$this->ID);
        }
        return GroupedList::create(CalendarEntry::get()->filter($filter)->Sort($sortOrder)->where($where));
    }

    public function ShowPast()
    {
        return isset($_GET['past']);
    }

    // THIS PAGE'S ENTRIES
    public function getFutureCalendarEntries()
    {
        $entries = GroupedList::create(CalendarEntry::get()->filter(array("CalendarPageID"=>$this-ID))->Sort('Date, Time')->where("Date >= CURRENT_DATE OR Date IS NULL"));

        return $entries;
    }

    public function getGroupedPastCalendarEntries()
    {
        $entries = GroupedList::create(CalendarEntry::get()->filter(array("CalendarPageID"=>$this-ID))->Sort('Date, Time')->where("Date < CURRENT_DATE"));

        return $entries;
    }

    public function getGroupedCalendarEntries()
    {
        $entries = GroupedList::create(CalendarEntry::get()->filter(array("CalendarPageID"=>$this-ID))->Sort('Date, Time'));

        return $entries;
    }

    // ALL ENTRIES - FROM ALL PAGES
    public function getAllGroupedFutureCalendarEntries()
    {
        $entries = GroupedList::create(CalendarEntry::get()->Sort('Date, Time')->where("Date >= CURRENT_DATE OR Date IS NULL"));

        return $entries;
    }

    public function getAllGroupedPastCalendarEntries()
    {
        $entries = GroupedList::create(CalendarEntry::get()->Sort('Date, Time')->where("Date < CURRENT_DATE"));

        return $entries;
    }

    public function getAllGroupedCalendarEntries()
    {
        $entries = GroupedList::create(CalendarEntry::get()->Sort('Date, Time'));

        return $entries;
    }
}
