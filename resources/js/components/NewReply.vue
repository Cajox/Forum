<template>

<!--
    <form method="post" action="{{route('reply.store', [$thread->channel->id, $thread->id ])}}">
-->
        <textarea
            name="body"
            id="body"
            class="form-control"
            placeholder="Reply something"
            cols="30"
            rows="5"
            required
            v-model="body">

        </textarea>
    <button type="submit" class="btn btn-danger" @click="addReply"> Post </button>
<!--
    </form>
-->


</template>

<script>
export default {

    data(){

        return {

            body: ''

        }

    },

    methods: {

    }

}
</script>

<style scoped>

</style>
