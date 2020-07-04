(function () {
    // 配置信息
    var setting = {
        el: 'pageGroupSku',
        skuData: null,
        skuAttr: null,
        spliter:'\u2299',
        quantity:1,
        skuForm:{
            id:0,
            price:0,
            stock:0,
            pic_url:'',
            is_attribute:false,
            attribute:[],
            quantity:1
        },
        keys:[],
        res:{},
        updateSku:function (data) {
            
        },
        getDefaultData:function(data){

        }
    };
    /**
     * 构造方法
     * @param options
     * @constructor
     */
    function GoodsSku(options) {
        // 配置信息
        setting = $.extend(true, {}, setting, options);
        // 初始化
        this.initialize();
    }
    GoodsSku.prototype = {
        // vue组件句柄
        appVue: null,
        /**
         * 初始化
         */
        initialize: function () {
            var _this = this;
            _this.init();
            this.appVue = new Vue({
                el: setting.el,
                data: {
                    skuForm:setting.skuForm,
                    skuData:setting.skuAttr,
                    selected:{},// 选中
                    disabled:{}, // 废除
                    quantity:1
                },
                created:function () {
                    var _this = this;
                    _this.buildResult();
                    _this.updateStatus();
                    _this._defaultData();
                },
                methods:{
                    onSelectSkuClass:function (index,key,name) {
                        var _this       = this;
                        if(_this.disabled[index][key] !== undefined){
                            if(_this.disabled[index][key] === true){
                                return 'disabled';
                            }
                        }
                        if(_this.selected[index] === undefined){
                            return '';
                        }else if(_this.selected[index]===name){

                            return 'sel';
                        }
                    },
                    /**
                     * 选择SKU属性
                     * @param index
                     * @param key
                     * @param name
                     * @returns {boolean}
                     */
                    onSelectSkuEvent:function (index,key,name) {
                        var _this = this;
                        if(_this.disabled[index][key] !== undefined){
                            if(_this.disabled[index][key] === true){
                                return false;
                            }
                        }
                        if(_this.selected[index] === name){
                            _this.$delete(_this.selected,index);
                        }else{
                            _this.$set(_this.selected,index,name);
                        }
                        _this.updateStatus();
                        _this.getResult();
                    },
                    /**
                     * 点击数量 i值 +1或-1
                     * @param i
                     */
                    onBtnQuantityEvent:function (i) {
                        var _this = this;
                        var quantity =_this.quantity + i;
                        if(i===-1 && quantity<=0){
                            return false;
                        }if(i===+1 && quantity> _this.skuForm.stock){
                            return false;
                        }
                        _this.quantity = quantity;
                        _this.skuForm.quantity = quantity;
                        //console.log(i);
                    },
                    getAllKeys:function (arr) {
                        var result = [];
                        for (var i = 0; i < arr.length; i++) { result.push(arr[i].path); }
                        return result;
                    },
                    //取得集合的所有子集「幂集」
                    powerset:function(arr) {
                        var ps = [[]];
                        for (var i=0; i < arr.length; i++) {
                            for (var j = 0, len = ps.length; j < len; j++) {
                                ps.push(ps[j].concat(arr[i]));
                            }
                        }
                        return ps;
                    },
                    buildResult:function () {
                        var _this   = this;
                        var items   = _this.skuData.items;
                        var allKeys = _this.getAllKeys(items);
                        var res     = setting.res;
                        for (var i = 0; i < allKeys.length; i++) {
                            var curr   = allKeys[i];
                            var skuId  = items[i].skuId;
                            var values = curr.split(setting.spliter);

                            // var allSets = getAllSets(values)
                            var allSets = _this.powerset(values);

                            // 每个组合的子集
                            for (var j = 0; j < allSets.length; j++) {
                                var set = allSets[j];
                                var key = set.join(setting.spliter);
                                if (res[key]) {
                                    res[key].skus.push(skuId);
                                } else {
                                    res[key] = {
                                        skus: [skuId]
                                    };
                                }
                            }

                        }
                        setting.res = res;
                    },
                    /**
                     *
                     * @param str
                     * @param spliter
                     */
                    trimSpliter:function(str, spliter) {
                        // ⊙abc⊙ => abc
                        // ⊙a⊙⊙b⊙c⊙ => a⊙b⊙c
                        var reLeft        = new RegExp('^' + spliter + '+', 'g');
                        var reRight       = new RegExp(spliter + '+$', 'g');
                        var reSpliter = new RegExp(spliter + '+', 'g');
                        return str.replace(reLeft, '')
                            .replace(reRight, '')
                            .replace(reSpliter, spliter);
                    },

                    /**
                     * 更新所有属性状态
                     */
                    updateStatus:function () {
                        var _this       = this;
                        var selected    = [];
                        var keys        = setting.keys,r = setting.skuAttr,disabled = [];
                        var res         = setting.res;
                        for (var s in _this.selected){ //对象转数组
                            selected[s] = _this.selected[s];
                        }
                        for (var i  = 0; i < keys.length; i++) {
                            var key = keys[i];
                            var data = r.result[key];
                            //var hasActive = !!selected[i];
                            var copy = selected.slice();
                            var disabledData = [];
                            for (var j = 0; j < data.length; j++) {
                                var item = data[j];
                                if (selected[i] === item){
                                    continue;
                                }
                                copy[i] = item;
                                var curr = _this.trimSpliter(copy.join(setting.spliter), setting.spliter);

                                 if (res[curr]) {
                                     disabledData[j] = false;
                                 } else {
                                     disabledData[j] = true;
                                 }
                            }
                            disabled[i] = disabledData;

                        }
                        _this.disabled = disabled;

                    },
                    //获取选中的数据
                    getResult:function(){
                        var _this       = this;
                        var selected    = [];
                        var keys        = setting.keys;
                        var res         = setting.res;
                        var _selectedAttribute = [];
                        for (var index in keys){
                            _selectedAttribute[index] = keys[index];
                        }
                        for (var s in _this.selected){
                            selected.push(_this.selected[s]);
                            //console.log(s);
                            delete _selectedAttribute[s];

                        }

                        if (selected.length === keys.length) {
                            var curr = res[selected.join(setting.spliter)];
                            var skuId = 0;
                            if (curr) {
                                //selected = selected.concat(curr.skus);
                                skuId = curr.skus[0];
                                var sku = setting.skuData[skuId];
                                _this.skuForm = {
                                    id          : skuId,
                                    stock       : sku.stock,
                                    price       : [sku.price],
                                    pic_url     : sku.pic_url,
                                    is_attribute: true,
                                    attribute   : selected,
                                    quantity    : _this.quantity
                                };
                                //console.log(_this.skuForm);
                            }
                        }else{
                            //请选择
                            var attribute = [];
                            for (index in _selectedAttribute){
                                if(_selectedAttribute[index] !== undefined){
                                    attribute.push(_selectedAttribute[index]);
                                }
                            }
                            var _SkuDefaultData = _this._getSkuDefaultData();
                            _this.skuForm = {
                                id          : 0,
                                price       : _SkuDefaultData.price,
                                stock       : _SkuDefaultData.stock,
                                pic_url     : _SkuDefaultData.pic_url,
                                is_attribute: false,
                                attribute   : attribute,
                                quantity    : _this.quantity
                            };
                        }
                        setting.updateSku(_this.skuForm);

                    },
                    _getSkuDefaultData:function () {
                        var skuData     = setting.skuData;
                        var stock       = 0;
                        var orig_price  = [],line_max,line_min;
                        var price       = [],max,min;
                        var pic_url     = '';

                        var i = 0;
                        for (var index in skuData){
                            if(i === 0){
                                max      =  skuData[index].price;
                                min      =  skuData[index].price;
                                pic_url  =  skuData[index].pic_url;
                                //划线价钱
                                line_max =  skuData[index].orig_price;
                                line_min =  skuData[index].orig_price;
                            }
                            if(max<skuData[index].price){
                                max=skuData[index].price;
                            }
                            if(min>skuData[index].price){
                                min=skuData[index].price;
                            }
                            //划线价钱
                            if(line_max<skuData[index].orig_price){
                                line_max=skuData[index].orig_price;
                            }
                            if(line_min>skuData[index].orig_price){
                                line_min=skuData[index].orig_price;
                            }

                            stock +=parseInt(skuData[index].stock);
                            i++;
                        }
                        if(max === min){
                            price.push(max);
                        }else{
                            price.push(min,max);
                        }
                        if(line_min>0){
                            if(line_max === line_min){
                                orig_price.push(line_max);
                            }else{
                                orig_price.push(line_min,line_max);
                            }
                        }

                        return {
                            stock : stock,
                            price     : price,
                            orig_price: orig_price,
                            pic_url   : pic_url,
                        };

                    },
                    //获取默认数据
                    _defaultData:function () {
                        var _this       = this;
                        var keys        = setting.keys;

                        var _SkuDefaultData = _this._getSkuDefaultData();
                        _this.skuForm = {
                                id:0,
                                price:_SkuDefaultData.price,
                                stock:_SkuDefaultData.stock,
                                pic_url:_SkuDefaultData.pic_url,
                                attribute:keys,
                                is_attribute:false,
                                quantity:setting.quantity
                        };
                        setting.updateSku(_this.skuForm);
                        setting.getDefaultData(_this.skuForm);

                    },
                    //购物车
                    onCartEvent:function () {
                        var _this   = this;//id
                        var skuForm = _this.skuForm;
                        if(skuForm.id === 0){
                            $.toast('请选 '+ skuForm.attribute.join(' '));
                            return false;
                        }else{
                             var data = {
                                goods_id:goods_id,
                                quantity:skuForm.quantity,
                                sku_id:skuForm.id
                            }
                            ajax('POST',UrlCart,data,function(ret) {
                                if(ret.status === true){
                                    $.toast(ret.message);
                                }else{
                                    $.toast(ret.message);
                                }
                            });
                        }
                    },
                    /**
                     * 立即购买
                     */
                    onBuyEvent:function () {
                        var _this   = this;//id
                        var skuForm = _this.skuForm;
                        if(skuForm.id === 0){
                            $.toast('请选 '+ skuForm.attribute.join(' '));
                            return false;
                        }else{
                            var data = {
                                goods_id:goods_id,
                                quantity:skuForm.quantity,
                                sku_id:skuForm.id
                            };



                            ajax('POST',UrlBuy,data,function(ret) {
                                if(ret.status === true){
                                    window.location.href = ret.url;
                                }else{
                                    $.toast(ret.message);
                                }
                            });
                        }
                    },
                    /**
                     * 获取当前data
                     */
                    getData: function () {
                        return this.$data;
                    },
                }
            });
        },
        init:function () {
            var data = setting.skuData,keys = [];
            for (var value in data){
                for (var attr_key in data[value]) {
                    if (!data[value].hasOwnProperty(attr_key)){
                        continue;
                    }
                    if (attr_key !== 'skuId' && attr_key !== 'stock' && attr_key !== 'price' && attr_key !== 'orig_price' && attr_key !== 'pic_url'){
                        keys.push(attr_key);
                    }
                }
                break;
            }

            setting.keys = keys;//赋值
            setting.skuAttr = this.combineAttr(data, keys);
        },
        combineAttr:function (data, keys) {
            var allKeys = [];
            var result = {};

            //for (var i = 0; i < data.length; i++) {
            for (var i in data) {
                var item = data[i];
                var values = [];
                for (var j = 0; j < keys.length; j++) {
                    var key = keys[j];
                    if (!result[key]) {
                        result[key] = [];
                    }
                    if (result[key].indexOf(item[key]) < 0) {
                        result[key].push(item[key]);
                    }
                    values.push(item[key]);
                }
                allKeys.push({
                    path: values.join(setting.spliter),
                    skuId: item['skuId']
                });
            }
            return {
                result: result,
                items: allKeys
            };
        }
    };
    window.GoodsSku = GoodsSku;
})(window);