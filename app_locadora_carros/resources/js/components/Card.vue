<script>
export default {
    props: ["titulo"],
};
</script>
<template>
    <div class="card mb-3">
        <div class="card-header bg-primary text-white d-flex align-items-center">{{ titulo }}
            <slot name="refresh"></slot>
        </div>
        <div class="card-body">
            <slot name="conteudo"></slot>
        </div>
        <div class="card-footer">
            <div class="d-flex">
                <slot name="rodape"></slot>
            </div>
        </div>
    </div>
</template>
