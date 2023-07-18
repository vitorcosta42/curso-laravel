<script>
export default {
    props: ["dados", "titulos", "atualizar", "visualizar", "remover"],
    methods: {
        setStore(obj) {
            this.$store.state.transacao.status = "";
            this.$store.state.transacao.mensagem = "";
            this.$store.state.item = obj;
        },
        formatDate(value) {
            const parts = value.substring(0, 10).split("-");
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
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
    <table class="table table-hover">
        <thead>
            <tr>
                <th v-for="(t, key) in titulos" :key="key">
                    {{ t.titulo }}
                </th>
                <th v-if="visualizar.visivel || atualizar || remover"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(obj, chave) in dadosFiltrados" :key="chave">
                <td v-for="(valor, chaveValor) in obj" :key="chaveValor">
                    <span v-if="titulos[chaveValor].tipo == 'texto'">
                        {{ valor }}
                    </span>
                    <span v-if="titulos[chaveValor].tipo == 'data'">{{
                        formatDate(valor)
                    }}</span>

                    <span v-if="titulos[chaveValor].tipo == 'imagem'">
                        <img
                            :src="'/storage/' + valor"
                            width="30"
                            height="30"
                        />
                    </span>
                </td>
                <td
                    class="row"
                    v-if="visualizar.visivel || atualizar || remover.visivel"
                >
                    <button
                        v-if="visualizar.visivel"
                        class="btn btn-outline-primary btn-sm col m-1"
                        :data-bs-toggle="visualizar.dataToggle"
                        :data-bs-target="visualizar.dataTarget"
                        @click="setStore(obj)"
                    >
                        Visualizar
                    </button>
                    <button
                        v-if="atualizar"
                        class="btn btn-outline-primary btn-sm col m-1"
                    >
                        Atualizar
                    </button>
                    <button
                        v-if="remover.visivel"
                        class="btn btn-outline-danger btn-sm col m-1"
                        :data-bs-toggle="remover.dataToggle"
                        :data-bs-target="remover.dataTarget"
                        @click="setStore(obj)"
                    >
                        Remover
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</template>
