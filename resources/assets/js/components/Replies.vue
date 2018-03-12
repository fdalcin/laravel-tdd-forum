<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <div class="mt-4">
            <p v-if="$parent.locked" class="text-center">
                This thread has been locked. No more replies are allowed.
            </p>

            <new-reply @created="add" v-else></new-reply>
        </div>
    </div>
</template>

<script>
import NewReply from './NewReply.vue';
import Reply from './Reply.vue';
import collection from '../mixins/collection';

export default {
    components: { NewReply, Reply },

    mixins: [collection],

    data() {
        return {
            dataSet: false
        };
    },

    created() {
        this.fetch();
    },

    methods: {
        fetch(page) {
            axios.get(this.url(page)).then(this.refresh);
        },

        refresh({ data }) {
            this.dataSet = data;

            this.items = data.data;

            window.scrollTo(0, 0);
        },

        url(page) {
            if (!page) {
                const query = location.search.match(/page=(\d+)/);

                page = query ? query[1] : 1;
            }

            return `${location.pathname}/replies?page=${page}`;
        }
    }
};
</script>
