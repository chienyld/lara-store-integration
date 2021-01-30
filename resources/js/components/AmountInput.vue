<template>
<div>
<div style="display:flex;align-items:center;justify-content:center">
<div style="float:left"><i @click="decreaseValue()" class="fas fa-minus-circle fa-2x"></i></div>

<div style="width:63%;float:left">
  <input class="form-control" name="qty" type="number" v-model="amt" value="qty" style="border-radius:40px"/>
</div>

<div style="float:left"><i v-on:click="increaseValue()" class="fas fa-plus-circle fa-2x"></i></div>
</div>

<div style="display:flex;align-items:center;justify-content:center">
<div style="width:80%;margin:15px">
<input v-if="this.amt>0" class="btn-primary col-10 offset-1" type="submit" value="加入清單" style="border-radius:50px">
</div>
</div>
</div>
</template>
<style scoped>
i{
    color: #3097D1;
    padding: 3px;
}
</style>
<script>
    export default {
        mounted() {
            console.log('Component mounted.');
        },

        data: function () {
            return {
                amt:0,
            }
        },
        methods:{
            increaseValue: function() {
                var value = this.amt;
                value++;
                this.amt = value;
                },
            decreaseValue: function() {
                var value = this.amt;
                if(value>0){
                    value--;
                    this.amt = value;
                }
                else{
                    this.amt = value;
                }
                }
        },
        computed: {
            actionButton:function (){
                if (this.amt){
                return ''}
                else{
                return ''
                }
            }
        }
    }
</script>

