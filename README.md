# Basic Calendar Module
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/purplespider/silverstripe-basic-calendar/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/purplespider/silverstripe-basic-calendar/?branch=master)

## Introduction

Provides basic "calendar page" functionality to a SilverStripe site. 

Designed to provide a simple, fool-proof way for users to add calendar events to a page on their website.

This module has been designed to have just the minimum required features, to avoid bloat, but can be easily extended to add new fields if required.

## Maintainer Contact ##
 * James Cocker (ssmodulesgithub@pswd.biz)
 
## Requirements
 * Silverstripe 4.4+
 * Use the 1.0 branch for SilverStripe 3.1 support
 
## Installation Instructions

1. `composer require purplespider/basic-calendar` 
3. Log in the CMS, and create a new Calendar Page page.

## Features

* An Event has a Title, Date, Time, Description & Image
* GridField to manage events
* Option to make the Manage Events tab the default fro the page
* Option to also display events from other Calendar Pages - So you can have several mini calendars, and a main one.
* Link for users to view Past or Future events.
* Past Events are displayed on a separate CMS tab, so they stay out of your way.