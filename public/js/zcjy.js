$.extend({
    /**
     * [发起跳转]
     * @param  {[type]} url   [description]
     * @param  {Number} timer [description]
     * @return {[type]}       [description]
     */
    location:function(url,timer=1000){
        if($.empty(timer)){
            location.href = url;
            return false;
        }
        setTimeout(function(){
            location.href = url;
        },timer);
    },

    /**
     * 汉化查询
     * @param  {[string]} name [description]
     * @return {[string]}      [description]
     */
    chParamFind:function(name){
        var words = $.chParam();
        var word = '参数不完整';
        for (var i in words) {
            if(typeof words[i][name] !== 'undefined'){
                word = '请输入'+words[i][name];
            }
        }
        return word;
    },
    /**
     * 汉化参数
     * @return {[string]} [description]
     */
    chParam:function(){
        return [
            {'name':'名称'},
            {'endtime':'结束时间'},
            {'content':'内容'},
            {'address':'地址'},
            {'gear':'档位金额'},
            {'target':'目标金额'},
            {'zuqi':'租期'},
            {'area':'面积'},
            {'price':'价格'},
            { 'pwd' : '密码'}
        ];
    },
    /**
     * 表单检测
     * @param  {[array|string]} attr           [description]
     * @return {[Boolean|string]}              [description]
     */
    varifyInput:function(attr){
        var status = false;
        if(!$.is_array(attr)){
            attr = attr.split(',');
        }
        for (var i = 0; i < attr.length; i++) {
            if($.empty($.inputAttr(attr[i]).val())){
                status = $.chParamFind(attr[i]);
                $.alert(status,'error');
                break;
            }
        }
        return status;
    },
    /**
     * 弹出
     * @param  {String} word [description]
     * @param  {String} type [description]
     * @return {[type]}      [description]
     */
    alert:function(word,type="success"){
        type = type == "success" ? 1 : 5;
        layer.msg(word, {icon: type});
    },
    /**
     * 检测数据是否为空
     * @param  {[String]} data  [description]
     * @return {[Boolean]}      [description]
     */
    empty:function(data) {
       return data == '' || data == null  || data == false || data == 'false' || data == 'null' || data == {} || 
       data == '{}' || data == [] ||  JSON.stringify(data) == '{}';
    },
    /**
     * [判断是否是数组]
     * @param  {[type]}  object [description]
     * @return {Boolean}        [description]
     */
    is_array:function (object){
        return object && typeof object==='object' &&
            Array == object.constructor;
    },
    /**
     * [自动根据input的name字段生成基于jq的选择器]
     * @param  {[array|String]}  name_attr [description]
     * @return {[Jquery Object]}           [description]
     */
    inputAttr:function(name_attr){
        if(!$.is_array(name_attr)){
            name_attr = name_attr.split(',');
        }
        var fuhao =',';
        var new_attr = '';
        for (var i = name_attr.length - 1; i >= 0; i--) {
            if(i == 0){
                fuhao = '';
            }
            new_attr += 'input[name='+name_attr[i]+']'+fuhao;
        }
        return $(new_attr);
    },    
    /**
     * [设置指定输入的最大长度]
     * @param {[string]} attribute      [属性]
     * @param {[array]} keyword_arr     [属性关键字数组]
     * @param {[int]} length            [description]
     */
   setInputLengthByName:function(attribute,keyword_arr,length){  
        for(var i=keyword_arr.length-1;i>=0;i--){
            $('input['+attribute+'='+keyword_arr[i]+']').attr('maxlength',length);
        }
    },
    /**
     * [后台/前端 ajax请求通用接口]
     * @param  {[string]}   request_url         [请求地址]
     * @param  {Function}   callback            [成功回调]
     * @param  {Object}     request_parameters  [请求参数]
     * @param  {String}     method              [HTTP请求方法]
     * @return {[type]}                         [description]
     */
    zcjyRequest:function(request_url,callback,request_parameters = {},method = "GET"){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:request_url,
                type:method,
                data:request_parameters,
                success: function(data) {
                    // console.log(data.code);
                    if(data.code == 0){
                        if(typeof(callback) == 'function'){
                            callback(data.message);
                        }
                    }
                    else if(data.code == 1) {
                        callback(false);
                        alert(data.message);
                        if(data.message == '请先完善填写个人信息后使用')
                        {
                            $.location('/user/reg',500)
                        }
                        else if(data.message == '该课程已被添加过请先去结算')
                        {
                            $.location('/enter_sign',500);
                        }
                        else if(data.message == '您有未支付的订单,请先完成支付后添加')
                        {
                            $.location('/user/orders?check=pay',500);
                        }
                    }
                    else if(data.code == 3){
                        callback(data.message);
                        layer.msg(data.message, {icon: 3});
                    }
                }
            });
    },
    /**
     * [给必填/必选字段加上提示]
     * @param  {string}  name_array [description]
     * @param  {Boolean} select     [description]
     * @return {[type]}             [description]
     */
    zcjyRequiredParam:function(name_array,select=false){
           name_array = name_array.split(',');
           select = select ? '选' : '填';
           for(var i=name_array.length-1;i>=0;i--){
                $('label[for='+name_array[i]+']').after('<span class=required>(必'+select+')</span>');
           }
    },
    /**
     * [打开弹出层]
     * @param  {[type]}   url      [description]
     * @param  {[type]}   title    [description]
     * @param  {Array}    area     [description]
     * @param  {[type]}   '680px'] [description]
     * @param  {Function} callback [description]
     * @return {[type]}            [description]
     */
    zcjyFrameOpen:function(url,title='操作信息框',area=['60%', '680px'],callback=null){
        var type =2;
        if(url.length > 50){
            type = 1;
        }
        if($(window).width()<479){
            area = ['100%', '100%'];
        }
         layer.open({
            type: type,
            title:title,
            shadeClose: true,
            shade: 0.8,
            area: area,
            content: url, 
        });
        if(callback != null && typeof (callback) == 'function'){
            callback(url);
        }
    },
});

$.fn.extend({    
    /**
     * [限制number类型的输入 后期可继续扩展]
     * @param 传入参数  [int/string] {整形/字符串} _lengths  [长度/类型]
     * @return 
     */
   numberInputLimit:function(_lengths){    
       $(this).bind("keyup paste",function(){
            if(_lengths <= 11){
            //替换字母特殊字符 用于整形浮点等     
            this.value=this.value.replace(/[^\d.]/g,"");
            }
           //截取最大长度 
           //针对数据库常用字符串 推荐使用191
           //针对数据库常用数量    推荐使用8 11
            if(this.value.length > _lengths){
                this.value=this.value.slice(0,_lengths);
            }
            //针对100以内 百分比
            if(_lengths == 3){
                if(this.value > 100){
                    this.value = 100;
                }
            }
            //针对商城分类
            if(_lengths == 1 || _lengths== 'category'){
                 if(this.value > 3){
                    this.value = 3;
                }
            } 
        });    
    },
    /**
     * [限制图片的长度 超过规范长度给出错误提示]
     * @param  {[int]}  _imgmaxlength [图片url最大长度]
     * @return {[type]}               [description]
     */
    imgInputLimit:function(_imgmaxlength){
        //图片长度限制
        $(this).bind('change',function(){
            //长度超出数据库规范限制
            if($(this).val().length>=_imgmaxlength){
                //置空输入框
                $(this).val("");
                //去除图片
                $(this).parent().find('img').remove();
                //给出错误提示弹框
                layer.msg("图片 不能大于 "+_imgmaxlength+" 个字符,请修改图片名称后重试", {
                            icon: 5
                });
              
            }
        });
    },
    zcjyFrameOpenObj:function(url,title,area=['60%', '680px'],func='click',callback=null){
        var type =2;
        if(url.length > 50){
            type = 1;
        }
        if($(window).width()<479){
            area = ['100%', '100%'];
        }
        $(this).bind(func,function(){
                 layer.open({
                    type: type,
                    title:title,
                    shadeClose: true,
                    shade: 0.8,
                    area: area,
                    content: url, 
                });
               if(callback != null && typeof (callback) == 'function'){
                    callback(url);
                }
        });
    },  
});

//常用字符串
$('input[type=text]').attr('maxlength',191);
//常用手机号
$('input[name=service_tel]').numberInputLimit(11);
//分类等级
$('input[name=category_level]').numberInputLimit(1);
//百分比
$('input[name=consume_credits],input[name=credits_max],#value').numberInputLimit(3);
//价格数字
$('input[name=price],input[name=sort],input[name=product_num],input[name=inventory_default],input[name=buy_limit],input[name=expire_hour],input[name=member],input[name=market_price],input[name=freight_free_limit],input[name=cost],input[name=records_per_page],input[name=inventory_warn],input[name=given],input[name=max_count],input[name=base],input[name=sales_count],input[name=inventory],input[name=weight],input[name=amount],input[name=discount]').numberInputLimit(8);
//图片长度限制提示
$('input[name=image]').imgInputLimit(256);
//指定标签后面加上必填
// $.zcjyRequired(['name']);


