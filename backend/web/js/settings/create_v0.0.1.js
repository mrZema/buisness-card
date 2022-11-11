"use strict";

import RequestService from '../requestModule_v0.0.1.js';
/*import Loader_escapingBall_v001 from '../loader_escapingBall_v.0.0.1.js';*/

//Declaring variables
let setting_type;

window.onload = function() {
    defineVariables();
    hangOnListeners();
};

//Declaring functions
function verifySettingFields() {
    let elements = document.getElementById('setting-form').elements;
    for (let i=0; i<elements.length; i++) {
        if (elements[i].value !== '') {
            elements[i].focus();
            elements[i].blur();
        }
    }
}

function changeSettingType() {
    let options = { url: '/settings/item/create' };
        options.method = 'POST';
        options.data = $('form').serialize();
        options.container = '#form-container';
        options.replace = false; // turning off url pushState
    let call = $.pjax.reload(options);
        call.done(function() {
            defineVariables();
            hangOnListeners();
            verifySettingFields();
        });
}

function defineVariables() {
    setting_type = document.getElementById('setting-type');
}

function hangOnListeners() {
    setting_type.onchange = function() {
        changeSettingType();
    };
}
