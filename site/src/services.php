<?php
use app\models\Config as ConfigModel;

use app\models\Course as CourseModel;
use app\models\Workshop as WorkshopModel;
use app\models\Technologies as TechnologiesModel;
use app\models\CoursePlan as CoursePlanModel;
use app\models\CoursesTechnologies as CoursesTechnologiesModel;
use app\models\Partner as PartnersModel;
use app\models\StudentsCompanies as StudentsCompaniesModel;
use app\models\Pages as PagesModel;
use app\models\Teachers as TeachersModel;
use app\models\TeachersCourses as TeachersCoursesModel;
use app\models\TeachersLinks as TeachersLinksModel;
use app\models\Redirects as RedirectsModel;
use app\helpers\HostService as HostService;
use app\helpers\EmailService as EmailService;
use app\helpers\OrderService as OrderService;
use app\helpers\SmsService as SmsService;

// 
$app['email.service'] = $app->share(function () use ($app) {
    return new EmailService($app);
});

// 
$app['order.service'] = $app->share(function () use ($app) {
    return new OrderService($app);
});

// 
$app['sms.service'] = $app->share(function () use ($app) {
    return new SmsService($app);
});

// 
$app['host.service'] = $app->share(function () use ($app) {
    return new HostService($app);
});

// @service for Config model
$app['config.model'] = $app->share(function () use ($app) {
    return new ConfigModel($app);
});

// @service for Workshops model
$app['workshops.model'] = $app->share(function () use ($app) {
    return new WorkshopModel($app);
});

// @service for Courses model
$app['courses.model'] = $app->share(function () use ($app) {
    return new CourseModel($app);
});

$app['coursesTechnologies.model'] = $app->share(function () use ($app) {
    return new CoursesTechnologiesModel($app);
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

// @service for TeachersCourses model
$app['teachersCourses.model'] = $app->share(function () use ($app) {
    return new TeachersCoursesModel($app);
});

// @service for TeachersLinks model
$app['teachersLinks.model'] = $app->share(function () use ($app) {
    return new TeachersLinksModel($app);
});

// @service for Redirects model
$app['redirects.model'] = $app->share(function () use ($app) {
    return new RedirectsModel($app);
});