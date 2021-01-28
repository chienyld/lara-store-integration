<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>List</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
</head>
<body>
@include('inc.navbar')
<div id="app">
    <div class="container">
            <div class="col-lg-6 col-lg-offset-3">
                <h2>我的清單</h2>
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
                        <td>@{{ item.id }}</td>
                        <td>@{{ item.name }}</td>
                        <td>@{{ item.quantity }}</td>
                        <td>@{{ item.price }}</td>
                        <td>
                        <button v-on:click="removeItem(item.id)" class="btn btn-sm btn-danger">移除</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table">
                    <tr>
                        <td>品項數:</td>
                        <td>@{{itemCount}}</td>
                    </tr>
                    <tr>
                        <td>總數量:</td>
                        <td>@{{ details.total_quantity }}</td>
                    </tr>
                    <tr>
                        <td>總金額:</td>
                        <td>@{{ '$' + details.total.toFixed(2) }} </td>
                    </tr>
                </table>
                <br>
                <div style="padding-left:0px;padding-right:0px;padding-bottom:5px;margin:0" class="col-lg-6 col-sm-12">
                <el-date-picker
                    v-model="value2"
                    format="yyyy-MM-dd" 
                    value-format="yyyy-MM-dd" 
                    type="date"
                    :picker-options="pickerOptions"
                    placeholder="借取日期" style="width: 99%">
                </el-date-picker><br></div>
                <div style="padding-left:0px;padding-right:0px;padding-bottom:5px;margin:0" class="col-lg-6 col-sm-12">
                <el-date-picker
                    v-model="value1"
                    format="yyyy-MM-dd" 
                    value-format="yyyy-MM-dd" 
                    type="date"
                    placeholder="歸還日期" 
                    :picker-options="pickerOptions2"
                    style="width: 99%">
                </el-date-picker></div><br><br><br>
                <div class="container"></div>
                <div class="container">
                <div class="row">
                <input type="radio" v-model="time" v-bind:value="12"> 中午 １２ 點 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <input type="radio" v-model="time" v-bind:value="18"> 下午 １８ 點</div></div><br><br>
                <button v-on:click="sendItem()" class="btn-primary">結帳</button>
                <p v-text=""></p>
        </div>
    </div>
</div>
</div>
<script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
<script src="https://unpkg.com/vue"></script>
<script src="https://cdn.jsdelivr.net/vue.resource/1.3.1/vue-resource.min.js"></script>
<!--
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<script>
    (function($) {
        var _token = '<?php echo csrf_token() ?>';
        $(document).ready(function() {
            var app = new Vue({
                el: '#app',
                data: {
                    pickerOptions: {
                        disabledDate(time) {
                            return time.getTime() < Date.now();
                        },
                        },
                    pickerOptions2: {
                        disabledDate(time) {
                            return time.getTime() < this.value;
                        },
                        },
                        value:'',
                        value1: '',
                        value2: '',
                        time:'',
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
                },
                watch:{
                    value2: function(values){
                        value=Date.parse(this.value2);
                    }
                },
                mounted:function(){
                    var _this=this;
                    this.loadItems();
                },
                methods: {
                    addItem: function() {
                        var _this = this;
                        this.$http.post('/cart',{
                            _token:_token,
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
                        var _this = this;
                        var arr = [];
                        this.items.forEach(function(item){
                            if(_this.value1<=_this.value2){
                                console.log('日期錯誤');
                            }else{
                            var jsonObj = {
                            _token:_token,
                            id:item.id,
                            name:item.name,
                            deposit:item.price,
                            qty:item.quantity,
                            borrowdate:_this.value2,
                            returndate:_this.value1,
                            time_period:_this.time  };
                            arr.push(jsonObj);
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
                            _token:_token,
                            id:uid,
                            name:uname,
                            deposit:uprice,
                            qty:uqty,
                            borrowdate:_this.value2,
                            returndate:_this.value1,
                            time_period:_this.time });
                        _this.$http.post('/borrows',{
                            _token:_token,
                            id:uid,
                            name:uname,
                            deposit:uprice,
                            qty:uqty,
                            borrowdate:_this.value2,
                            returndate:_this.value1,
                            time_period:_this.time
                            })
                        .then(function(success) {
                            alert(_this.item.name+'申請成功！請於'+_this.value2+" 當日"+_this.time+'點至學物處領取');
                            console.log(success); 
                            console.log(success.data); 
                        });
                    };
                        _this.items=[];
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
                    addCartCondition: function() {
                        var _this = this;
                        this.$http.post('/cart/conditions',{
                            _token:_token,
                            name:_this.cartCondition.name,
                            type:_this.cartCondition.type,
                            target:_this.cartCondition.target,
                            value:_this.cartCondition.value,
                        }).then(function(success) {
                            _this.loadItems();
                        }, function(error) {
                            console.log(error);
                        });
                    },
                    clearCartCondition: function() {
                        var _this = this;
                        this.$http.delete('/cart/conditions?_token=' + _token).then(function(success) {
                            _this.loadItems();
                        }, function(error) {
                            console.log(error);
                        });
                    },
                    removeItem: function(id) {
                        var _this = this;
                        this.$http.delete('/cart/'+id,{
                            params: {
                                _token:_token
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
            });
        });
    })(jQuery);
</script>

</body>
</html>