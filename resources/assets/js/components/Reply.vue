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
        update() {
            axios
                .patch('/replies/' + this.attributes.id, { body: this.body })
                .then(() => this.finishUpdate());
        },

        finishUpdate() {
            this.editing = false;

            flash('Your reply has been updated.');
        }
    }
};
</script>