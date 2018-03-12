<template>
    <div :id="'reply-' + reply.id" class="card mt-4 mb-2" :class="isBest? 'border-success' : ''">
        <div class="card-header level">
            <h5 class="flex">
                <a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name"></a>
                said <span v-text="ago"></span>
            </h5>

            <div v-if="signedIn">
                <favorite :reply="reply"></favorite>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="from-group mb-2">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                    <button type="button" class="btn btn-link" @click="cancel">Cancel</button>
                </form>
            </div>

            <div v-else v-html="body"></div>
        </div>

        <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-sm mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-link" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-sm btn-default ml-a"
                    @click="markAsBest"
                    v-show="authorize('owns', reply.thread) && !isBest">
                Best reply?
            </button>
        </div>
    </div>
</template>

<script>
import Favorite from './Favorite.vue';
import moment from 'moment';

export default {
    props: ['reply'],

    components: {
        Favorite
    },

    data() {
        return {
            body: this.reply.body,
            editing: false,
            isBest: this.reply.isBest
        };
    },

    created() {
        window.events.$on(
            'best-reply-selected',
            id => (this.isBest = this.reply.id === id)
        );
    },

    computed: {
        ago() {
            return moment(this.reply.created_at).fromNow();
        }
    },

    methods: {
        cancel() {
            this.body = this.reply.body;

            this.editing = false;
        },

        destroy() {
            axios
                .delete('/replies/' + this.reply.id)
                .then(() => this.$emit('deleted', this.reply.id));
        },

        update() {
            axios
                .patch('/replies/' + this.reply.id, { body: this.body })
                .then(() => {
                    this.editing = false;

                    flash('Your reply has been updated.');
                })
                .catch(({ response }) => flash(response.data, 'danger'));
        },

        markAsBest() {
            axios
                .post('/replies/' + this.reply.id + '/best')
                .then(() =>
                    window.events.$emit('best-reply-selected', this.reply.id)
                )
                .catch(({ response }) => flash(response.data, 'danger'));
        }
    }
};
</script>