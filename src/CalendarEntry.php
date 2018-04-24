<?php

namespace PurpleSpider\SilverStripe\BasicCalendar;

use SilverStripe\ORM\FieldType\DBDate;
use PurpleSpider\SilverStripe\BasicCalendar\CalendarPage;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\FileHandleField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\ORM\DataObject;
use SilverStripe\AssetAdmin\Forms\UploadField;

class CalendarEntry extends DataObject
{

    private static $db = [
        "Title" => "Text",
        "Date" => DBDate::class,
        "Time" => "Varchar(100)",
        "Description" => "Text",
    ];

    private static $has_one = [
        "CalendarPage" => CalendarPage::class,
        "Image" => Image::class,
    ];

    private static $summary_fields = [
        "niceDate" => "Date & Time",
        "Title" => "Title",
        "ImageCMSThumb" => "Image",
    ];
    
    private static $table_name = 'CalendarEntry';

    private static $default_sort = "Date ASC, Time ASC";

    public function validate()
    {
        $result = parent::validate();
        if (!$this->Title) {
            $result->addError('Title is required');
        }
        if (!$this->Date) {
            $result->addError('Date is required');
        }
        return $result;
    }

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            $datefield = DateField::create('Date', 'Date');
            // $datefield->setConfig('showcalendar', true);
            // $datefield->setConfig('dateformat', 'dd/MM/yyyy');
            
            $imageField = UploadField::create('Image', 'Image');
            $imageField->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
            $imageField->setFolderName('Managed/CalendarImages');
            $fields->addFieldToTab('Root.Main', $imageField, "Content");

            $fields->addFieldToTab('Root.Main', new TextField('Title', "Event Title"));
            $fields->addFieldToTab('Root.Main', $datefield);
            $fields->addFieldToTab('Root.Main', TextField::create('Time', "Time"));
            $fields->addFieldToTab('Root.Main', new TextareaField('Description'));
            $fields->addFieldToTab('Root.Main', $imageField);
        });

        $fields = parent::getCMSFields();

        $this->extend('updateCMSFields', $fields);

        $fields->removeFieldFromTab("Root.Main", "CalendarPageID");

        return $fields;
    }

    public function getMonthDigit()
    {
        $date = strtotime($this->Date);

        return date('m', $date);
    }
    
    public function niceDate()
    {
      $dateFormat = $this->obj('Date')->Format('d MMMM y, eee');
      
      if ($this->Time) {
        return $dateFormat.", ".$this->Time;
      }
      
      return $dateFormat;
    }
    
    public function ImageCMSThumb()
    {
      return $this->ImageID ? $this->Image()->Fit(50,20) : "";
    }
    
    public function Link()
    {
      return $this->CalendarPage()->Link()."#".$this->ID;
    }

    public function getYear()
    {
        $date = strtotime($this->Date);

        return date('Y', $date);
    }

    public function canCreate($members = null, $context = array())
    {
        $extended = $this->extendedCan(__FUNCTION__, $members);
        if ($extended !== null) {
            return $extended;
        }

        return true;
    }

    public function canEdit($members = null, $context = array())
    {
        $extended = $this->extendedCan(__FUNCTION__, $members);
        if ($extended !== null) {
            return $extended;
        }

        return true;
    }

    public function canDelete($members = null, $context = array())
    {
        $extended = $this->extendedCan(__FUNCTION__, $members);
        if ($extended !== null) {
            return $extended;
        }

        return true;
    }

    public function canView($members = null, $context = array())
    {
        $extended = $this->extendedCan(__FUNCTION__, $members);
        if ($extended !== null) {
            return $extended;
        }

        return true;
    }
}