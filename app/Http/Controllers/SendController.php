<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Borrow;
use App\Models\Post;
use App\Models\User;
use App\Models\Order;
use DB;
use \ECPay_PaymentMethod as ECPayMethod;
use \EcpayLogistics;
use \EcpayLogisticsType;
use \EcpayLogisticsSubType;
use \EcpayIsCollection;
use \EcpayDevice;
use \EcpayTestMerchantId;
use \EcpayUrl;
use \EcpayTestUrl;
use \EcpayTemperature;
use \EcpayDistance;
use \EcpaySpecification;
use \EcpayScheduledPickupTime;
use \EcpayScheduledDeliveryTime;
use \EcpayStoreType;
use \EcpayCheckMacValue;
use \EcpayIo;
//use TsaiYiHua\ECPay\Checkout;

class SendController extends Controller 
{
    /**
     * Create a new controller instance.
     *
     * @return void   
     */
    protected $checkout;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        //$this->checkout = $checkout;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('/home');
    }
    public function lookup(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required'
        ]);
        //$posts = Post::all();
        //return Post::where('title', 'Post Two')->get();
        //$posts = DB::select('SELECT * FROM posts');
        //$posts = Post::orderBy('title','desc')->take(1)->get();
        //$posts = Post::orderBy('title','desc')->get();
        $order_id = $request->input('order_id');
        $borrows = Borrow::where('order_id',$order_id)->orderBy('id','desc')->paginate(15);
        $order = Order::where('order_id',$order_id)->first();

        return view('/send')->with('borrows', $borrows)->with('order',$order);
    }
    public function type0(){
        $posts0 = Post::orderBy('created_at','desc')->where('type', '0')->paginate(10);
        return view('posts.index')->with('posts', $posts0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function martmap() {
        define('HOME_URL', 'http://www.sample.com.tw/logistics_dev');
        try {
            $AL = new EcpayLogistics();
            $AL->Send = array(
                'MerchantID' => '2000132',
                'MerchantTradeNo' => 'no' . date('YmdHis'),
                'LogisticsSubType' => EcpayLogisticsSubType::UNIMART,
                'IsCollection' => EcpayIsCollection::NO,
                'ServerReplyURL' => HOME_URL . '/ServerReplyURL.php',
                'ExtraData' => '??????????????????',
                'Device' => EcpayDevice::PC
            );
            // CvsMap(Button??????, Form target)
            $html = $AL->CvsMap('????????????(??????)');
            echo $html;
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    public function store(Request $request) 
    {
        /*$this->validate($request, [
            'id' => 'required',
            'name' => 'required',
            'deposit' => 'required',
            'qty' => 'required',
            'shipping'=>'required'
        ]);*/
        $user_order_id = strval(auth()->user()->id);
        $user_order_id .= time(); 

        $ids = $request->input('id');
        $items = $request->input('name');
        $deposits = $request->input('deposit');
        $qtys = $request->input('qty');
        $shipping = $request->input('shipping');
        $totalAdding = 0;
        for($i=0 ; $i< count($ids) ; $i++ ){
            $borrow=new Borrow;
            $borrow->order_id = $user_order_id;
            $borrow->borrow_id = $ids[$i];
            $borrow->user_id = auth()->user()->id; // get this from session or wherever it came from
            $borrow->user_name = auth()->user()->name; // get this from session or wherever it came from
            $borrow->name = $items[$i];
            $borrow->depositamt = $deposits[$i];
            $borrow->qty = $qtys[$i];
            $borrow->status = false;
            $borrow->save();
    
            $post = Post::find($borrow->borrow_id);
            $post->inventory = $post->inventory-$borrow->qty;
            $post->save();
            
            $totalAdding += $deposits[$i]*$qtys[$i];
            }
            $order = new Order;
            $order->order_id = $user_order_id;
            $order->user_id = auth()->user()->id;
            $order->user_name = auth()->user()->name;
            $order->shipping = $shipping;
            $order->payment = false;
            if($shipping==0){ 
                $order->total = $totalAdding+10;
                $order->address = $request->input('address');
            }else{
                $order->total = $totalAdding+60;
                $order->address = $request->input('address');
            }
            
            $order->status = false;
            $order->save();
            
            /*$this->validate($request, [
                'id' => 'required',
                'name' => 'required',
                'deposit' => 'required',
                'qty' => 'required',
                'returndate' => 'required',
                'borrowdate' => 'required',
                'time_period' => 'required',
            ]);
            
            $borrow=new Borrow;
            $borrow->borrow_id = $request->input('id');
            $borrow->user_id = auth()->user()->id; // get this from session or wherever it came from
            $borrow->user_name = auth()->user()->name; // get this from session or wherever it came from
            $borrow->name = $request->input('name');
            $borrow->depositamt = $request->input('deposit');
            $borrow->qty = $request->input('qty');
            $borrow->borrow_date = $request->input('borrowdate');
            $borrow->return_date = $request->input('returndate');
            $borrow->time_period = $request->input('time_period');
            $borrow->status = false;
            $borrow->save();
    
            $post = Post::find($borrow->borrow_id);
            $post->inventory = $post->inventory-$borrow->qty;
            $post->save();*/
        

        if($request->input('shipping')==1){
            try {
                $AL = new EcpayLogistics();
                $AL->HashKey = '5294y06JbISpM5x9';
                $AL->HashIV = 'v77hoKGq4kWxNNIS';
                $AL->Send = array(
                    'MerchantID' => '2000132',
                    'MerchantTradeNo' => 'no' . date('YmdHis'),
                    'MerchantTradeDate' => date('Y/m/d H:i:s'),
                    'LogisticsType' => EcpayLogisticsType::CVS,
                    'LogisticsSubType' => EcpayLogisticsSubType::UNIMART,
                    'GoodsAmount' => 1500,
                    'CollectionAmount' => 10,
                    'IsCollection' => EcpayIsCollection::YES,
                    'GoodsName' => '????????????',
                    'SenderName' => '???????????????',
                    'SenderPhone' => '0226550115',
                    'SenderCellPhone' => '0911222333',
                    'ReceiverName' => '???????????????',
                    'ReceiverPhone' => '0226550115',
                    'ReceiverCellPhone' => '0933222111',
                    'ReceiverEmail' => 'test_emjhdAJr@test.com.tw',
                    'TradeDesc' => '??????????????????',
                    'ServerReplyURL' => HOME_URL . '/ServerReplyURL.php',
                    'LogisticsC2CReplyURL' => HOME_URL . '/LogisticsC2CReplyURL.php',
                    'Remark' => '????????????',
                    'PlatformID' => '',
                );
        
                $AL->SendExtend = array(
                    'ReceiverStoreID' => '991182',
                    'ReturnStoreID' => '991182'
                );
                // BGCreateShippingOrder()
                $Result = $AL->BGCreateShippingOrder();
                echo '<pre>' . print_r($Result, true) . '</pre>';
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        }
        if($request->input('shipping')==2){
            try {
                define('HOME_URL', 'http://www.sample.com.tw/logistics_dev');
                $AL = new \EcpayLogistics();
                $AL->HashKey = '5294y06JbISpM5x9';
                $AL->HashIV = 'v77hoKGq4kWxNNIS';
                $AL->Send = array(
                    'MerchantID' => '2000132',
                    'MerchantTradeNo' => 'no' . date('YmdHis'),
                    'MerchantTradeDate' => date('Y/m/d H:i:s'),
                    'LogisticsType' => EcpayLogisticsType::CVS,
                    'LogisticsSubType' => EcpayLogisticsSubType::UNIMART,
                    'GoodsAmount' => 1500,
                    'CollectionAmount' => 10,
                    'IsCollection' => EcpayIsCollection::YES,
                    'GoodsName' => '????????????',
                    'SenderName' => '???????????????',
                    'SenderPhone' => '0226550115',
                    'SenderCellPhone' => '0911222333',
                    'ReceiverName' => '???????????????',
                    'ReceiverPhone' => '0226550115',
                    'ReceiverCellPhone' => '0933222111',
                    'ReceiverEmail' => 'test_emjhdAJr@test.com.tw',
                    'TradeDesc' => '??????????????????',
                    'ServerReplyURL' => HOME_URL . '/ServerReplyURL.php',
                    'LogisticsC2CReplyURL' => HOME_URL . '/LogisticsC2CReplyURL.php',
                    'Remark' => '????????????',
                    'PlatformID' => '',
                );
        
                $AL->SendExtend = array(
                    'ReceiverStoreID' => '991182',
                    'ReturnStoreID' => '991182'
                );
                // BGCreateShippingOrder()
                $Result = $AL->BGCreateShippingOrder();
                echo '<pre>' . print_r($Result, true) . '</pre>';
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        }else{
            try {

                $obj = new \ECPay_AllInOne();
        
                //????????????
                $obj->ServiceURL  = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";  //????????????
                $obj->HashKey     = '5294y06JbISpM5x9' ;                                          //?????????Hashkey??????????????????ECPay?????????HashKey
                $obj->HashIV      = 'v77hoKGq4kWxNNIS' ;                                          //?????????HashIV??????????????????ECPay?????????HashIV
                $obj->MerchantID  = '2000132';                                                    //?????????MerchantID??????????????????ECPay?????????MerchantID
                $obj->EncryptType = '1';                                                          //CheckMacValue??????????????????????????????1?????????SHA256??????

                //?????????????????????
                $costing = array();
                for($i=0 ; $i< count($ids) ; $i++ ){
                    array_push($obj->Send['Items'], array('Name' => $items[$i], 'Price' => (int)$deposits[$i],
                            'Currency' => "???", 'Quantity' => (int) $qtys[$i], 'URL' => "dedwed"));
                    $oneitem=(int)$deposits[$i]*(int) $qtys[$i];
                    array_push($costing,$oneitem);     
                    }
                //????????????(??????????????????????????????)
                       
                $MerchantTradeNo =$user_order_id;
                //$MerchantTradeNo = "Test".time() ;
                $obj->Send['ReturnURL']         = "https://e444fdf722db.ngrok.io/paymentCheck" ;     //?????????????????????????????????
                $obj->Send['PeriodReturnURL']         = "https://e444fdf722db.ngrok.io/paymentCheck" ;    //?????????????????????????????????
                $obj->Send['ClientBackURL'] = " https://e444fdf722db.ngrok.io/payed" ;
                $obj->Send['MerchantTradeNo']   = $MerchantTradeNo;                           //????????????
                $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                        //????????????
                $obj->Send['TotalAmount']       = array_sum($costing);                                       //????????????
                //$obj->Send['TotalAmount']       = "2000";                                       //????????????
                $obj->Send['TradeDesc']         = "good to drink" ;                           //????????????
                $obj->Send['ChoosePayment']     = ECPayMethod::ALL ;                  //????????????:?????????
                //$obj->Send['ChoosePayment']     = ECPayMethod::Credit ;              //????????????:Credit
                //$obj->Send['IgnorePayment']     = ECPayMethod::GooglePay ;           //?????????????????????:GooglePay


                
                /*array_push($obj->Send['Items'], array('Name' => "????????????????????????", 'Price' => (int)"2000",
                    'Currency' => "???", 'Quantity' => (int) "1", 'URL' => "dedwed"));*/

                # ??????????????????
                /*
                $obj->Send['InvoiceMark'] = ECPay_InvoiceState::Yes;
                $obj->SendExtend['RelateNumber'] = "Test".time();
                $obj->SendExtend['CustomerEmail'] = 'test@ecpay.com.tw';
                $obj->SendExtend['CustomerPhone'] = '0911222333';
                $obj->SendExtend['TaxType'] = ECPay_TaxType::Dutiable;
                $obj->SendExtend['CustomerAddr'] = '???????????????????????????19-2???5???D???';
                $obj->SendExtend['InvoiceItems'] = array();
                // ?????????????????????????????????????????????
                foreach ($obj->Send['Items'] as $info)
                {
                    array_push($obj->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
                        $info['Quantity'],'Word' => '???','Price' => $info['Price'],'TaxType' => ECPay_TaxType::Dutiable));
                }
                $obj->SendExtend['InvoiceRemark'] = '??????????????????';
                $obj->SendExtend['DelayDay'] = '0';
                $obj->SendExtend['InvType'] = ECPay_InvType::General;
                */


                //????????????(auto submit???ECPay)
                $obj->CheckOut();
            

            
            } catch (Exception $e) {
                echo $e->getMessage();
            } 
        }
        //return redirect('html')->with('htmlsend',$htmlsend);

        //$user_order_id .= strval(date("-Ymd"));
        //$user_order_id .= strval(date("-hi"));

        /*$formData = [
            'UserId' => auth()->user()->id, // ??????ID , Optional
            'ItemDescription' => '????????????',
            'ItemName' => 'Product Name',
            'TotalAmount' => '2000',
            'PaymentMethod' => 'Credit', // ALL, Credit, ATM, WebATM
            'ReturnURL' => 'https://5a5b10746db2.ngrok.io',
            'MerchantTradeNo' => $user_order_id,
        ];*/

        
        //-----------------------//
        //return redirect('/send')->with('alert', 'created');
        //return $this->checkout->setPostData($formData)->send();
    }
    public function paymentCheck(Request $request)
    {
        $MerchantTradeNo = $request->input('MerchantTradeNo');
        $order = Order::where('order_id',$MerchantTradeNo)->first();
        //$order = Order::where('order_id', '=', request('MerchantTradeNo'))->firstOrFail();
        $order->payment = !$order->payment;
        $order->save();
    }
    public function redirectFromECpay () {
        session()->flash('success', 'Order success!');
        return redirect('/home');
    }
    public function sendOrder()
    {
        $formData = [
            'UserId' => 1, // ??????ID , Optional
            'ItemDescription' => '????????????',
            'ItemName' => 'Product Name',
            'TotalAmount' => '2000',
            'PaymentMethod' => 'Credit', // ALL, Credit, ATM, WebATM
        ];
        return $this->checkout->setPostData($formData)->send();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $borrow = Borrow::find($id);
        return view('borrow.show')->with('borrow', $borrow);
    }

    public function get(Request $request)
    {
        $borrow = Borrow::find($id)->get();
        return response()->json($borrow);
    }
    /*public function get(Request $request)
    {
        $borrows = Borrow::orderBy('created_at', 'desc')->get();
        return response()->json($borrows);
    }*/
    public function verify(Request $request)
    {
        $id=$request->input('id');
        $item=$request->input('item');
        $unstatus=$request->input('status');
        $qty=$request->input('qty');
        $status=!$unstatus;
        $borrow = Borrow::find($id);
        $post = Post::find($item);
        $borrow->status = $status;
        $borrow->save();
        $post->save();
        /*return response()->json([
            'status'=> $status
          ], 200);*/
        return redirect('send');
   }

    
}
