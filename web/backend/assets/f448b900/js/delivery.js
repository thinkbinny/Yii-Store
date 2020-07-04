(function () {

    /***
     * 配送区域设置
     * @param options
     * @constructor
     */
    function delivery(options) {
        var option = $.extend(true, {
            el: '#app',
            mode: 1,
            regions: {},
            cityCount: 0,
            formData: []
        }, options);
        var app = this.createVueApp(option);
        app.initializeForms();
        return app;
    }

    delivery.prototype = {

        createVueApp: function (option) {
            return new Vue({
                el: option.el,
                data: {
                    // 模板名称
                    name: option.name,
                    // 计费方式
                    mode: option.mode,
                    // 所有地区
                    regions: option.regions,
                    // 全选状态
                    checkAll: false,
                    // 当前选择的地区id集
                    checked: {
                        province: [],
                        citys: []
                    },
                    // 禁止选择的地区id集
                    disable: {
                        province: [],
                        citys: [],
                        treeData: {}
                    },
                    // 已选择的区域和运费form项
                    forms: []
                },
                updated:function () {

                  this.updateRender();
                },
                methods: {
                    updateRender:function(){
                        //console.log('update');
                        window.updateRender();
                    },
                    // 初始化forms
                    initializeForms: function () {

                        var app = this;
                        if (!option.formData.length) return false;
                        option.formData.forEach(function (form) {
                            // 转换为整数型
                            for (var key in  form.citys) {
                                if (form.citys.hasOwnProperty(key)) {
                                    form.citys[key] = parseInt(form.citys[key]);
                                }
                            }
                            // 转换省级为整数型
                            for (var k in  form.province) {
                                if (form.province.hasOwnProperty(k)) {
                                    form.province[k] = parseInt(form.province[k]);
                                }
                            }
                            form['treeData'] = app.getTreeData({
                                province: form.province,
                                citys: form.citys
                            });
                            app.forms.push(form);
                        });
                    },

                    // 添加配送区域
                    onAddRegionEvent: function () {
                        var app = this;
                        // 判断是否选择了全国
                        var total = 0;
                        app.forms.forEach(function (item) {
                            total += item.citys.length;
                        });
                        if (total >= option.cityCount) {
                            layer.msg('已经选择了所有区域~');
                            return false;
                        }

                        // 显示选择可配送区域弹窗
                        app.onShowCheckBox({
                            complete: function (checked) {
                                // 选择完成后新增form项
                                app.forms.push({
                                    province: checked.province,
                                    citys: checked.citys,
                                    first           :1,
                                    first_fee       :0,
                                    additional      :1,
                                    additional_fee  :0,
                                    treeData: app.getTreeData(checked)
                                });
                            }
                        });
                    },

                    // 全选
                    onCheckAll: function (checked) {
                        var app = this;
                        app.checkAll = checked;
                        // 遍历能选择的地区
                        for (var key in  app.regions) {
                            if (app.regions.hasOwnProperty(key)) {
                                var item = app.regions[key];
                                if (!app.isPropertyExist(item.id, app.disable.treeData) || !app.disable.treeData[item.id].isAllCitys) {
                                    var provinceId = parseInt(item.id);
                                    this.checkedProvince(provinceId, app.checkAll);
                                }
                            }

                        }

                    },

                    // 标记不可选的地区
                    onDisableRegion: function (ignoreFormIndex) {
                        var app = this;
                        // 清空禁选地区
                        var disable = {province: [], citys: []};
                        for (var key in app.forms) {
                            if (app.forms.hasOwnProperty(key)) {
                                if (ignoreFormIndex > -1 && ignoreFormIndex === parseInt(key)) continue;
                                var item = app.forms[key];
                                disable.province = app.arrayMerge(disable.province, item.province);
                                disable.citys = app.arrayMerge(disable.citys, item.citys);
                            }
                        }
                        app.disable = {
                            province: disable.province,
                            citys: disable.citys,
                            treeData: app.getTreeData(disable)
                        };
                    },

                    // 将选中的区域id集格式化为树状格式
                    getTreeData: function (checkedData) {
                        var app = this;
                        var treeData = {};
                        checkedData.province.forEach(function (provinceId) {
                            provinceId = parseInt(provinceId);
                            var province = app.regions[provinceId]
                                , citys = []
                                , cityCount = 0;
                            for (var cityIndex in province.city) {
                                if (province.city.hasOwnProperty(cityIndex)) {
                                    var cityItem = province.city[cityIndex];
                                    if (app.inArray(cityItem.id, checkedData.citys)) {
                                        citys.push({id: cityItem.id, name: cityItem.name});
                                    }
                                    cityCount++;
                                }
                            }
                            treeData[province.id] = {
                                id: province.id,
                                name: province.name,
                                citys: citys,
                                isAllCitys: citys.length === cityCount
                            };
                        });
                        return treeData;
                    },

                    // 编辑配送区域
                    onEditerForm: function (formIndex, formItem) {
                        var app = this;
                        // 显示选择可配送区域弹窗
                        app.onShowCheckBox({
                            editerFormIndex: formIndex,
                            checkedData: {
                                province: formItem.province,
                                citys: formItem.citys
                            },
                            complete: function (data) {
                                // var formItem = app.forms[formIndex];
                                formItem.province = data.province;
                                formItem.citys = data.citys;
                                formItem.treeData = app.getTreeData(data);
                            }
                        });
                    },

                    // 删除配送区域
                    onDeleteForm: function (formIndex) {
                        var app = this;
                        layer.confirm('确定要删除吗？'
                            , {title: '友情提示'}
                            , function (index) {
                                app.forms.splice(formIndex, 1);
                                layer.close(index);
                            }
                        );
                    },

                    // 显示选择可配送区域弹窗
                    onShowCheckBox: function (option) {
                        var app = this;
                        var options = $.extend(true, {
                            editerFormIndex: -1,
                            checkedData: null,
                            complete: $.noop()
                        }, option);
                        // 已选中的数据
                        app.checked = options.checkedData ? options.checkedData : {
                            province: [],
                            citys: []
                        };
                        // 标记不可选的地区
                        app.onDisableRegion(options.editerFormIndex);
                        // 取消全选按钮
                        app.checkAll = false;
                        // 开启弹窗
                        layer.open({
                            type: 1,
                            shade: false,
                            skin:'layui-layer-background-none',
                            title: '选择可配送区域',
                            btn: ['确定', '取消'],
                            area: ['760px', '450px'], //宽高
                            content: $(this.$refs['choice']),
                            yes: function (index) {
                                if (app.checked.citys.length <= 0) {
                                    layer.msg('请选择区域~');
                                    return false;
                                }
                                options.complete(app.checked);
                                layer.close(index);
                            }
                        });
                    },

                    // 选择省份
                    onCheckedProvince: function (id,checked) {
                        var provinceId = parseInt(id);
                        this.checkedProvince(provinceId, checked);
                    },

                    checkedProvince: function (provinceId, checked) {
                        var app = this;
                        // 更新省份选择
                        var index = app.checked.province.indexOf(provinceId);
                        if (!checked) {
                            index > -1 && app.checked.province.splice(index, 1);
                        } else {
                            index === -1 && app.checked.province.push(provinceId);
                        }
                        // 更新城市选择
                        var cityIds = app.regions[provinceId].city;
                        for (var cityIndex in cityIds) {
                            if (cityIds.hasOwnProperty(cityIndex)) {
                                var cityId = parseInt(cityIndex);
                                var checkedIndex = app.checked.citys.indexOf(cityId);
                                if (!checked) {
                                    checkedIndex > -1 && app.checked.citys.splice(checkedIndex, 1)
                                } else {
                                    checkedIndex === -1 && app.checked.citys.push(cityId);
                                }
                            }
                        }
                    },

                    // 选择城市
                    onCheckedCity: function (value, provinceId,checked) {
                        var cityId = parseInt(value);
                        if (!checked) {
                            var index = this.checked.citys.indexOf(cityId);
                            index > -1 && this.checked.citys.splice(index, 1);
                        } else {
                            this.checked.citys.push(cityId);
                        }
                        // 更新省份选中状态
                        this.onUpdateProvinceChecked(parseInt(provinceId));
                    },

                    // 更新省份选中状态
                    onUpdateProvinceChecked: function (provinceId) {
                        var provinceIndex = this.checked.province.indexOf(provinceId);
                        var isExist = provinceIndex > -1;
                        if (!this.onHasCityChecked(provinceId)) {
                            isExist && this.checked.province.splice(provinceIndex, 1);
                        } else {
                            !isExist && this.checked.province.push(provinceId);
                        }

                    },

                    // 是否存在城市被选中
                    onHasCityChecked: function (provinceId) {
                        var app = this;
                        var cityIds = this.regions[provinceId].city;
                        for (var cityId in cityIds) {
                            if (cityIds.hasOwnProperty(cityId)
                                && app.inArray(parseInt(cityId), app.checked.citys))
                                return true;
                        }
                        return false;
                    },

                    // 数组中是否存在指定的值
                    inArray: function (val, array) {
                         val = parseInt(val);
                        return array.indexOf(val) > -1;
                    },

                    // 对象的属性是否存在
                    isPropertyExist: function (key, obj) {
                        return obj.hasOwnProperty(key);
                    },

                    // 数组合并
                    arrayMerge: function (arr1, arr2) {
                        return arr1.concat(arr2);
                    },
                    /**
                     * 获取当前data
                     */
                    getData: function () {
                        return this.$data.forms;
                    }
                }
            });
        }
    };

    window.delivery = delivery;

})(window);
