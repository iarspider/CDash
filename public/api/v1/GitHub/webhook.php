<?php
/*=========================================================================
  Program:   CDash - Cross-Platform Dashboard System
  Module:    $Id$
  Language:  PHP
  Date:      $Date$
  Version:   $Revision$

  Copyright (c) Kitware, Inc. All rights reserved.
  See LICENSE or http://www.cdash.org/licensing/ for details.

  This software is distributed WITHOUT ANY WARRANTY; without even
  the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
  PURPOSE. See the above copyright notices for more information.
=========================================================================*/

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/config/config.php';
require_once 'include/api_common.php';

use CDash\Model\Repository;

init_api_request();

$event = array_key_exists('HTTP_X_GITHUB_EVENT', $_SERVER) ?  $_SERVER['HTTP_X_GITHUB_EVENT'] : '';

switch ($event) {
    case 'check_run':
        // Avoid an infinite loop of reacting to our own activity.
        if ($_REQUEST['check_run']['name'] != 'CDash' ||
                $_REQUEST['action'] == 'rerequested') {
            $sha = $_REQUEST['check_run']['head_sha'];
            Repository::createOrUpdateCheck($sha);
        }
        break;

    case 'status':
        $sha = $_REQUEST['sha'];
        Repository::createOrUpdateCheck($sha);
        break;

    default:
        break;
}