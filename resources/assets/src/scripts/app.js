import jQuery from 'jquery';

window.$ = window.jQuery = jQuery;
import axios from 'axios';

window.axios = axios;

import {initContactForm} from './contact';
import {initLoginForm} from "./login";

$(function () {
    initContactForm();
    initLoginForm();
});
