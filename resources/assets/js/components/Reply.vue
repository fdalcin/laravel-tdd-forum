<script>
export default {
    props: ['attributes'],

    data() {
        return {
            body: this.attributes.body,
            editing: false
        };
    },

    methods: {
        cancel() {
            this.body = this.attributes.body;

            this.editing = false;
        },

        destroy() {
            axios.delete('/replies/' + this.attributes.id).then(() => {
                $(this.$el).fadeOut(300);

                flash('Your reply has been deleted.');
            });
        },

        update() {
            axios
                .patch('/replies/' + this.attributes.id, { body: this.body })
                .then(() => {
                    this.editing = false;

                    flash('Your reply has been updated.');
                });
        }
    }
};
</script>