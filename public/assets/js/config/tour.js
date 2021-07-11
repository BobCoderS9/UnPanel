(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/config/tour", ["Config"], factory);
  } else if (typeof exports !== "undefined") {
    factory(require("Config"));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.Config);
    global.configTour = mod.exports;
  }
})(this, function (_Config) {
  "use strict";

  (0, _Config.set)('tour', {
    steps: [{
      element: '#toggleMenubar',
      position: 'right',
      intro: 'Offcanvas Menu <p class=\'content\'>It is nice custom navigation for desktop users and a seek off-canvas menu for tablet and mobile users</p>'
    }, {
      element: '#toggleFullscreen',
      intro: 'Full Screen <p class=\'content\'>Click this button you can view the admin template in full screen</p>'
    }, {
      element: '#toggleChat',
      position: 'left',
      intro: 'Quick Conversations <p class=\'content\'>This is a sidebar dialog box for user conversations list, you can even create a quick conversation with other users</p>'
    }],
    skipLabel: '<i class=\'wb-close\'></i>',
    doneLabel: '<i class=\'wb-close\'></i>',
    nextLabel: 'Next <i class=\'wb-chevron-right-mini\'></i>',
    prevLabel: '<i class=\'wb-chevron-left-mini\'></i>Prev',
    showBullets: false
  });
});