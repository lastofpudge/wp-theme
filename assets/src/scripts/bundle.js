require('./core.js');


$(function() {
    "use strict";

    function App() {}

    App.prototype = {
        init: function() {
            //svg init
            svg4everybody();
            // aos animate
            AOS.init({
                disable: 'mobile'
            });
        },

    };

    const app = new App();

    app.init();

});
