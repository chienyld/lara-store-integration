<template>
<div>
    <button class="statusbtn btn" v-bind:class="actionButton" v-text="statusText" style="margin:3px;height:36px;width:83px"></button>
</div>
</template>

<script>
    export default {
        props: ['datastatus'],
        mounted() {
        },

        data: function () {
            return {      
                status: this.datastatus,
            }
        },
        computed: {
            statusText:function() {
                if (this.status ==false){
                return '處理中'}
                else{
                return '已出貨'
                }
            },
            actionButton:function (){
                if (this.status ==false){
                return 'btn-danger'}
                else{
                return 'btn-success'
                }
            }
        }
    }
</script>
