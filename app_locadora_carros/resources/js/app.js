import "./bootstrap";
import { createApp } from "vue";
import { createStore } from "vuex";

const store = createStore({
    state() {
        return {
            item: {},
            transacao: {
                status: "",
                mensagem: "",
                dados: "",
            },
        };
    },
});

const app = createApp({});
app.use(store);

import LoginComponent from "./components/Login.vue";
import HomeComponent from "./components/Home.vue";
import MarcasComponent from "./components/Marcas.vue";
import InputComponent from "./components/InputComponent.vue";
import Table from "./components/Table.vue";
import Card from "./components/Card.vue";
import Modal from "./components/Modal.vue";
import Alert from "./components/Alert.vue";
import Paginate from "./components/Paginate.vue";

app.component("Login", LoginComponent);
app.component("Home", HomeComponent);
app.component("Marcas", MarcasComponent);
app.component("InputComponent", InputComponent);
app.component("Table", Table);
app.component("Card", Card);
app.component("Modal", Modal);
app.component("Alert", Alert);
app.component("paginate-component", Paginate);

app.mount("#app");
