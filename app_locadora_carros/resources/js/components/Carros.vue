<script>
export default {
    data() {
        return {
            urlBase: "http://localhost:8000/api/v1/carro",
            urlPaginacao: "",
            urlFiltro: "",
            modelo_id: "",
            disponivel: "",
            placa: "",
            km: "",
            modelos: { data: [] },
            transacaoStatus: "",
            transacaoDetalhes: {},
            carros: { data: [] },
            busca: { id: "", placa: "" },
        };
    },
    methods: {
        buscarModelos() {
            let urlModelos = "http://localhost:8000/api/v1/modelo?buscar";
            axios
                .get(urlModelos)
                .then((response) => {
                    this.modelos = response;
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },

        atualizar() {
            let formData = new FormData();
            formData.append("_method", "patch");
            formData.append("modelo_id", this.$store.state.item.modelo.id);
            formData.append("placa", this.$store.state.item.placa);
            formData.append("disponivel", this.$store.state.item.disponivel);
            formData.append("km", this.$store.state.item.km);

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
                        "Registro de carro atualizado com sucesso!";

                    console.log("Atualizado", response);
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
                    this.carros = response.data;
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },

        salvar() {
            let formData = new FormData();
            formData.append("modelo_id", this.modelo_id);
            formData.append("placa", this.placa);
            formData.append("disponivel", this.disponivel);
            formData.append("km", this.km);
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
        this.buscarModelos();
    },
};
</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- inicio card de busca -->
                <Card titulo="Busca de Carros">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="form-group col">
                                <InputComponent
                                    titulo="ID"
                                    id="inputId"
                                    id-help="idHelp"
                                    texto-ajuda="Opcional. Informe o ID do carro"
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
                                    titulo="Placa"
                                    id="inputPlaca"
                                    id-help="placaHelp"
                                    texto-ajuda="Opcional. Informe a placa do carro"
                                >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="inputPlaca"
                                        aria-describedby="placaHelp"
                                        placeholder="Placa"
                                        v-model="busca.placa"
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
                <Card titulo="Relação de Carros">
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
                            :dados="carros.data"
                            :visualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalCarroVisualizar',
                            }"
                            :atualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalCarroEditar',
                            }"
                            :remover="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalCarroRemover',
                            }"
                            :titulos="{
                                id: { titulo: 'ID', tipo: 'texto' },
                                modelo: { titulo: 'Modelo', tipo: 'texto' },
                                placa: { titulo: 'Placa', tipo: 'texto' },
                                disponivel: {
                                    titulo: 'Disponível',
                                    tipo: 'texto',
                                },
                                km: { titulo: 'KM', tipo: 'texto' },
                                created_at: {
                                    titulo: 'Data de Criação',
                                    tipo: 'data',
                                },
                            }"
                        >
                        </Table>
                    </template>
                    <template v-slot:rodape>
                        <Paginate>
                            <li
                                v-for="(l, key) in carros.links"
                                :key="key"
                                :class="
                                    l.active ? 'page-item active' : 'page-item'
                                "
                                @click="paginacao(l)"
                            >
                                <a class="page-link" v-html="l.label"></a>
                            </li>
                        </Paginate>
                        <div class="ms-auto">
                            <button
                                type="button"
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalCarro"
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
    <Modal id="modalCarro" titulo="Adicionar Carro">
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
                titulo="Erro ao tentar cadastrar o carro"
                v-if="transacaoStatus == 'erro'"
            ></Alert>
        </template>
        <template v-slot:conteudo>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Modelo"
                    id="selectModelo"
                    id-help="novoModeloHelp"
                    texto-ajuda="Informe o modelo do carro"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        name="Marca"
                        id="selectModelo"
                        v-model="modelo_id"
                    >
                        <option value="" disabled selected>
                            Selecione o modelo
                        </option>

                        <option
                            v-for="modelo in modelos.data"
                            :key="modelo.id"
                            :value="modelo.id"
                        >
                            {{ modelo.nome }}
                        </option>
                    </select>
                </InputComponent>
                <InputComponent
                    titulo="Placa"
                    id="inputPlaca"
                    id-help="PlacaHelp"
                    texto-ajuda="Informe a placa do veículo"
                    class="w-100"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="inputPlaca"
                        aria-describedby="PlacaHelp"
                        placeholder="Placa"
                        v-model="placa"
                    />
                </InputComponent>
            </div>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Disponível"
                    id="Disponivel"
                    id-help="DisponivelHelp"
                    texto-ajuda="Selecione a disponibilidade do veículo"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        name="Disponivel"
                        id="Disponivel"
                        aria-describedby="DisponivelHelp"
                        v-model="disponivel"
                    >
                        <option value="" disabled selected>Selecionar</option>
                        <option :value="1">Disponível</option>
                        <option :value="0">Não disponível</option>
                    </select>
                </InputComponent>
                <InputComponent
                    titulo="Quilometragem"
                    id="inputKm"
                    id-help="KmHelp"
                    texto-ajuda="Informe a quilometragem do veículo"
                    class="w-100"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="inputKm"
                        aria-describedby="KmHelp"
                        placeholder="Quilometragem"
                        v-model="km"
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

    <Modal id="modalCarroVisualizar" titulo="Visualizar Carro">
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
            <InputComponent titulo="Modelo">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.modelo?.nome"
                    disabled
                />
            </InputComponent>
            <InputComponent titulo="Placa">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.placa"
                    disabled
                />
            </InputComponent>
            <InputComponent titulo="Imagem">
                <img
                    :src="'storage/' + $store.state.item.modelo?.imagem"
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
    <Modal id="modalCarroRemover" titulo="Remover Carro">
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
            <InputComponent titulo="Modelo">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.modelo?.nome"
                    disabled
                />
            </InputComponent>
            <InputComponent titulo="Placa">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.placa"
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
    <Modal id="modalCarroEditar" titulo="Editar Carro">
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
                    titulo="Modelo"
                    id="atualizarModelo"
                    id-help="atualizarModeloHelp"
                    texto-ajuda="Informe o modelo do carro"
                    class="w-100"
                >
                    <div v-if="$store.state.item && $store.state.item.modelo">
                        <select
                            class="form-select"
                            name="modelo"
                            id="atualizarModelo"
                            v-model="$store.state.item.modelo.id"
                            aria-describedby="atualizarModeloHelp"
                        >
                            <option value="" disabled>Selecionar</option>
                            <option
                                v-for="modelo in modelos.data"
                                :key="modelo.id"
                                :value="modelo.id"
                            >
                                {{ modelo.nome }}
                            </option>
                        </select>
                    </div>
                </InputComponent>

                <InputComponent
                    titulo="Placa"
                    id="atualizarPlaca"
                    id-help="atualizarPlacaHelp"
                    texto-ajuda="Informe a placa do veículo"
                    class="w-100"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="atualizarPlaca"
                        aria-describedby="atualizarPlacaHelp"
                        placeholder="Placa"
                        v-model="$store.state.item.placa"
                    />
                </InputComponent>
            </div>

            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Disponível"
                    id="disponibilidade"
                    id-help="atualizarDisponibilidade"
                    texto-ajuda="Selecione a disponibilidade do veículo"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        id="disponibilidade"
                        aria-describedby="atualizarDisponibilidade"
                        v-model="$store.state.item.disponivel"
                    >
                        <option value="" disabled selected>Selecionar</option>
                        <option :value="1">1</option>
                        <option :value="0">0</option>
                    </select>
                </InputComponent>
                <InputComponent
                    titulo="Quilometragem"
                    id="atualizarKm"
                    id-help="atualizarKmHelp"
                    texto-ajuda="Informe a quilometragem do carro"
                    class="w-100"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="atualizarKm"
                        aria-describedby="atualizarKmHelp"
                        placeholder="KM"
                        v-model="$store.state.item.km"
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
