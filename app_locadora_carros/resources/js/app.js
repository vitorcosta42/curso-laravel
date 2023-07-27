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
import ClientesComponent from "./components/Clientes.vue";
import CarrosComponent from "./components/Carros.vue";
import LocacoesComponent from "./components/Locacoes.vue";
import MarcasComponent from "./components/Marcas.vue";
import ModelosComponent from "./components/Modelos.vue";
import InputComponent from "./components/InputComponent.vue";
import Table from "./components/Table.vue";
import Card from "./components/Card.vue";
import Modal from "./components/Modal.vue";
import Alert from "./components/Alert.vue";

app.component("Home", HomeComponent);
app.component("Clientes", ClientesComponent);
app.component("Modelos", ModelosComponent);
app.component("Marcas", MarcasComponent);
app.component("Carros", CarrosComponent);
app.component("Locacoes", LocacoesComponent);
app.component("Login", LoginComponent);

app.component("Alert", Alert);
app.component("Card", Card);
app.component("InputComponent", InputComponent);
app.component("Table", Table);
app.component("Modal", Modal);
app.mount("#app");
