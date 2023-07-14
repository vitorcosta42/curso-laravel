import "./bootstrap";
import { createApp } from "vue";

const app = createApp({});

import ExampleComponent from "./components/ExampleComponent.vue";
import LoginComponent from "./components/Login.vue";
import HomeComponent from "./components/Home.vue";
import MarcasComponent from "./components/Marcas.vue";
import InputContainer from "./components/InputContainer.vue";
import Table from "./components/Table.vue";
import Card from "./components/Card.vue";
import Modal from "./components/Modal.vue";
import Alert from "./components/Alert.vue";
import Paginate from './components/Paginate.vue';

app.component("example-component", ExampleComponent);
app.component("Login", LoginComponent);
app.component("Home", HomeComponent);
app.component("Marcas", MarcasComponent);
app.component("InputComponent", InputContainer);
app.component("Table", Table);
app.component("Card", Card);
app.component("Modal", Modal);
app.component("Alert", Alert);
app.component("Paginate",Paginate);

app.mount("#app");
