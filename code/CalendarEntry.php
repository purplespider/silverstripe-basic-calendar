<?php

class CalendarEntry extends DataObject
{

    public static $db = array(
        "Title" => "Text",
        "Date" => "Date",
        "Time" => "Text",
        "Location" => "Varchar(255)",
        "Description" => "Text",
        "Teaser" => "HTMLText",
        "Content" => "HTMLText",
        "Featured" => "Boolean",
    );

    public static $has_one = array(
        "CalendarPage" => "CalendarPage",
        "Image" => "Image"
    );

    public static $summary_fields = array(
        "Date" => "Date",
        "Title" => "Title"
    );

    public static $default_sort = "Date ASC, Time ASC";

    public function validate()
    {
        $result = parent::validate();
        if (!$this->Title) {
            $result->error('Title is required');
        }
        if (!$this->Date) {
            $result->error('Date is required');
        }
        return $result;
    }

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            $datefield = new DateField('Date', 'Date (DD/MM/YYYY)*');
            $datefield->setConfig('showcalendar', true);
            $datefield->setConfig('dateformat', 'dd/MM/YYYY');

            $imagefield = new UploadField('Image', 'Image');
            $imagefield->allowedExtensions = array('jpg', 'gif', 'png');
            $imagefield->setFolderName("Managed/CalendarImages");
            $imagefield->setCanPreviewFolder(false);

            $fields->addFieldToTab('Root.Main', new TextField('Title', "Event Title*"));
            $fields->addFieldToTab('Root.Main', $datefield);
            $fields->addFieldToTab('Root.Main', new TextField('Time', "Time (HH:MM)"));
            $fields->addFieldToTab('Root.Main', new TextField('Location'));
            $fields->addFieldToTab('Root.Main', new TextareaField('Description'));
            $fields->addFieldToTab('Root.Main', new CheckboxField('Featured', 'Featured on the Homepage'));
            $fields->addFieldToTab('Root.Main', HTMLEditorField::create('Teaser')->setRows('3'));
            $fields->addFieldToTab('Root.Main', new HTMLEditorField('Content'));
            $fields->addFieldToTab('Root.Main', $imagefield);
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

    public function getYear()
    {
        $date = strtotime($this->Date);

        return date('Y', $date);
    }

    public function canCreate($members = null)
    {
        $extended = $this->extendedCan(__FUNCTION__, $members);
        if ($extended !== null) {
            return $extended;
        }

        return true;
    }

    public function canEdit($members = null)
    {
        $extended = $this->extendedCan(__FUNCTION__, $members);
        if ($extended !== null) {
            return $extended;
        }

        return true;
    }

    public function canDelete($members = null)
    {
        $extended = $this->extendedCan(__FUNCTION__, $members);
        if ($extended !== null) {
            return $extended;
        }

        return true;
    }

    public function canView($members = null)
    {
        $extended = $this->extendedCan(__FUNCTION__, $members);
        if ($extended !== null) {
            return $extended;
        }

        return true;
    }
}
