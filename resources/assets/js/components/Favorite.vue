<template>
  <button :class="classes" @click="toggle">
      <span class="fa fa-heart mr-1"></span>
      <span v-text="count"></span>
  </button>
</template>

<script>
export default {
    props: ['reply'],

    data() {
        return {
            active: this.reply.isFavorited,
            count: this.reply.favoritesCount
        };
    },

    computed: {
        classes() {
            return [
                'btn',
                'btn-sm',
                this.active ? 'btn-primary' : 'btn-default'
            ];
        },
        endpoint() {
            return '/replies/' + this.reply.id + '/favorites';
        }
    },

    methods: {
        toggle() {
            return this.active ? this.unfavorite() : this.favorite();
        },

        favorite() {
            axios.post(this.endpoint).then(() => {
                this.active = true;

                this.count++;
            });
        },

        unfavorite() {
            axios.delete(this.endpoint).then(() => {
                this.active = false;

                this.count--;
            });
        }
    }
};
</script>

