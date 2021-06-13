<template>
<div class="container">
    <div v-if="info" v-html="info"></div>
            <div v-else class="col-lg-6 col-lg-offset-3">
                <a href="/posts" class="btn btn-default">回首頁</a>
                <h3>我的購物清單</h3>
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>品項</th>
                        <th>數量</th>
                        <th>金額</th>
                        <th>編輯</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in items">
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.price }}</td>
                        <td>
                        <button v-on:click="removeItem(item.id)" class="btn btn-sm btn-danger">移除</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table">
                    <tr>
                        <td>種類:</td>
                        <td>{{itemCount}}</td>
                    </tr>
                    <tr>
                        <td>數量:</td>
                        <td>{{ details.total_quantity }}</td>
                    </tr>
                    <tr>
                        <td>小計:</td>
                        <td>{{ '$' + details.total }} </td>
                    </tr>
                    <tr>
                        <td>手續費:</td>
                        <td v-if="shipping==0">{{ '$' + 10 }} </td>
                        <td v-else>{{ '$' + 10 }} </td>
                    </tr>
                    <tr>
                        <td>總金額:</td>
                        <td v-if="shipping==1">{{ '$' + details.total }} </td>
                        <td v-else>${{ parseInt(details.total)+10 }} </td>
                    </tr>
                </table>
                <!--<input type="radio" id="store" v-model="shipping" value="0"><label for="store"> &nbsp 門市自取</label>&nbsp&nbsp&nbsp&nbsp&nbsp
                <input type="radio" id="ship" v-model="shipping" value="1"><label for="ship"> &nbsp 宅配寄送</label>-->
                <input v-model="address" placeholder="房號/部門/棟別/樓層" class="form-control" style="border-radius:8px">
                <!--<button v-if="shipping==1" onclick="location.href='http://localhost:8000/choose'" class="btn-primary" style="border-radius:8px">choose</button>-->
                <br>
                
                
                <button v-if="shipping==0 && address && items.length>0" v-on:click="sendItem()" class="btn-primary">結帳</button>
        </div>
    </div>

</template>
<script>
export default {
    props: ['token'],
    data: function (){
        return {
            info:'',
            shipping:'0',
            address:'',
            details: {
                sub_total: 0,
                total: 0,
                total_quantity: 0
            },
            itemCount: 0,
            items: [],
            item: {
                id: '',
                name: '',
                price: 0.00,
                qty: 1
            },
            cartCondition: {
                name: '',
                type: '',
                target: '',
                value: '',
                attributes: {
                    description: 'Value Added Tax'
                }
            },
        options: {
            target: [
                {label: 'Apply to SubTotal', key: 'subtotal'},
                {label: 'Apply to Total', key: 'total'}
            ]
        }
        }
    },
    watch:{
        info:function(val){
        if(val!=''){
            let i =0;
            for(i=0;i<=5;i++){
                setTimeout(() => {             
                    document.getElementById('__paymentButton').click();}, 3000);
                }
            }
        }
    },
    mounted:function(){
        var _this=this;
        this.loadItems();
        console.log(this.token);
        //setTimeout(() => {document.getElementById('__paymentButton').click();}, 3000);
    },
    methods: {
        addItem: function() {
            var _this = this;
            this.$http.post('/cart',{
                _token:_this.token,
                id:_this.item.id,
                name:_this.item.name,
                price:_this.item.price,
                qty:_this.item.qty
            }).then(function(success) {
                _this.loadItems();
            }, function(error) {
                console.log(error);
            });
        },
        sendItem: function() {
            console.log(this.address);
            var _this = this;
            var arr = [];
            this.items.forEach(function(item){
                if(_this.value1<=_this.value2){
                    console.log('日期錯誤');
                }else{
                var jsonObj = {
                _token:_this.token,
                id:item.id,
                name:item.name,
                deposit:item.price,
                qty:item.quantity
                };
                arr.push(jsonObj);
                    _this.$http.delete('/cart/'+item.id,{
                    params: {
                            _token:_this.token
                        }
                    }).then(function(success) {
                        _this.loadItems();
                    }, function(error) {
                        console.log(error);
                    });  
            };
            });
            if(_this.value1<=_this.value2){
                alert('日期錯誤');
            }else{
            console.log(arr);
            let data = JSON.parse(JSON.stringify(arr));
            var i; 
            let uid = [];
            let uname = [];
            let uprice = [];
            let uqty = [];
            for(i=0;i<arr.length;i++){
                uid.push(arr[i].id);
                uname.push(arr[i].name);
                uprice.push(arr[i].deposit);
                uqty.push(arr[i].qty);
            };
            console.log({
                _token:_this.token,
                id:uid,
                name:uname,
                deposit:uprice,
                qty:uqty
                });
            _this.$http.post('/borrows',{
                _token:_this.token,
                shipping:_this.shipping,
                address:String(_this.address),
                id:uid,
                name:uname,
                deposit:uprice,
                qty:uqty
                })
            .then(function(success) {
                //alert(_this.item.name+'申請成功！請於');
                //console.log(success); 
                //console.log(success.data); 
                this.info=success.data;
                //this.document.getElementById("__ecpayForm").submit(); 
                //this.$refs.submitBtn.click();
                
            });
        };
            _this.items=[];
            console.log(_this.items); 
            console.log(_this.items); 
                                  
            /*var _this = this;
            this.$http.post('/borrows',{
                _token:_token,
                id:_this.item.id,
                name:_this.item.name,
                deposit:_this.item.price,
                qty:_this.item.qty
            }).then(function(success) {
                window.location.href = "/";
            }, function(error) {
                console.log(error);
            });*/
        },
        removeItem: function(id) {
            var _this = this;
            this.$http.delete('/cart/'+id,{
                params: {
                    _token:_this.token
                }
            }).then(function(success) {
                _this.loadItems();
            }, function(error) {
                console.log(error);
            });
        },
        loadItems: function() {
            var _this = this;
            this.$http.get('/cart',{
                params: {
                    limit:10
                }
            }).then(function(success) {
                _this.items = success.body.data;
                _this.itemCount = success.body.data.length;
                _this.loadCartDetails();
            }, function(error) {
                console.log(error);
            });
        },
        loadCartDetails: function() {
            var _this = this;
            this.$http.get('/cart/details').then(function(success) {
                _this.details = success.body.data;
            }, function(error) {
                console.log(error);
            });
        }
    }
};
</script>