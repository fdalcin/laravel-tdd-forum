<template>
    <div :id="'reply-' + data.id" class="card mt-4 mb-2">
        <div class="card-header level">
            <h5 class="flex">
                <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name"></a>
                said <span v-text="ago"></span>
            </h5>

            <div v-if="signedIn">
                <favorite :reply="data"></favorite>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="from-group mb-2">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                
                <button class="btn btn-sm btn-success" @click="update">Update</button>
                <button class="btn btn-link" @click="cancel">Cancel</button>
            </div>

            <div v-else v-text="body"></div>
        </div>

        <div class="card-footer level" v-if="canUpdate">
            <button class="btn btn-sm mr-2" @click="editing = true">Edit</button>
            <button class="btn btn-link" @click="destroy">Delete</button>
        </div>
    </div>
</template>

<script>
import Favorite from './Favorite.vue';
import moment from 'moment';

export default {
    props: ['data'],

    components: {
        Favorite
    },

    data() {
        return {
            body: this.data.body,
            editing: false
        };
    },

    computed: {
        ago() {
            return moment(this.data.created_at).fromNow();
        },

        canUpdate() {
            return this.authorize(user => this.data.user_id == user);
        },

        signedIn() {
            return window.App.signedIn;
        }
    },

    methods: {
        cancel() {
            this.body = this.data.body;

            this.editing = false;
        },

        destroy() {
            axios
                .delete('/replies/' + this.data.id)
                .then(() => this.$emit('deleted', this.data.id));
        },

        update() {
            axios
                .patch('/replies/' + this.data.id, { body: this.body })
                .then(() => {
                    this.editing = false;

                    flash('Your reply has been updated.');
                });
        }
    }
};
</script>