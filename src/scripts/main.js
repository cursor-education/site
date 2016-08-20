// require('file?name=[name].[ext]!../index.html');

import angular from 'angular';
import App from './app';

/**
 * Manually bootstrap the application when AngularJS and
 * the application classes have been loaded.
 */
angular
  .element(document)
  .ready(function() {
    angular
      .module('app-bootstrap', [])
      .run(() => {
        console.log(`Running the 'app-bootstrap'`);
      });

    let body = document.getElementsByTagName("body")[0];
    angular.bootstrap(body, ['app-bootstrap']);
  });
