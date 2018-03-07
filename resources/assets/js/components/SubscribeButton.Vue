<template>
    <button :class="classes" @click="subscribe" v-text="subscribeText"></button>
</template>

<script>
export default {
    props: ['active'],

    data() {
        return {
            isSubscribedTo: this.active
        };
    },
    computed: {
        classes() {
            return [
                'btn',
                this.isSubscribedTo ? 'btn-secondary' : 'btn-default'
            ];
        },

        subscribeText() {
            return this.isSubscribedTo ? 'Unsubscribe' : 'Subscribe';
        }
    },

    methods: {
        subscribe() {
            axios[this.isSubscribedTo ? 'delete' : 'post'](
                location.pathname + '/subscriptions'
            ).then(() => (this.isSubscribedTo = !this.isSubscribedTo));
        }
    }
};
</script>
