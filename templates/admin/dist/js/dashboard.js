/**
 *
 * Dashboard
 *
 * Copyright 2015, Author Name
 * Some information on the license.
 *
**/

;(function(Module, $, undefined){
    'use strict';

    /**
     * Module.init
     * Init module.
    **/
    Module.init = function(){
        Module.binds();
    }

    /**
     * Module.binds
     * jQuery event binds.
    **/
    Module.binds = function(){
        $(function(){
            // Date picker.
            $('.js-datepicker').datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
    }

    Module.init();

}(window, jQuery));
