<template>
    <div>
        <br>
        <hr>

        <div :id="'reply-' + id" class="panel">
            <div class="panel-heading">
                <div class="level">
                    <h5 class="flex">
                            <a :href="'/profiles/'+data.owner.name" class="flex"
                        v-text="data.owner.name">
                        </a> said <span v-text="ago"></span>
                    </h5>

                    <div v-if=""> <!--IMP-->
                        <favorite :reply="data"></favorite>
                    </div>

                </div>
            </div>
        </div>
        <br>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>

                <button class="btn btn-xs btn-dark" @click="update">Update</button>
                <button class="btn btn-xs btn-warning" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>
        <br>
<!--
        @can('update', $reply) IMP
-->
        <div class="panel-footer level" v-if="">
            <button class="btn btn-info btn-xs mr-1"@click="editing = true">Edit</button>
            <button class="btn btn-danger btn-xs mr-1"@click="destroy">Delete</button>
        </div>
<!--
        @endcan
-->
    </div>


</template>
<script>

import Favorite from './Favorite.vue';
import moment from 'moment';

    export default {

        props: ['data'],

        components: {Favorite},


        data(){

            return{

                editing: false,
                id: this.data.id,
                body: this.data.body

            }
        },

        computed: {

            ago(){

                return moment(this.data.created_at).fromNow();

            }
/*
            singedIn(){

                return window.App.singedIn;

            },

            canUpdate(){

                return this.authorize(user => this.data.user_id == user.id)

            }*/

        },

        methods: {

            update(){

                axios.patch('/replies/' + this.data.id,{

                    body:this.body

                })

                this.editing = false;

                flash("Updated");

            },

            destroy(){

                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id)

/*                $(this.$el).fadeOut(300, ()=> {

                    flash('Your reply has beed deleted')

                });*/

            }

        }

    }
</script>

