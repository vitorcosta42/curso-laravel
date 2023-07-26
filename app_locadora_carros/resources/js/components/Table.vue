<script>
import moment from "moment";
export default {
    props: ["dados", "titulos", "atualizar", "visualizar", "remover"],
    methods: {
        setStore(obj) {
            this.$store.state.transacao.status = "";
            this.$store.state.transacao.mensagem = "";
            this.$store.state.transacao.dados = "";
            this.$store.state.item = obj;
        },
        formataData(d) {
            return moment(d).format("DD/MM/YYYY");
        },
    },
    computed: {
        dadosFiltrados() {
            let campos = Object.keys(this.titulos);
            let dadosFiltrados = [];
            this.dados.map((item, chave) => {
                let itemFiltrado = {};

                campos.forEach((campo) => {
                    itemFiltrado[campo] = item[campo];
                });
                dadosFiltrados.push(itemFiltrado);
            });
            return dadosFiltrados;
        },
    },
};
</script>

<template>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th
                        class="text-center"
                        v-for="(t, key) in titulos"
                        :key="key"
                    >
                        <div v-if="titulos[key].visivel !== false">
                            {{ t.titulo }}
                        </div>
                    </th>

                    <th
                        class="text-center"
                        v-if="
                            visualizar.visivel ||
                            atualizar.visivel ||
                            remover.visivel
                        "
                    >
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(obj, chave) in dadosFiltrados" :key="chave">
                    <td
                        class="align-middle"
                        v-for="(valor, chaveValor) in obj"
                        :key="chaveValor"
                    >
                        <div
                            class="d-flex align-items-center justify-content-center"
                        >
                            <span
                                v-if="
                                    titulos[chaveValor].tipo == 'texto' &&
                                    titulos[chaveValor].titulo !== 'Marca' &&
                                    titulos[chaveValor].titulo !== 'Modelo' &&
                                    titulos[chaveValor].titulo !== 'Cliente' &&
                                    titulos[chaveValor].titulo !== 'Carro'
                                "
                            >
                                {{ valor }}
                            </span>

                            <span
                                v-else-if="titulos[chaveValor].tipo == 'texto'"
                            >
                                {{ valor.nome }}
                            </span>
                            <span v-if="titulos[chaveValor].titulo == 'Carro'">
                                {{ valor.placa }}
                            </span>

                            <span v-if="titulos[chaveValor].tipo == 'data'">{{
                                formataData(valor)
                            }}</span>

                            <span
                                v-if="
                                    titulos[chaveValor].tipo == 'imagem' &&
                                    titulos[chaveValor].visivel !== false
                                "
                            >
                                <img
                                    :src="'/storage/' + valor"
                                    width="30"
                                    height="30"
                                />
                            </span>
                        </div>
                    </td>

                    <td
                        class="align-middle"
                        v-if="
                            visualizar.visivel ||
                            atualizar.visivel ||
                            remover.visivel
                        "
                    >
                        <div
                            :class="[
                                'btn-group',
                                'gap-2',
                                visualizar.visivel || atualizar.visivel
                                    ? 'd-flex'
                                    : 'd-block text-center',
                                'align-items-center',
                                'justify-content-center',
                            ]"
                            role="group"
                        >
                            <button
                                v-if="visualizar.visivel"
                                class="btn btn-outline-primary btn-sm rounded"
                                :data-bs-toggle="visualizar.dataToggle"
                                :data-bs-target="visualizar.dataTarget"
                                @click="setStore(obj)"
                            >
                                <i class="fas fa-eye"></i>
                            </button>

                            <button
                                v-if="atualizar.visivel"
                                class="btn btn-outline-primary btn-sm rounded"
                                :data-bs-toggle="atualizar.dataToggle"
                                :data-bs-target="atualizar.dataTarget"
                                @click="setStore(obj)"
                            >
                                <i class="fas fa-edit"></i>
                            </button>

                            <button
                                v-if="remover.visivel"
                                class="btn btn-outline-danger btn-sm rounded"
                                :data-bs-toggle="remover.dataToggle"
                                :data-bs-target="remover.dataTarget"
                                @click="setStore(obj)"
                            >
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
