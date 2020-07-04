(function () {

    // 配置信息
    var setting = {
        el: 'many-sku',
        baseData: null,
        isSpecLocked: false,
        data:{
            required:false
        },
        batchData: {
            productcode: '',
            price: '',
            sharing_price: '',
            line_price: '',
            stock: '',
            goods_weight: ''
        }
    };

    /**
     * 构造方法
     * @param options
     * @param baseData
     * @constructor
     */
    function GoodsSku(options, baseData) {
        // 配置信息
        setting = $.extend(true, {}, setting, options);
        // 初始化
        this.initialize();
    }

    GoodsSku.prototype = {

        // vue组件句柄
        appVue: null,
        update:function (options) {
            setting = $.extend(true, {}, setting, options);
            var sku_attr = [], sku_list = [];
            if (typeof setting.baseData !== 'undefined' && setting.baseData !== null && setting.baseData !== '') {

                sku_attr = setting.baseData['sku_attr'];
                sku_list = setting.baseData['sku_list'];
            }

            this.appVue.sku_attr = sku_attr;
            this.appVue.sku_list = sku_list;
            this.appVue.buildSkulist();
        },
        /**
         * 初始化
         */
        initialize: function () {
            // 已存在的规格数据
            var sku_attr = [], sku_list = [];
            if (typeof setting.baseData !== 'undefined' && setting.baseData !== null && setting.baseData !== '') {

                sku_attr = setting.baseData['sku_attr'];
                sku_list = setting.baseData['sku_list'];
            }

            // 实例化vue对象
            this.appVue = new Vue({
                el: setting.el,
                data: {
                    sku_attr: sku_attr,
                    sku_list: sku_list,
                    // 显示添加规格组按钮
                    showAddGroupBtn: true,
                    // 显示添加规格组表单
                    showAddGroupForm: false,
                    // 新增规格组值
                    addGroupFrom: {
                        specName: '',
                        specValue: ''
                    },
                    // 当前规格属性是否锁定
                    isSpecLocked: setting.isSpecLocked,
                    // 批量设置sku属性
                    batchData: setting.batchData
                },
                methods: {
                    /**
                     * 注册sku添加规格组
                     */
                    onSelectSkuEvent: function () {
                        var _this = this;
                        // 注册上传sku图片
                        _this.$nextTick(function () {
                            //console.log(_this.sku_attr);
                            $(_this.$el).find('.add-sku-group').selectWindows({
                                title:'选择属性/规格',
                                scrollbar:'Yes',
                                params:_this.sku_attr,
                                area: ['650px', '450px'],
                                done: function (data, $addon) {
                                    //console.log(_this.sku_attr.length);
                                    for(var i=0;i<data.length;i++){
                                        var attr = [];
                                        for (var k=0;k<_this.sku_attr.length;k++){
                                             attr.push(_this.sku_attr[k].attr_id);
                                        }
                                        //console.log(attr);
                                        if(attr.indexOf(data[i].id) === -1 ){
                                            _this.sku_attr.push({
                                                attr_id: data[i].id,
                                                attr_name: data[i].name,
                                                attr_items: []
                                                //,tempValue: ''
                                            });
                                            //从新注册sku添加规格组
                                            _this.onSelectSkuEvent();
                                            //注册属性值事件
                                            _this.onSelectAttrValueEvent();
                                            _this.buildSkulist();
                                        }else{
                                            //属性已添加存在，无须添加
                                            layer.msg('【'+data[i].name+'】规格已存在，无须添加');
                                            return false;
                                        }

                                    }



                                }
                            });

                        });
                    },


                    /**
                     * 新增属性值
                     * @param index
                     */
                    onSelectAttrValueEvent: function (index) {
                        var _this = this;


                        // 注册新增属性值
                        _this.$nextTick(function () {
                            $(_this.$el).find('.add-sku-attr-value').selectWindows({
                                title:'选择属性值',
                                scrollbar:'Yes',
                                params:_this.sku_attr,
                                area: ['650px', '450px'],
                                done: function (data, $addon) {
                                    var index = $addon.data('index');
                                    var specAttr = _this.sku_attr[index];
                                    for(var i=0;i<data.length;i++){
                                        var attr = new Array();
                                        for (var k=0;k<specAttr.attr_items.length;k++){
                                            attr.push(specAttr.attr_items[k].attr_value_id);
                                        }
                                        if(attr.indexOf(data[i].id) === -1 ){
                                            specAttr.attr_items.push({
                                                attr_value_id: data[i].id,
                                                attr_name_value: data[i].value
                                            });
                                            _this.buildSkulist();
                                        }else{
                                            //属性已添加存在，无须添加
                                            layer.msg('【'+data[i].value+'】属性已存在，无须添加');
                                            return false;
                                        }


                                    }
                                    //_this.buildSkulist();
                                }
                            });

                        });
                    },

                    /**
                     * 构建规格组合列表
                     */
                    buildSkulist: function () {
                        var _this = this;
                        // 规格组合总数 (table行数)
                        var totalRow = 1;
                        for (var i = 0; i < _this.sku_attr.length; i++) {
                            totalRow *= _this.sku_attr[i].attr_items.length;
                        }
                        // 遍历tr 行
                        var specList = [];
                        for (i = 0; i < totalRow; i++) {
                            var rowData = [], rowCount = 1, specSkuIdAttr = [];
                            // 遍历td 列
                            for (var j = 0; j < _this.sku_attr.length; j++) {
                                var skuValues = _this.sku_attr[j].attr_items;
                                rowCount *= skuValues.length;
                                var anInterBankNum = (totalRow / rowCount),point = ((i / anInterBankNum) % skuValues.length);
                                if (0 === (i % anInterBankNum)) {
                                    rowData.push({
                                        rowspan: anInterBankNum,
                                        attr_value_id: skuValues[point].attr_value_id,
                                        attr_name_value: skuValues[point].attr_name_value
                                    });
                                }
                                specSkuIdAttr.push(skuValues[parseInt(point.toString())].attr_value_id);
                            }
                            //console.log(_this.sku_list[i]);
                            var sku_id = 0;
                            if( _this.sku_list[i] !== undefined ){
                                if( _this.sku_list[i].sku_id !== undefined ){
                                    sku_id = _this.sku_list[i].sku_id;
                                }
                            }
                            specList.push({
                                sku_attr_id: specSkuIdAttr.join('_'),
                                sku_id:sku_id,//_this.sku_list[i].sku_id,
                                rows: rowData,
                                form: {}
                            });
                        }

                        // return false;
                        // 合并旧sku数据
                        if (_this.sku_list.length > 0 && specList.length > 0) {
                            for (i = 0; i < specList.length; i++) {
                                var overlap = _this.sku_list.filter(function (val) {
                                    return val.sku_attr_id === specList[i].sku_attr_id;
                                });
                                if (overlap.length > 0) specList[i].form = overlap[0].form;
                            }
                        }
                        _this.sku_list = specList;
                        // 注册上传sku图片事件
                        _this.onSelectImagesEvent();


                    },

                    /**
                     * 删除规则组事件
                     * @param index
                     */
                    onDeleteGroup: function (index) {
                        var _this = this;
                        parent.layer.confirm('确定要删除该规则吗？确认后不可恢复请谨慎操作'
                            , function (layerIndex) {
                                // 删除指定规则组
                                _this.sku_attr.splice(index, 1);
                                // 构建规格组合列表
                                _this.buildSkulist();
                                parent.layer.close(layerIndex);
                            });
                    },

                    /**
                     * 删除规则值事件
                     * @param index
                     * @param itemIndex
                     */
                    onDeleteValue: function (index, itemIndex) {
                        var _this = this;
                        parent.layer.confirm('确定要删除该规则吗？确认后不可恢复请谨慎操作'
                            , function (layerIndex) {
                                // 删除指定规则组
                                _this.sku_attr[index].attr_items.splice(itemIndex, 1);
                                // 构建规格组合列表
                                _this.buildSkulist();
                                parent.layer.close(layerIndex);
                            });
                    },

                    /**
                     * 批量设置sku属性
                     */
                    onSubmitBatchData: function () {
                        var _this = this;
                        _this.sku_list.forEach(function (value) {
                            for (var key  in _this.batchData) {
                                if (_this.batchData.hasOwnProperty(key) && _this.batchData[key]) {
                                    _this.$set(value.form, key, _this.batchData[key]);
                                }
                            }
                        });
                    },

                    /**
                     * 注册上传sku图片事件
                     */
                    onSelectImagesEvent: function () {
                        var _this = this;
                        // 注册上传sku图片
                        _this.$nextTick(function () {

                            $(_this.$el).find('.selectImg').selectImages({
                                done: function (data, $addon) {

                                    var index = $addon.data('index');
                                    _this.$set(_this.sku_list[index].form, 'image_id', data[0]['id']);
                                    _this.$set(_this.sku_list[index].form, 'image_path', data[0]['pic_url']);
                                }
                            });

                        });
                    },

                    /**
                     * 删除sku图片
                     */
                    onDeleteSkuImage: function (index) {
                        this.sku_list[index].form['image_id'] = 0;
                        this.sku_list[index].form['image_path'] = '';
                    },

                    /**
                     * 获取当前data
                     */
                    getData: function () {
                        return this.$data;
                    },

                    /**
                     * sku列表是否为空
                     * @returns {boolean}
                     */
                    isEmptySkuList: function () {
                        return !this.sku_list.length;
                    }

                }
            });

            // 初始化生成sku列表
            sku_list.length > 0 && this.appVue.buildSkulist();
            this.appVue.onSelectSkuEvent();
            //注册属性值事件
            this.appVue.onSelectAttrValueEvent();
        }

    };

    window.GoodsSku = GoodsSku;

})();

