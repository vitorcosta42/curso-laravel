<script>
import moment from "moment";
export default {
    data() {
        return {
            urlBase: "http://localhost:8000/api/v1/locacao",
            urlPaginacao: "",
            urlFiltro: "",
            carro_id: "",
            cliente_id: "",
            data_inicio_periodo: "",
            data_final_previsto_periodo: "",
            data_final_realizado_periodo: "",
            valor_diaria: "",
            km_inicial: "",
            km_final: "",
            carros: { data: [] },
            clientes: { data: [] },
            transacaoStatus: "",
            transacaoDetalhes: {},
            locacoes: { data: [] },
            busca: { id: "", cliente: "" },
        };
    },
    methods: {
        formataData(d) {
            return moment(d).format("DD/MM/YYYY");
        },
        buscarCarros() {
            let urlCarros = "http://localhost:8000/api/v1/carro?buscar";
            axios
                .get(urlCarros)
                .then((response) => {
                    this.carros = response;
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },

        buscarClientes() {
            let urlClientes = "http://localhost:8000/api/v1/cliente?buscar";
            axios
                .get(urlClientes)
                .then((response) => {
                    this.clientes = response;
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },
        atualizar() {
            let formData = new FormData();
            formData.append("_method", "patch");
            formData.append("carro_id", this.$store.state.item.carro.id);
            formData.append("cliente_id", this.$store.state.item.cliente.id);
            formData.append(
                "data_inicio_periodo",
                this.$store.state.item.data_inicio_periodo
            );
            formData.append(
                "data_inicio_periodo",
                this.$store.state.item.data_final_previsto_periodo
            );
            formData.append(
                "data_final_realizado_periodo",
                this.$store.state.item.data_final_realizado_periodo
            );
            formData.append(
                "valor_diaria",
                this.$store.state.item.valor_diaria
            );
            formData.append("km_inicial", this.$store.state.item.km_inicial);
            formData.append("km_final", this.$store.state.item.km_final);

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
                    this.locacoes = response.data;
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },

        salvar() {
            let formData = new FormData();
            formData.append("carro_id", this.carro_id);
            formData.append("cliente_id", this.cliente_id);
            formData.append("data_inicio_periodo", this.data_inicio_periodo);
            formData.append(
                "data_final_previsto_periodo",
                this.data_final_previsto_periodo
            );
            formData.append(
                "data_final_realizado_periodo",
                this.data_final_realizado_periodo
            );
            formData.append("valor_diaria", this.valor_diaria);
            formData.append("km_inicial", this.km_inicial);
            formData.append("km_final", this.km_final);

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
        this.buscarCarros();
        this.buscarClientes();
    },
};
</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- inicio card de busca -->
                <Card titulo="Busca de Locações">
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
                <Card titulo="Relação de Locações">
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
                            :dados="locacoes.data"
                            :visualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalLocacaoVisualizar',
                            }"
                            :atualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalLocacaoEditar',
                            }"
                            :remover="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalLocacaoRemover',
                            }"
                            :titulos="{
                                id: { titulo: 'ID', tipo: 'texto' },
                                cliente: {
                                    titulo: 'Cliente',
                                    tipo: 'texto',
                                },
                                carro: {
                                    titulo: 'Carro',
                                    tipo: 'texto',
                                },
                                data_inicio_periodo: {
                                    titulo: 'Data Início',
                                    tipo: 'data',
                                },
                                data_final_previsto_periodo: {
                                    titulo: 'Data Final Prevista',
                                    tipo: 'data',
                                },
                                valor_diaria: {
                                    titulo: 'Valor Diária',
                                    tipo: 'texto',
                                },
                                km_inicial: {
                                    titulo: 'Km Inicial',
                                    tipo: 'texto',
                                },
                                data_final_realizado_periodo: {
                                    titulo: 'Data Final',
                                    tipo: 'data',
                                },
                                km_final: {
                                    titulo: 'Km Final',
                                    tipo: 'texto',
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
                        <Paginate>
                            <li
                                v-for="(l, key) in locacoes.links"
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
                                data-bs-target="#modalLocacao"
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
    <Modal id="modalLocacao" titulo="Adicionar Locação">
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
                titulo="Erro ao tentar cadastrar a locação"
                v-if="transacaoStatus == 'erro'"
            ></Alert>
        </template>
        <template v-slot:conteudo>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Cliente"
                    id="selectCliente"
                    id-help="novoClienteHelp"
                    texto-ajuda="Informe o cliente da locação"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        name="Marca"
                        id="selectCliente"
                        v-model="cliente_id"
                    >
                        <option value="" disabled selected>
                            Selecione o cliente
                        </option>

                        <option
                            v-for="cliente in clientes.data"
                            :key="cliente.id"
                            :value="cliente.id"
                        >
                            {{ cliente.nome }}
                        </option>
                    </select>
                </InputComponent>
                <InputComponent
                    titulo="Carro"
                    id="selectCarro"
                    id-help="novoCarroHelp"
                    texto-ajuda="Informe o carro da locação"
                    class="w-100"
                >
                    <select
                        class="form-select"
                        name="Marca"
                        id="selectCarro"
                        v-model="carro_id"
                    >
                        <option value="" disabled selected>
                            Selecione o carro
                        </option>

                        <option
                            v-for="carro in carros.data"
                            :key="carro.id"
                            :value="carro.id"
                        >
                            {{ carro.modelo.nome }} - {{ carro.placa }}
                        </option>
                    </select>
                </InputComponent>
            </div>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Data Inicial"
                    id="inputDataInicioPeriodo"
                    id-help="DataInicioPeriodoHelp"
                    texto-ajuda="Informe a data de início da locação"
                    class="w-100"
                >
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="inputDataInicioPeriodo"
                        aria-describedby="DataInicioPeriodoHelp"
                        placeholder="Data Inicial"
                        v-model="data_inicio_periodo"
                    />
                </InputComponent>
                <InputComponent
                    titulo="Data Final Prevista"
                    id="inputDataFinalPrevista"
                    id-help="DataFinalPrevistaHelp"
                    texto-ajuda="Informe a data final prevista da locação"
                    class="w-100"
                >
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="inputDataFinalPrevista"
                        aria-describedby="DataFinalPrevistaHelp"
                        placeholder="Data Final Prevista"
                        v-model="data_final_previsto_periodo"
                    />
                </InputComponent>
            </div>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Valor Diária"
                    id="inputValorDiaria"
                    id-help="ValorDiariaHelp"
                    texto-ajuda="Informe o valor da diária da locação"
                    class="w-100"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="inputValorDiaria"
                        aria-describedby="ValorDiariaHelp"
                        placeholder="Valor Diária"
                        v-model="valor_diaria"
                    />
                </InputComponent>

                <InputComponent
                    titulo="Quilometragem Inicial"
                    id="inputKmInicial"
                    id-help="KmInicialHelp"
                    texto-ajuda="Informe a quilometragem inicial da locação"
                    class="w-100"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="inputKmInicial"
                        aria-describedby="KmInicialHelp"
                        placeholder="Quilometragem Inicial"
                        v-model="km_inicial"
                    />
                </InputComponent>
            </div>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Data Final Realizada"
                    id="inputDataFinalRealizado"
                    id-help="DataFinalRealizadoHelp"
                    texto-ajuda="Informe a data final realizada da locação"
                    class="w-100"
                >
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="inputDataFinalRealizada"
                        aria-describedby="DataFinalRealizadaHelp"
                        placeholder="Data Final Realizada"
                        v-model="data_final_realizado_periodo"
                    />
                </InputComponent>

                <InputComponent
                    titulo="Quilometragem Final"
                    id="inputKmFinal"
                    id-help="KmFinalHelp"
                    texto-ajuda="Informe a quilometragem final da locação"
                    class="w-100"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="inputKmFinal"
                        aria-describedby="KmFinalHelp"
                        placeholder="Quilometragem Final"
                        v-model="km_final"
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

    <Modal id="modalLocacaoVisualizar" titulo="Visualizar Locação">
        <template v-slot:alertas></template>
        <template v-slot:conteudo>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent titulo="ID" class="w-100">
                    <input
                        type="text"
                        class="form-control"
                        :value="$store.state.item.id"
                        disabled
                    />
                </InputComponent>
                <InputComponent titulo="Cliente" class="w-100">
                    <input
                        type="text"
                        class="form-control"
                        :value="$store.state.item.cliente?.nome"
                        disabled
                    />
                </InputComponent>
            </div>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent titulo="Carro (ID)" class="w-100">
                    <input
                        type="text"
                        class="form-control"
                        :value="$store.state.item.carro?.id"
                        disabled
                    />
                </InputComponent>
                <InputComponent titulo="Placa" class="w-100">
                    <input
                        type="text"
                        class="form-control"
                        :value="$store.state.item.carro?.placa"
                        disabled
                    />
                </InputComponent>
            </div>

            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent titulo="Data Inicial" class="w-100">
                    <input
                        type="datetime-local"
                        class="form-control"
                        :value="$store.state.item.data_inicio_periodo"
                        disabled
                    />
                </InputComponent>
                <InputComponent titulo="Data Final Prevista" class="w-100">
                    <input
                        type="datetime-local"
                        class="form-control"
                        :value="$store.state.item.data_final_previsto_periodo"
                        disabled
                    />
                </InputComponent>
            </div>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent titulo="Valor Diária" class="w-100">
                    <input
                        type="text"
                        class="form-control"
                        :value="$store.state.item.valor_diaria"
                        disabled
                    />
                </InputComponent>
                <InputComponent titulo="Quilometragem Inicial" class="w-100">
                    <input
                        type="text"
                        class="form-control"
                        :value="$store.state.item.valor_diaria"
                        disabled
                    />
                </InputComponent>
            </div>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent titulo="Data Final Realizada" class="w-100">
                    <input
                        type="datetime-local"
                        class="form-control"
                        :value="$store.state.item.data_final_realizado_periodo"
                        disabled
                    />
                </InputComponent>
                <InputComponent titulo="Quilometragem Final" class="w-100">
                    <input
                        type="text"
                        class="form-control"
                        :value="$store.state.item.km_final"
                        disabled
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
                Fechar
            </button>
        </template>
    </Modal>
    <!--fim modal de visualizar modelo -->

    <!-- inicio modal de remoção de modelo-->
    <Modal id="modalLocacaoRemover" titulo="Remover Locação">
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
            <InputComponent titulo="Cliente">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.cliente?.nome"
                    disabled
                />
            </InputComponent>
            <InputComponent titulo="Placa">
                <input
                    type="text"
                    class="form-control"
                    :value="$store.state.item.carro?.placa"
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
    <Modal id="modalLocacaoEditar" titulo="Editar Locação">
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
                    titulo="Cliente"
                    id="atualizarCliente"
                    id-help="atualizarClienteHelp"
                    texto-ajuda="Informe o cliente da locação"
                    class="w-100"
                >
                    <div v-if="$store.state.item && $store.state.item.cliente">
                        <select
                            class="form-select"
                            name="cliente"
                            id="atualizarCliente"
                            v-model="$store.state.item.cliente.id"
                            aria-describedby="atualizarClienteHelp"
                        >
                            <option value="" disabled>Selecionar</option>
                            <option
                                v-for="cliente in clientes.data"
                                :key="cliente.id"
                                :value="cliente.id"
                            >
                                {{ cliente.nome }}
                            </option>
                        </select>
                    </div>
                </InputComponent>
                <InputComponent
                    titulo="Carro"
                    id="atualizarCarro"
                    id-help="atualizarCarroHelp"
                    texto-ajuda="Informe o carro da locação"
                    class="w-100"
                >
                    <div v-if="$store.state.item && $store.state.item.carro">
                        <select
                            class="form-select"
                            name="carro"
                            id="atualizarcarro"
                            v-model="$store.state.item.carro.id"
                            aria-describedby="atualizarCarroHelp"
                        >
                            <option value="" disabled>Selecionar</option>
                            <option
                                v-for="carro in carros.data"
                                :key="carro.id"
                                :value="carro.id"
                            >
                                {{ carro.modelo.nome }} - {{ carro.placa }}
                            </option>
                        </select>
                    </div>
                </InputComponent>
            </div>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Data Inicial"
                    id="atualizarDataInicial"
                    id-help="atualizarDataInicialHelp"
                    texto-ajuda="Informe a data de início da locação"
                    class="w-100"
                >
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="atualizarDataInicial"
                        aria-describedby="atualizarDataInicialHelp"
                        placeholder="Data Inicial"
                        v-model="$store.state.item.data_inicio_periodo"
                    />
                </InputComponent>
                <InputComponent
                    titulo="Data Final Prevista"
                    id="atualizarDataFinalPrevista"
                    id-help="atualizarDataFinalPrevistaHelp"
                    texto-ajuda="Informe a data final prevista da locação"
                    class="w-100"
                >
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="atualizarDataFinalPrevista"
                        aria-describedby="atualizarDataFinalPrevistaHelp"
                        placeholder="Data Final Prevista"
                        v-model="$store.state.item.data_final_previsto_periodo"
                    />
                </InputComponent>
            </div>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Valor Diária"
                    id="atualizarValorDiaria"
                    id-help="atualizarValorDiariaHelp"
                    texto-ajuda="Informe o valor da diária da locação"
                    class="w-100"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="atualizarValorDiaria"
                        aria-describedby="atualizarValorDiariaHelp"
                        placeholder="Valor Diária"
                        v-model="$store.state.item.valor_diaria"
                    />
                </InputComponent>
                <InputComponent
                    titulo="Quilometragem Inicial"
                    id="atualizarKmInicial"
                    id-help="atualizarKmInicialHelp"
                    texto-ajuda="Informe a quilometragem inicial da locação"
                    class="w-100"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="atualizarKmInicial"
                        aria-describedby="atualizarKmInicialHelp"
                        placeholder="Valor Diária"
                        v-model="$store.state.item.km_inicial"
                    />
                </InputComponent>
            </div>
            <div class="form-group d-flex mb-3 gap-3">
                <InputComponent
                    titulo="Data Final Realizada"
                    id="atualizarDataFinalRealizado"
                    id-help="atualizarDataFinalRealizadoHelp"
                    texto-ajuda="Informe a data final da locação"
                    class="w-100"
                >
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="atualizarDataFinalRealizado"
                        aria-describedby="atualizarDataFinalRealizadoHelp"
                        placeholder="Data Final Prevista"
                        v-model="$store.state.item.data_final_realizado_periodo"
                    />
                </InputComponent>
                <InputComponent
                    titulo="Quilometragem Final"
                    id="atualizarKmFinal"
                    id-help="atualizarKmFinalHelp"
                    texto-ajuda="Informe a quilometragem final da locação"
                    class="w-100"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="atualizarKmFinal"
                        aria-describedby="atualizarKmFinalHelp"
                        placeholder="Valor Diária"
                        v-model="$store.state.item.km_final"
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
