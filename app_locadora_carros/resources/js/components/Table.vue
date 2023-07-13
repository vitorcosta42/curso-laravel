<script>
export default {
    props: ["dados", "titulos"],
};
</script>

<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th
                    scope="col"
                    v-for="(t, key) in titulos"
                    :key="key"
                    class="text-uppercase"
                >
                    {{ t }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj in dados" :key="obj.id">
                <td
                    v-if="titulos.includes(chave)"
                    v-for="(valor, chave) in obj"
                    :key="chave"
                >
                    {{ valor }}
                    <span v-if="chave == 'imagem'">
                        <img
                            :src="'/storage/' + valor"
                            width="30"
                            height="30"
                            alt="imagem-marca"
                    /></span>
                    <span v-else>
                        {{ valor }}
                    </span>
                </td>
                <!-- <td>
                    <img
                        :src="'/storage/' + m.imagem"
                        alt="imagem-marca"
                        width="40"
                        length="40"
                    />
                </td> -->
            </tr>
        </tbody>
    </table>
</template>
