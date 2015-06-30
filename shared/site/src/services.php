<?php
use app\models\Config as ConfigModel;

use app\models\Course as CourseModel;
use app\models\Technologies as TechnologiesModel;
use app\models\CoursePlan as CoursePlanModel;
use app\models\Partner as PartnersModel;
use app\models\StudentsCompanies as StudentsCompaniesModel;
use app\models\Pages as PagesModel;
use app\models\Teachers as TeachersModel;

// @service for Config model
$app['config.model'] = $app->share(function () use ($app) {
    return new ConfigModel($app);
});

// @service for Courses model
$app['courses.model'] = $app->share(function () use ($app) {
    return new CourseModel($app);
});

// @service for Technologies model
$app['technologies.model'] = $app->share(function () use ($app) {
    return new TechnologiesModel($app);
});

// @service for Courses model
$app['coursesPlan.model'] = $app->share(function () use ($app) {
    return new CoursePlanModel($app);
});

// @service for Partners model
$app['partners.model'] = $app->share(function () use ($app) {
    return new PartnersModel($app);
});

// @service for StudentsCompanies model
$app['studentsCompanies.model'] = $app->share(function () use ($app) {
    return new StudentsCompaniesModel($app);
});

// @service for Pages model
$app['pages.model'] = $app->share(function () use ($app) {
    return new PagesModel($app);
});

// @service for Teachers model
$app['teachers.model'] = $app->share(function () use ($app) {
    return new TeachersModel($app);
});