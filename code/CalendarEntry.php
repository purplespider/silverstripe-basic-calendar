<?php

class CalendarEntry extends DataObject
{

    public static $db = array(
        "Title" => "Text",
        "Date" => "Date",
        "Time" => "Text",
        "Description" => "Text"
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

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        // add the new fields in
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                TextField::create('Title', "Event Title*"),
                DateField::create('Date', 'Date (DD/MM/YYYY)*')
                    ->setConfig('showcalendar', true)
                    ->setConfig('dateformat', 'dd/MM/YYYY'),
                TextField::create('Time', "Time (HH:MM)"),
                TextareaField::create('Description'),

                UploadField::create('Image', 'Image')
                    ->setAllowedExtensions(array('jpg', 'gif', 'png'))
                    ->setFolderName("Managed/CalendarImages")
                    ->setCanPreviewFolder(false)
            )
        );

        $fields->removeFieldFromTab('Root.Main', 'CalendarPageID');

        $this->extend('updateCalendarCMSFields', $fields);

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
