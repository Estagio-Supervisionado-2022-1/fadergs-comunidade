<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>
                    <span>
                        Teste : 
                        {{ count }}
                    </span>
                    <span v-on:click="actionDispa"  >BTN</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapMutations } from 'vuex';
    
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        computed: {
            ...mapState('operator',['count']),
            },
        methods: {
            actionDispa(){
                this.$store.dispatch('operator/increment')
            }
        }
    }
</script>
