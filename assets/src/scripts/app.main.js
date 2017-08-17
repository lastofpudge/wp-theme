require('./core.js');


$(function() {
    "use strict";

    function App() {}

    App.prototype = {
        init: function() {
            //svg init
            svg4everybody();
            // aos amnimate
            AOS.init({
                disable: 'mobile'
            });
        },

    };

    var app = new App();

    app.init();

});
