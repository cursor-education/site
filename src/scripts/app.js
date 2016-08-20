import angular from 'angular';

import 'angular-animate';
import 'angular-aria';
import 'angular-material';

// import AppController from './AppController';
// import Users from 'src/users/Users';

export default angular.module('app', ['ngMaterial'] )
  .config(($mdIconProvider, $mdThemingProvider) => {
    $mdThemingProvider.theme('default')
      .primaryPalette('brown')
      .accentPalette('red');
  })
  .controller('AppController', AppController);
