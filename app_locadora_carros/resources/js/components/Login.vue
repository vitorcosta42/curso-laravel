<script>
export default {
    props: ["csrf_token"],
    data() {
        return {
            email: "",
            password: "",
        };
    },
    methods: {
        login(e) {
            let url = "http://localhost:8000/api/login";
            let config = {
                method: "post",
                body: new URLSearchParams({
                    email: this.email,
                    password: this.password,
                }),
            };
            fetch(url, config).then((response) => response.json())
            .then((data) => {
                if (data.token) {
                    document.cookie = "token=" + data.token + ";SameSite=Lax";
                }
                e.target.submit();
            });
        },
    },
};
</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">Login (Vue)</div>
                    <div class="card-body">
                        <form
                            method="POST"
                            action=""
                            @submit.prevent="login($event)"
                        >
                            <input
                                type="hidden"
                                name="_token"
                                :value="csrf_token"
                            />
                            <div class="row mb-3">
                                <label
                                    for="email"
                                    class="col-md-4 col-form-label text-md-end"
                                    >E-mail</label
                                >

                                <div class="col-md-6">
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        name="email"
                                        required
                                        autocomplete="email"
                                        autofocus
                                        v-model="email"
                                    />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label
                                    for="password"
                                    class="col-md-4 col-form-label text-md-end"
                                    >Senha</label
                                >

                                <div class="col-md-6">
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        v-model="password"
                                    />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="remember"
                                            id="remember"
                                        />

                                        <label
                                            class="form-check-label"
                                            for="remember"
                                        >
                                            Mantenha-me conectado
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="#">
                                        Esqueci a senha
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

