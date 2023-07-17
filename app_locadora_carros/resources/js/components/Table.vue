<script>
export default {
    props: ["dados", "titulos", "atualizar", "visualizar", "remover"],
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
    methods: {
        formatDate(value) {
            const parts = value.substring(0, 10).split("-");
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        },
    },
};
</script>

<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="(t, key) in titulos" :key="key">
                    {{ t.titulo }}
                </th>
                <th
                    class="row justify-content-center"
                    v-if="visualizar || atualizar || remover"
                >
                    Ações
                </th>
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
                <td class="row" v-if="visualizar || atualizar || remover">
                    <button
                        v-if="visualizar"
                        class="btn btn-outline-primary btn-sm col m-1"
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
                        v-if="remover"
                        class="btn btn-outline-danger btn-sm col m-1"
                    >
                        Remover
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</template>
