<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value">

        <trix-editor ref="editor" input="trix" :placeholder="placeholder"></trix-editor>
    </div>
</template>

<script>
    import Trix from 'trix';

    export default {
        props: ['name', 'value', 'placeholder', 'shouldClear'],

        watch: {
            shouldClear() {
                this.$refs.editor.value = '';
            }
        },

        mounted() {
            this.$refs.editor.addEventListener('trix-change', e => this.$emit('input', e.target.innerHTML));
        }
    }
</script>