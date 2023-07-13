<script>
export default {
    props: ["id", "titulo", "idHelp",'textoAjuda'],
};
</script>

<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{ titulo }}</label>
        <slot></slot>
        <div :id="idHelp" class="text-muted form-text">
            {{textoAjuda}}
        </div>
    </div>
</template>
