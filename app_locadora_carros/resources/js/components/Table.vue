<script>
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
            if (!d) return "";
            d = d.split("T");
            let data = d[0];
            data = data.split("-");
            data = data[2] + "/" + data[1] + "/" + data[0];
            return data;
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
                <th
                    v-if="
                        visualizar.visivel ||
                        atualizar.visivel ||
                        remover.visivel
                    "
                ></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(obj, chave) in dadosFiltrados" :key="chave">
                <td v-for="(valor, chaveValor) in obj" :key="chaveValor">
                    <span v-if="titulos[chaveValor].tipo == 'texto'">
                        {{ valor }}
                    </span>
                    <span v-if="titulos[chaveValor].tipo == 'data'">{{
                        formataData(valor)
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
                    v-if="
                        visualizar.visivel ||
                        atualizar.visivel ||
                        remover.visivel
                    "
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
                        v-if="atualizar.visivel"
                        class="btn btn-outline-primary btn-sm col m-1"
                        :data-bs-toggle="atualizar.dataToggle"
                        :data-bs-target="atualizar.dataTarget"
                        @click="setStore(obj)"
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
