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
    public function store(Request $request) 
    {
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required',
            'deposit' => 'required',
            'qty' => 'required',
            'shipping'=>'required'
        ]);

        $ids = $request->input('id');
        $items = $request->input('name');
        $deposits = $request->input('deposit');
        $qtys = $request->input('qty');
        $shipping = $request->input('shipping');

        
        try {

            $obj = new \ECPay_AllInOne();
    
            //服務參數
            $obj->ServiceURL  = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";  //服務位置
            $obj->HashKey     = '5294y06JbISpM5x9' ;                                          //測試用Hashkey，請自行帶入ECPay提供的HashKey
            $obj->HashIV      = 'v77hoKGq4kWxNNIS' ;                                          //測試用HashIV，請自行帶入ECPay提供的HashIV
            $obj->MerchantID  = '2000132';                                                    //測試用MerchantID，請自行帶入ECPay提供的MerchantID
            $obj->EncryptType = '1';                                                          //CheckMacValue加密類型，請固定填入1，使用SHA256加密


            //基本參數(請依系統規劃自行調整)
            $MerchantTradeNo = "Test".time() ;
            $obj->Send['ReturnURL']         = "https://5a5b10746db2.ngrok.io" ;     //付款完成通知回傳的網址
            $obj->Send['PeriodReturnURL']         = "https://5a5b10746db2.ngrok.io" ;    //付款完成通知回傳的網址
            $obj->Send['ClientBackURL'] = " https://5a5b10746db2.ngrok.io/home" ;
            $obj->Send['MerchantTradeNo']   = $MerchantTradeNo;                           //訂單編號
            $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                        //交易時間
            $obj->Send['TotalAmount']       = array_sum($deposits);                                       //交易金額
            $obj->Send['TradeDesc']         = "good to drink" ;                           //交易描述
            //$obj->Send['ChoosePayment']     = ECPay_PaymentMethod::ALL ;                  //付款方式:全功能
            $obj->Send['ChoosePayment']     = ECPayMethod::Credit ;              //付款方式:Credit
            $obj->Send['IgnorePayment']     = ECPayMethod::GooglePay ;           //不使用付款方式:GooglePay


            //訂單的商品資料
            for($i=0 ; $i< count($ids) ; $i++ ){
            array_push($obj->Send['Items'], array('Name' => $items[$i], 'Price' => (int)$deposits[$i],
                    'Currency' => "元", 'Quantity' => (int) $qtys[$i], 'URL' => "dedwed"));
            }

            # 電子發票參數
            /*
            $obj->Send['InvoiceMark'] = ECPay_InvoiceState::Yes;
            $obj->SendExtend['RelateNumber'] = "Test".time();
            $obj->SendExtend['CustomerEmail'] = 'test@ecpay.com.tw';
            $obj->SendExtend['CustomerPhone'] = '0911222333';
            $obj->SendExtend['TaxType'] = ECPay_TaxType::Dutiable;
            $obj->SendExtend['CustomerAddr'] = '台北市南港區三重路19-2號5樓D棟';
            $obj->SendExtend['InvoiceItems'] = array();
            // 將商品加入電子發票商品列表陣列
            foreach ($obj->Send['Items'] as $info)
            {
                array_push($obj->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
                    $info['Quantity'],'Word' => '個','Price' => $info['Price'],'TaxType' => ECPay_TaxType::Dutiable));
            }
            $obj->SendExtend['InvoiceRemark'] = '測試發票備註';
            $obj->SendExtend['DelayDay'] = '0';
            $obj->SendExtend['InvType'] = ECPay_InvType::General;
            */


            //產生訂單(auto submit至ECPay)
            $obj->CheckOut();
        

        
        } catch (Exception $e) {
            echo $e->getMessage();
        } 


        //$user_order_id = strval(auth()->user()->id);
        //$user_order_id .= strval(date("-Ymd"));
        //$user_order_id .= strval(date("-hi"));
        $user_order_id = $MerchantTradeNo;

        /*$formData = [
            'UserId' => auth()->user()->id, // 用戶ID , Optional
            'ItemDescription' => '產品簡介',
            'ItemName' => 'Product Name',
            'TotalAmount' => '2000',
            'PaymentMethod' => 'Credit', // ALL, Credit, ATM, WebATM
            'ReturnURL' => 'https://5a5b10746db2.ngrok.io',
            'MerchantTradeNo' => $user_order_id,
        ];*/

        

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

        }
        $order = new Order;
        $order->order_id = $user_order_id;
        $order->user_id = auth()->user()->id;
        $order->user_name = auth()->user()->name;
        $order->shipping = $shipping;
        $order->payment = false;

        if($shipping==0){ 
            $order->total = array_sum($deposits);
            $order->address = 0;
        }else{
            $order->total = array_sum($deposits)+60;
            $order->address = $request->input('address');
        }
        
        $order->status = false;
        $order->save();
        /*
        $this->validate($request, [
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
        //return redirect('/send')->with('alert', 'created');
        //return $this->checkout->setPostData($formData)->send();
    }

    public function sendOrder()
    {
        $formData = [
            'UserId' => 1, // 用戶ID , Optional
            'ItemDescription' => '產品簡介',
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
