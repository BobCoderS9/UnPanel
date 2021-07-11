(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/config/colors", ["Config"], factory);
  } else if (typeof exports !== "undefined") {
    factory(require("Config"));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.Config);
    global.configColors = mod.exports;
  }
})(this, function (_Config) {
  "use strict";

  (0, _Config.set)('colors', {
    red: {
      100: '#ffdbdc',
      200: '#ffbfc1',
      300: '#ffbfc1',
      400: '#ff8589',
      500: '#ff666b',
      600: '#ff4c52',
      700: '#f2353c',
      800: '#e62020',
      900: '#d60b0b'
    },
    pink: {
      100: '#ffd9e6',
      200: '#ffbad2',
      300: '#ff9ec0',
      400: '#ff7daa',
      500: '#ff5e97',
      600: '#f74584',
      700: '#eb2f71',
      800: '#e6155e',
      900: '#d10049'
    },
    purple: {
      100: '#eae1fc',
      200: '#d9c7fc',
      300: '#c8aefc',
      400: '#b693fa',
      500: '#a57afa',
      600: '#9463f7',
      700: '#8349f5',
      800: '#7231f5',
      900: '#6118f2'
    },
    indigo: {
      100: '#e1e4fc',
      200: '#c7cffc',
      300: '#afb9fa',
      400: '#96a3fa',
      500: '#7d8efa',
      600: '#667afa',
      700: '#4d64fa',
      800: '#364ff5',
      900: '#1f3aed'
    },
    blue: {
      100: '#d9e9ff',
      200: '#b8d7ff',
      300: '#99c5ff',
      400: '#79b2fc',
      500: '#589ffc',
      600: '#3e8ef7',
      700: '#247cf0',
      800: '#0b69e3',
      900: '#0053bf'
    },
    cyan: {
      100: '#c2f5ff',
      200: '#9de6f5',
      300: '#77d9ed',
      400: '#54cbe3',
      500: '#28c0de',
      600: '#0bb2d4',
      700: '#0099b8',
      800: '#007d96',
      900: '#006275'
    },
    teal: {
      100: '#c3f7f2',
      200: '#92f0e6',
      300: '#6be3d7',
      400: '#45d6c8',
      500: '#28c7b7',
      600: '#17b3a3',
      700: '#089e8f',
      800: '#008577',
      900: '#00665c'
    },
    green: {
      100: '#c2fadc',
      200: '#99f2c2',
      300: '#72e8ab',
      400: '#49de94',
      500: '#28d17c',
      600: '#11c26d',
      700: '#05a85c',
      800: '#008c4d',
      900: '#006e3c'
    },
    'light-green': {
      100: '#dcf7b0',
      200: '#c3e887',
      300: '#add966',
      400: '#94cc39',
      500: '#7eb524',
      600: '#6da611',
      700: '#5a9101',
      800: '#4a7800',
      900: '#3a5e00'
    },
    yellow: {
      100: '#fff6b5',
      200: '#fff39c',
      300: '#ffed78',
      400: '#ffe54f',
      500: '#ffdc2e',
      600: '#ffcd17',
      700: '#fcb900',
      800: '#faa700',
      900: '#fa9600'
    },
    orange: {
      100: '#ffe1c4',
      200: '#ffc894',
      300: '#fab06b',
      400: '#fa983c',
      500: '#f57d1b',
      600: '#eb6709',
      700: '#de4e00',
      800: '#b53f00',
      900: '#962d00'
    },
    brown: {
      100: '#f5e2da',
      200: '#e0cdc5',
      300: '#cfb8b0',
      400: '#bda299',
      500: '#ab8c82',
      600: '#997b71',
      700: '#82675f',
      800: '#6b534c',
      900: '#57403a'
    },
    grey: {
      100: '#fafafa',
      200: '#eeeeee',
      300: '#e0e0e0',
      400: '#bdbdbd',
      500: '#9e9e9e',
      600: '#757575',
      700: '#616161',
      800: '#424242'
    },
    'blue-grey': {
      100: '#f3f7f9',
      200: '#e4eaec',
      300: '#ccd5db',
      400: '#a3afb7',
      500: '#76838f',
      600: '#526069',
      700: '#37474f',
      800: '#263238'
    }
  });
});