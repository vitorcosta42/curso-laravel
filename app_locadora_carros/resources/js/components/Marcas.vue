<script>
import axios from "axios";
export default {
    data() {
        return {
            urlBase: "http://localhost:8000/api/v1/marca",
            urlFiltro: "",
            nomeMarca: "",
            arquivoImagem: [],
            transacaoStatus: "",
            transacaoDetalhes: {},
            marcas: { data: [] },
            busca: { id: "", nome: "" },
            currentPage: 1,
            itemsPerPage: 5,
        };
    },
    methods: {
        goToPage(pageNumber) {
            this.currentPage = pageNumber;
        },
        atualizar() {
            let formData = new FormData();
            formData.append("_method", "patch");
            formData.append("nome", this.$store.state.item.nome);
            if (this.arquivoImagem[0]) {
                formData.append("imagem", this.arquivoImagem[0]);
            }

            let url = this.urlBase + "/" + this.$store.state.item.id;

            let config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            };

            axios
                .post(url, formData, config)
                .then((response) => {
                    this.$store.state.transacao.status = "sucesso";
                    this.$store.state.transacao.mensagem =
                        "Registro de marca atualizado com sucesso!";

                    console.log("Atualizado", response);
                    atualizarImagem.value = "";
                    this.carregarLista();
                })
                .catch((errors) => {
                    this.$store.state.transacao.status = "erro";
                    this.$store.state.transacao.mensagem =
                        errors.response.data.message;
                    this.$store.state.transacao.dados =
                        errors.response.data.errors;
                    console.log("Erro de atualização", errors.response);
                });
        },
        remover() {
            let confirmacao = confirm(
                "Tem certeza que deseja remover esse registro?"
            );
            if (!confirmacao) {
                return false;
            }
            let formData = new FormData();
            formData.append("_method", "delete");
            let url = this.urlBase + "/" + this.$store.state.item.id;

            axios
                .post(url, formData)
                .then((response) => {
                    this.$store.state.transacao.status = "sucesso";
                    this.$store.state.transacao.mensagem = response.data.msg;
                    this.carregarLista();
                })
                .catch((errors) => {
                    this.$store.state.transacao.status = "erro";
                    this.$store.state.transacao.mensagem =
                        errors.response.data.erro;
                });
        },
        pesquisar() {
            let filtro = "";
            for (let chave in this.busca) {
                if (this.busca[chave]) {
                    if (filtro != "") {
                        filtro += ";";
                    }
                    filtro += chave + ":like:" + this.busca[chave];
                }
            }
            if (filtro != "") {
                this.urlFiltro = "&filtro=" + filtro;
            } else {
                this.urlFiltro = "";
            }
            this.carregarLista();
        },

        carregarLista() {
            let url = this.urlBase + "?" + this.urlFiltro;
            axios
                .get(url)
                .then((response) => {
                    this.marcas = response;
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },

        carregarImagem(e) {
            this.arquivoImagem = e.target.files;
        },
        salvar() {
            let formData = new FormData();
            formData.append("nome", this.nomeMarca);
            formData.append("imagem", this.arquivoImagem[0]);

            let config = {
                headers: {
                    "Content-Type": "multipart/form-data",
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
    computed: {
        totalPages() {
            return Math.ceil(this.items.length / this.itemsPerPage);
        },
        paginatedList() {
            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            const endIndex = startIndex + this.itemsPerPage;
            return this.items.slice(startIndex, endIndex);
        },
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
                                        v-model="busca.id"
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
                                        v-model="busca.nome"
                                    />
                                </InputComponent>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button
                            type="submit"
                            class="btn btn-primary btn-sm ms-auto"
                            @click="pesquisar()"
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
                            :dados="marcas.data"
                            :visualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalMarcaVisualizar',
                            }"
                            :atualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalMarcaEditar',
                            }"
                            :remover="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalMarcaRemover',
                            }"
                            :titulos="{
                                id: { titulo: 'ID', tipo: 'texto' },
                                nome: {
                                    titulo: 'Nome',
                                    tipo: 'texto',
                                },
                                imagem: {
                                    titulo: 'Imagem',
                                    tipo: 'imagem',
                                },
                                created_at: {
                                    titulo: 'Data de Criação',
                                    tipo: 'data',
                                },
                            }"
                        >
                        </Table>
                    </template>

                    <template v-slot:rodape>
                        <div class="ms-auto">
                            <button
                                type="button"
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalMarca"
                            >
                                Adicionar
                            </button>
                        </div>
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
    <!-- fim do modal de adicionar marca -->
    <!-- inicio modal de visualizar marca -->
    <Modal id="modalMarcaVisualizar" titulo="Visualizar Marca">
        <template v-slot:alertas></template>
        <template v-slot:conteudo>
            <InputComponent titulo="ID">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.id"
                    disabled
                />
            </InputComponent>
            <InputComponent titulo="Nome da Marca">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.nome"
                    disabled
                />
            </InputComponent>
            <InputComponent titulo="Imagem da Marca">
                <img
                    :src="'storage/' + $store.state.item.imagem"
                    class="d-flex"
                />
            </InputComponent>
            <InputComponent titulo="Data de Criação">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.created_at"
                    disabled
                />
            </InputComponent>
        </template>

        <template v-slot:rodape>
            <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
            >
                Fechar
            </button>
        </template>
    </Modal>
    <!--fim modal de visualizar marca -->

    <!-- inicio modal de remoção de marca-->
    <Modal id="modalMarcaRemover" titulo="Remover Marca">
        <template v-slot:alertas>
            <Alert
                tipo="success"
                titulo="Ação realizada com sucesso"
                :detalhes="$store.state.transacao"
                v-if="$store.state.transacao.status == 'sucesso'"
            ></Alert>

            <Alert
                tipo="danger"
                titulo="Erro no processo"
                :detalhes="$store.state.transacao"
                v-if="$store.state.transacao.status == 'erro'"
            >
            </Alert>
        </template>
        <template v-slot:conteudo>
            <InputComponent titulo="ID">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.id"
                    disabled
                />
            </InputComponent>
            <InputComponent titulo="Nome da Marca">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.nome"
                    disabled
                />
            </InputComponent>
        </template>

        <template v-slot:rodape>
            <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
            >
                Fechar
            </button>
            <button
                type="button"
                class="btn btn-danger"
                data-bs-dismiss="modal"
                @click="remover()"
                v-if="$store.state.transacao.status != 'sucesso'"
            >
                Remover
            </button>
        </template>
    </Modal>
    <!--fim modal de remoção de marca -->
    <!-- inicio modal de edição de marca-->
    <Modal id="modalMarcaEditar" titulo="Editar Marca">
        <template v-slot:alertas>
            <Alert
                tipo="success"
                titulo="Ação realizada com sucesso"
                :detalhes="$store.state.transacao"
                v-if="$store.state.transacao.status == 'sucesso'"
            ></Alert>

            <Alert
                tipo="danger"
                titulo="Erro no processo"
                :detalhes="$store.state.transacao"
                v-if="$store.state.transacao.status == 'erro'"
            >
            </Alert>
        </template>
        <template v-slot:conteudo>
            <div class="form-group mb-3">
                <InputComponent
                    titulo="Nome"
                    id="atualizarNome"
                    id-help="atualizarNomeHelp"
                    texto-ajuda="Informe o nome da marca"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="atualizarNome"
                        aria-describedby="atualizarNomeHelp"
                        placeholder="Nome"
                        v-model="$store.state.item.nome"
                    />
                </InputComponent>
            </div>
            <div class="form-group">
                <InputComponent
                    titulo="Imagem"
                    id="atualizarImagem"
                    id-help="atualizarImagemHelp"
                    texto-ajuda="Selecione uma imagem no formato PNG ou JPEG"
                >
                    <input
                        type="file"
                        class="form-control"
                        id="atualizarImagem"
                        aria-describedby="atualizarImagemHelp"
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
            <button type="button" class="btn btn-primary" @click="atualizar()">
                Editar
            </button>
        </template>
    </Modal>
    <!-- fim do modal de edição de  marca -->
</template>
