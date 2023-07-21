<script>
import Paginate from "./Paginate.vue";

export default {
    components: {
        Paginate,
    },
    data() {
        return {
            urlBase: "http://localhost:8000/api/v1/modelo",
            urlPaginacao: "",
            urlFiltro: "",
            nomeModelo: "",
            marca_id: "",
            marcas: [],
            numero_portas: "",
            air_bag: "",
            lugares: "",
            abs: "",
            arquivoImagem: [],
            transacaoStatus: "",
            transacaoDetalhes: {},
            modelos: { data: [] },
            busca: { id: "", nome: "" },
        };
    },
    methods: {
        buscarMarcas() {
            let urlMarcas = "http://localhost:8000/api/v1/marca";
            axios
                .get(urlMarcas)
                .then((response) => {
                    this.marcas = response.data;
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },

        atualizar() {
            let formData = new FormData();
            formData.append("_method", "patch");
            formData.append("nome", this.$store.state.item.nome);
            formData.append("marca_id", this.$store.state.item.marca.id);
            formData.append(
                "numero_portas",
                this.$store.state.item.numero_portas
            );
            formData.append("lugares", this.$store.state.item.lugares);
            formData.append("air_bag", this.$store.state.item.air_bag);
            formData.append("abs", this.$store.state.item.abs);
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
                        "Registro de modelo atualizado com sucesso!";

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
                this.urlPaginacao = "page=1";
                this.urlFiltro = "&filtro=" + filtro;
            } else {
                this.urlFiltro = "";
            }
            this.carregarLista();
        },
        paginacao(l) {
            if (l.url) {
                this.urlPaginacao = l.url.split("?")[1];
                this.carregarLista();
            }
        },
        carregarLista() {
            let url = this.urlBase + "?" + this.urlPaginacao + this.urlFiltro;
            axios
                .get(url)
                .then((response) => {
                    this.modelos = response.data;
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
            formData.append("nome", this.nomeModelo);
            formData.append("marca_id", this.marca_id);
            formData.append("numero_portas", this.numero_portas);
            formData.append("lugares", this.lugares);
            formData.append("air_bag", this.air_bag);
            formData.append("abs", this.abs);
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
        this.buscarMarcas();
    },
};
</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- inicio card de busca -->
                <Card titulo="Busca de Modelos">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="form-group col">
                                <InputComponent
                                    titulo="ID"
                                    id="inputId"
                                    id-help="idHelp"
                                    texto-ajuda="Opcional. Informe o ID da modelo"
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
                                    texto-ajuda="Opcional. Informe o nome da modelo"
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
                <Card titulo="Relação de Modelos">
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
                            :dados="modelos.data"
                            :visualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalModeloVisualizar',
                            }"
                            :atualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalModeloEditar',
                            }"
                            :remover="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalModeloRemover',
                            }"
                            :titulos="{
                                id: { titulo: 'ID', tipo: 'texto' },
                                nome: { titulo: 'Nome', tipo: 'texto' },
                                marca: { titulo: 'Marca', tipo: 'texto' },
                                imagem: {
                                    titulo: 'Imagem',
                                    tipo: 'imagem',
                                    visivel: false,
                                },
                                numero_portas: {
                                    titulo: 'N° de Portas',
                                    tipo: 'texto',
                                },
                                lugares: { titulo: 'Lugares', tipo: 'texto' },
                                air_bag: { titulo: 'Airbag', tipo: 'texto' },
                                abs: { titulo: 'Freio ABS', tipo: 'texto' },
                                created_at: {
                                    titulo: 'Data de Criação',
                                    tipo: 'data',
                                },
                            }"
                        >
                        </Table>
                    </template>
                    <template v-slot:rodape>
                        <paginate-component>
                            <li
                                v-for="(l, key) in modelos.links"
                                :key="key"
                                :class="
                                    l.active ? 'page-item active' : 'page-item'
                                "
                                @click="paginacao(l)"
                            >
                                <a class="page-link" v-html="l.label"></a>
                            </li>
                        </paginate-component>
                        <div class="ms-auto">
                            <button
                                type="button"
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalModelo"
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
    <Modal id="modalModelo" titulo="Adicionar Modelo">
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
                titulo="Erro ao tentar cadastrar a modelo"
                v-if="transacaoStatus == 'erro'"
            ></Alert>
        </template>
        <template v-slot:conteudo>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Nome"
                    id="novoNome"
                    id-help="novoNomeHelp"
                    texto-ajuda="Informe o nome da modelo"
                    class="w-100"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="novoNome"
                        aria-describedby="novoNomeHelp"
                        placeholder="Nome"
                        v-model="nomeModelo"
                    />
                </InputComponent>
                <InputComponent
                    titulo="Marca"
                    id="selectMarca"
                    id-help="novoNomeHelp"
                    texto-ajuda="Informe a marca do modelo"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        name="Marca"
                        id="selectMarca"
                        v-model="marca_id"
                    >
                        <option value="" disabled selected>
                            Selecione a marca
                        </option>

                        <option
                            v-for="marca in marcas.data"
                            :key="marca.id"
                            :value="marca.id"
                        >
                            {{ marca.nome }}
                        </option>
                    </select>
                </InputComponent>
            </div>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Número de Portas"
                    id="numeroPortas"
                    id-help="novoNumeroPortas"
                    texto-ajuda="Informe o número de portas"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        id="numeroPortas"
                        aria-describedby="novoNumeroPortas"
                        v-model="numero_portas"
                    >
                        <option value="" disabled selected>Selecionar</option>
                        <option :value="1">1</option>
                        <option :value="2">2</option>
                        <option :value="3">3</option>
                        <option :value="4">4</option>
                        <option :value="5">5</option>
                    </select>
                </InputComponent>

                <InputComponent
                    titulo="Lugares"
                    id="novoLugares"
                    id-help="novoLugaresHelp"
                    texto-ajuda="Informe a quantidade de lugares"
                    class="w-100"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="novoLugares"
                        aria-describedby="novoLugaresHelp"
                        placeholder="Quantidade de Lugares"
                        v-model="lugares"
                    />
                </InputComponent>
            </div>

            <div class="form-group d-flex gap-3">
                <InputComponent
                    titulo="Airbag"
                    id="selectAirbag"
                    id-help="novoAirbagHelp"
                    texto-ajuda="Informe se o veículo possui Airbag"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        name="Airbag"
                        id="selectAirbag"
                        v-model="air_bag"
                        placeholder="Selecionar"
                    >
                        <option value="" disabled selected>Selecionar</option>
                        <option :value="1">Possui</option>
                        <option :value="0">Não possui</option>
                    </select>
                </InputComponent>
                <InputComponent
                    titulo="Freio ABS"
                    id="selectAbs"
                    id-help="novoAbsHelp"
                    texto-ajuda="Informe se o veículo possui ABS"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        name="Abs"
                        id="selectAbs"
                        aria-describedby="NovoAbsHelp"
                        v-model="abs"
                    >
                        <option value="" disabled selected>Selecionar</option>
                        <option :value="1">Possui</option>
                        <option :value="0">Não possui</option>
                    </select>
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
    <!-- fim do modal de adicionar modelo -->
    <!-- inicio modal de visualizar modelo -->

    <Modal id="modalModeloVisualizar" titulo="Visualizar Modelo">
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
            <InputComponent titulo="Marca do Modelo">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.marca?.nome"
                    disabled
                />
            </InputComponent>
            <InputComponent titulo="Nome do Modelo">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.nome"
                    disabled
                />
            </InputComponent>
            <InputComponent titulo="Imagem do Modelo">
                <img
                    :src="'storage/' + $store.state.item.imagem"
                    class="d-flex"
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
    <!--fim modal de visualizar modelo -->

    <!-- inicio modal de remoção de modelo-->
    <Modal id="modalModeloRemover" titulo="Remover Modelo">
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
            <InputComponent titulo="Nome do Modelo">
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
    <!--fim modal de remoção de modelo -->
    <!-- inicio modal de edição de modelo-->
    <Modal id="modalModeloEditar" titulo="Editar Modelo">
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
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Nome"
                    id="atualizarNome"
                    id-help="atualizarNomeHelp"
                    texto-ajuda="Informe o nome do modelo"
                    class="w-100"
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

                <InputComponent
                    titulo="Marca"
                    id="atualizarMarca"
                    id-help="atualizarMarcaHelp"
                    texto-ajuda="Informe a marca do modelo"
                    class="w-100"
                >
                    <div v-if="$store.state.item && $store.state.item.marca">
                        <select
                            class="form-select"
                            name="Marca"
                            id="atualizarMarca"
                            v-model="$store.state.item.marca.id"
                            aria-describedby="atualizarMarcaHelp"
                        >
                            <option value="" disabled>Selecionar</option>
                            <option
                                v-for="marca in marcas.data"
                                :key="marca.id"
                                :value="marca.id"
                            >
                                {{
                                    marca.nome
                                }}
                            </option>
                        </select>
                    </div>
                </InputComponent>
            </div>

            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Número de Portas"
                    id="numeroPortas"
                    id-help="atualizarNumeroPortas"
                    texto-ajuda="Informe o número de portas"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        id="numeroPortas"
                        aria-describedby="atualizarNumeroPortas"
                        v-model="$store.state.item.numero_portas"
                    >
                        <option value="" disabled selected>Selecionar</option>
                        <option :value="1">1</option>
                        <option :value="2">2</option>
                        <option :value="3">3</option>
                        <option :value="4">4</option>
                        <option :value="5">5</option>
                    </select>
                </InputComponent>

                <InputComponent
                    titulo="Lugares"
                    id="atualizarLugares"
                    id-help="atualizarLugaresHelp"
                    texto-ajuda="Informe a quantidade de lugares"
                    class="w-100"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="atualizarLugares"
                        aria-describedby="atualizarLugaresHelp"
                        placeholder="Quantidade de Lugares"
                        v-model="$store.state.item.lugares"
                    />
                </InputComponent>
            </div>

            <div class="form-group d-flex gap-3">
                <InputComponent
                    titulo="Airbag"
                    id="atualizarAirbag"
                    id-help="atualizarAirbagHelp"
                    texto-ajuda="Informe se o veículo possui Airbag"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        name="Airbag"
                        id="atualizarAirbag"
                        aria-describedby="atualizarAirbagHelp"
                        v-model="$store.state.item.air_bag"
                        placeholder="Selecionar"
                    >
                        <option value="" disabled selected>Selecionar</option>
                        <option :value="1">Possui</option>
                        <option :value="0">Não possui</option>
                    </select>
                </InputComponent>
                <InputComponent
                    titulo="Freio ABS"
                    id="atualizarAbs"
                    id-help="atualizarAbsHelp"
                    texto-ajuda="Informe se o veículo possui ABS"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        name="Abs"
                        id="atualizarAbs"
                        aria-describedby="atualizarAbsHelp"
                        v-model="$store.state.item.abs"
                    >
                        <option value="" disabled selected>Selecionar</option>
                        <option :value="1">Possui</option>
                        <option :value="0">Não possui</option>
                    </select>
                </InputComponent>
            </div>
            <div class="form-group">
                <InputComponent
                    titulo="Imagem"
                    id="atualizarImagem"
                    id-help="atualizarImagemHelp"
                    texto-ajuda="Selecione uma imagem no formato PNG ou JPEG"
                    class="w-100"
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
    <!-- fim do modal de edição de  modelo -->
</template>
