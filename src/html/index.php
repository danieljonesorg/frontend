<?php
/*
 * Author: Jaisen Mathai <jaisen@jmathai.com>
 * Front controller for OpenPhoto.
 * This file takes all requests and dispatches them to the appropriate controller.
 */

// TODO, remove this
date_default_timezone_set('America/Los_Angeles');

$basePath = dirname(dirname(__FILE__));
$epiPath = "{$basePath}/libraries/external/epi";
require "{$epiPath}/Epi.php";
Epi::setPath('base', $epiPath);
Epi::setPath('config', "{$basePath}/configs");
Epi::setPath('view', "{$basePath}/views");
//Epi::setSetting('exceptions', true);
Epi::init('api','config','route','template');


getConfig()->load('defaults.ini');
$configFile = Epi::getPath('config').'/generated/settings.ini';
if(file_exists($configFile))
{
  getConfig()->load('generated/settings.ini');
  // load all dependencies
  require getConfig()->get('paths')->libraries . '/dependencies.php';
  getRoute()->run();
}
elseif(!file_exists($configFile))
{

  $baseDir = dirname(dirname(__FILE__));
  $paths = new stdClass;
  $paths->libraries = "{$baseDir}/libraries";
  $paths->controllers = "{$baseDir}/libraries/controllers";
  $paths->external = "{$baseDir}/libraries/external";
  $paths->adapters = "{$baseDir}/libraries/adapters";
  $paths->models = "{$baseDir}/libraries/models";
  getConfig()->set('paths', $paths);
  require getConfig()->get('paths')->libraries . '/dependencies.php';
  require getConfig()->get('paths')->libraries . '/routes-setup.php';
  require getConfig()->get('paths')->controllers . '/SetupController.php';
  getRoute()->run('/setup');
}
