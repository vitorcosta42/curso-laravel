/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
import  LoginComponent from './components/Login.vue';
app.component('example-component', ExampleComponent);
app.component('login-component', LoginComponent);



app.mount('#app');
