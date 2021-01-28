<template>
<div>
    <button style="margin-top:5px" v-bind:class="actionButton" @click="checkstatus" v-text="statusText"></button>
</div>
</template>

<script>
    export default {
        props: ['active','userid'],
        mounted() {
            console.log('Component mounted.');
        },

        data: function () {
            return {      
                status: this.active,
                id:this.userid
                
            }
        },
        computed: {
            statusText:function() {
                //return (this.status) ? '確認歸還' : '取消歸還';
                if (this.status==true){
                return '停權'}
                else{
                return '恢復'
                }
            },
            actionButton:function (){
                if (this.status==true){
                return 'btn btn-danger'}
                else{
                return 'btn btn-info'
                }
            }
        },
        methods: {  
            checkstatus() {
                //var _token = '<?php echo csrf_token() ?>';
                var _this=this;
                this.$http.post('/account/edit',{
                    id:_this.userid,
                    status:_this.active
                }).then(function(success) {
                    window.location = "/account";
                        }, function(error) {
                            console.log(error);
                        });
            }
        }
    }
</script>
