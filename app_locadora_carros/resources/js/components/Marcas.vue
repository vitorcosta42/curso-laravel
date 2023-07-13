<script>
import axios from "axios";

export default {
    data() {
        return {
            urlBase: "http://localhost:8000/api/v1/marca",
            nomeMarca: "",
            arquivoImagem: [],
            transacaoStatus: "",
            transacaoDetalhes: {},
            marcas: [],
        };
    },
    methods: {
        carregarLista() {
            axios
                .get(this.urlBase)
                .then((response) => {
                    this.marcas = response.data;
                })
                .catch((errors) => {});
        },
        carregarImagem(e) {
            this.arquivoImagem = e.target.files;
        },
        salvar() {
            const formData = new FormData();
            formData.append("nome", this.nomeMarca);
            formData.append("imagem", this.arquivoImagem[0]);
            const config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                    Accept: "application/json",
                },
            };
            axios
                .post(this.urlBase, formData, config)
                .then((response) => {
                    this.transacaoStatus = "adicionado";
                    this.transacaoDetalhes = {
                        mensagem: "ID do registro: " + response.data.id,
                    };
                })
                .catch((errors) => {
                    this.transacaoStatus = "erro";
                    this.transacaoDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors,
                    };
                });
        },
    },

    mounted() {
        this.carregarLista();
    },
};
</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- inicio card de busca -->
                <Card titulo="Busca de Marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="form-group col">
                                <InputComponent
                                    titulo="ID"
                                    id="inputId"
                                    id-help="idHelp"
                                    texto-ajuda="Opcional. Informe o ID da marca"
                                >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="inputId"
                                        aria-describedby="idHelp"
                                        placeholder="ID"
                                    />
                                </InputComponent>
                            </div>
                            <div class="form-group col">
                                <InputComponent
                                    titulo="Nome"
                                    id="inputNome"
                                    id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o nome da marca"
                                >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="inputNome"
                                        aria-describedby="nomeHelp"
                                        placeholder="Nome"
                                    />
                                </InputComponent>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button
                            type="submit"
                            class="btn btn-primary btn-sm ms-auto"
                        >
                            Pesquisar
                        </button>
                    </template>
                </Card>
                <!-- fim card de busca -->

                <!-- inicio card listagem -->
                <Card titulo="Relação de Marcas">
                    <template v-slot:refresh>
                        <button
                            class="btn btn-primary btn-sm ms-auto bg-white text-primary"
                            @click="carregarLista()"
                        >
                            Recarregar
                        </button>
                    </template>
                    <template v-slot:conteudo>
                        <Table
                            :dados="marcas"
                            :titulos="['id', 'marca', 'imagem']"
                        >
                        </Table>
                    </template>
                    <template v-slot:rodape>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm ms-auto"
                            data-bs-toggle="modal"
                            data-bs-target="#modalMarca"
                        >
                            Adicionar
                        </button>
                    </template>
                </Card>
            </div>
            <!-- fim card listagem -->
        </div>
    </div>
    <!-- Modal -->
    <Modal id="modalMarca" titulo="Adicionar Marca">
        <template v-slot:alertas>
            <Alert
                tipo="success"
                :detalhes="transacaoDetalhes"
                titulo="Cadastro realizado com sucesso!"
                v-if="transacaoStatus == 'adicionado'"
            ></Alert>
            <Alert
                tipo="danger"
                :detalhes="transacaoDetalhes"
                titulo="Erro ao tentar cadastrar a marca"
                v-if="transacaoStatus == 'erro'"
            ></Alert>
        </template>
        <template v-slot:conteudo>
            <div class="form-group mb-3">
                <InputComponent
                    titulo="Nome"
                    id="novoNome"
                    id-help="novoNomeHelp"
                    texto-ajuda="Informe o nome da marca"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="novoNome"
                        aria-describedby="novoNomeHelp"
                        placeholder="Nome"
                        v-model="nomeMarca"
                    />
                </InputComponent>
            </div>
            <div class="form-group">
                <InputComponent
                    titulo="Imagem"
                    id="novoImagem"
                    id-help="novoImagemHelp"
                    texto-ajuda="Selecione uma imagem no formato PNG ou JPEG"
                >
                    <input
                        type="file"
                        class="form-control"
                        id="novoImagem"
                        aria-describedby="novoImagemHelp"
                        placeholder="Selecione uma imagem"
                        @change="carregarImagem($event)"
                    />
                </InputComponent>
            </div>
        </template>
        <template v-slot:rodape>
            <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
            >
                Cancelar
            </button>
            <button type="button" class="btn btn-primary" @click="salvar()">
                Adicionar
            </button>
        </template>
    </Modal>
</template>
