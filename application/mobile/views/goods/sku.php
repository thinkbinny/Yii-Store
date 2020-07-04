<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sku 多维属性状态判断</title>
    <script src="http://misc.360buyimg.com/jdf/lib/jquery-1.6.4.js"></script>
    <style>
        body {
            font-size: 12px;
        }
        dt {
            width: 100px;
            text-align: right;
        }
        dl {
            clear: both;
            overflow:hidden;
        }
        dl.hl {
            background:#ddd;
        }
        dt, dd {
            float:left;
            height: 40px;
            line-height: 40px;
            margin-left: 10px;
        }
        button {
            font-size: 14px;
            font-weight: bold;
            padding: 4px 4px;
        }
        .disabled {
            color:#999;
            border: 1px dashed #666;
        }
        .active {
            color: red;
        }
    </style>
</head>
<body>

<p>
    <textarea id="data_area" cols="100" rows="10">
[
   { "颜色": "红", "尺码": "大", "型号": "A", "skuId": "3158055" },
   { "颜色": "白", "尺码": "大", "型号": "A", "skuId": "3158054" },
   { "颜色": "白", "尺码": "中", "型号": "B", "skuId": "3133859" },
   { "颜色": "蓝", "尺码": "小", "型号": "C", "skuId": "3516833" }
]
    </textarea>
</p>
<p>
    <input onclick="updateData()" type="button" value="更新数据">
</p>

<hr>

<div id="app"></div>

<hr>

<div id="msg"></div>

<script>
    var data = JSON.parse($('#data_area').val())

    var res = {}

    var spliter = '\u2299'
    var r = {}
    var keys = []
    var selectedCache = []

    /**
     * 计算组合数据
     */
    function combineAttr(data, keys) {
        var allKeys = []
        var result = {}

        for (var i = 0; i < data.length; i++) {
            var item = data[i]
            var values = []

            for (var j = 0; j < keys.length; j++) {
                var key = keys[j]
                if (!result[key]) result[key] = []
                if (result[key].indexOf(item[key]) < 0) result[key].push(item[key])
                values.push(item[key])
            }
            allKeys.push({
                path: values.join(spliter),
                sku: item['skuId']
            })
        }
        return {
            result: result,
            items: allKeys
        }
    }

    /**
     * 渲染 DOM 结构
     */
    function render(data) {
        var output = ''
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i];
            var items = data[key]
            output += '<dl data-type="'+ key +'" data-idx="'+ i +'">'
            output += '<dt>'+ key +':</dt>'
            output += '<dd>'
            for (var j = 0; j < items.length; j++) {
                var item = items[j]
                var cName = j == 0 ? 'active' : ''
                if (j == 0) { selectedCache.push(item) }
                output += '<button data-title="'+ item +'" class="'+ cName +'" value="'+ item +'">'+ item +'</button> '
            }
            output += '</dd>'
            output += '</dl>'
        }
        output += '</dl>'

        $('#app').html(output)
    }

    function getAllKeys(arr) {
        var result = []
        for (var i = 0; i < arr.length; i++) { result.push(arr[i].path) }
        return result
    }

    /**
     * 取得集合的所有子集「幂集」
     arr = [1,2,3]

     i = 0, ps = [[]]:
     j = 0; j < ps.length => j < 1:
     i=0, j=0 ps.push(ps[0].concat(arr[0])) => ps.push([].concat(1)) => [1]
     ps = [[], [1]]

     i = 1, ps = [[], [1]] :
     j = 0; j < ps.length => j < 2
     i=1, j=0 ps.push(ps[0].concat(arr[1])) => ps.push([].concat(2))  => [2]
     i=1, j=1 ps.push(ps[1].concat(arr[1])) => ps.push([1].concat(2)) => [1,2]
     ps = [[], [1], [2], [1,2]]

     i = 2, ps = [[], [1], [2], [1,2]]
     j = 0; j < ps.length => j < 4
     i=2, j=0 ps.push(ps[0].concat(arr[2])) => ps.push([3])    => [3]
     i=2, j=1 ps.push(ps[1].concat(arr[2])) => ps.push([1, 3]) => [1, 3]
     i=2, j=2 ps.push(ps[2].concat(arr[2])) => ps.push([2, 3]) => [2, 3]
     i=2, j=3 ps.push(ps[3].concat(arr[2])) => ps.push([2, 3]) => [1, 2, 3]
     ps = [[], [1], [2], [1,2], [3], [1, 3], [2, 3], [1, 2, 3]]
     */
    function powerset(arr) {
        var ps = [[]];
        for (var i=0; i < arr.length; i++) {
            for (var j = 0, len = ps.length; j < len; j++) {
                ps.push(ps[j].concat(arr[i]));
            }
        }
        return ps;
    }
    /**
     * 生成所有子集是否可选、库存状态 map
     */
    function buildResult(items) {
        var allKeys = getAllKeys(items)

        for (var i = 0; i < allKeys.length; i++) {
            var curr = allKeys[i]
            var sku = items[i].sku
            var values = curr.split(spliter)

            // var allSets = getAllSets(values)
            var allSets = powerset(values)

            // 每个组合的子集
            for (var j = 0; j < allSets.length; j++) {
                var set = allSets[j]
                var key = set.join(spliter)

                if (res[key]) {
                    res[key].skus.push(sku)
                } else {
                    res[key] = {
                        skus: [sku]
                    }
                }
            }
        }
    }

    function trimSpliter(str, spliter) {
        // ⊙abc⊙ => abc
        // ⊙a⊙⊙b⊙c⊙ => a⊙b⊙c
        var reLeft        = new RegExp('^' + spliter + '+', 'g');
        var reRight       = new RegExp(spliter + '+$', 'g');
        var reSpliter = new RegExp(spliter + '+', 'g');
        return str.replace(reLeft, '')
            .replace(reRight, '')
            .replace(reSpliter, spliter)
    }

    /**
     * 获取当前选中的属性
     */
    function getSelectedItem() {
        var result = []
        $('dl[data-type]').each(function () {
            var $selected = $(this).find('.active')
            if ($selected.length) {
                result.push($selected.val())
            } else {
                result.push('')
            }
        })

        return result
    }

    /**
     * 更新所有属性状态
     */
    function updateStatus(selected) {
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i];
            var data = r.result[key]
            var hasActive = !!selected[i]
            var copy = selected.slice()

            for (var j = 0; j < data.length; j++) {
                var item = data[j]
                if (selected[i] == item) continue
                copy[i] = item

                var curr = trimSpliter(copy.join(spliter), spliter)
                var $item = $('dl').filter('[data-type="'+ key +'"]').find('[value="'+ item +'"]')

                var titleStr = '['+ copy.join('-') +']'

                if (res[curr]) {
                    $item.removeClass('disabled')
                    setTitle($item.get(0))
                } else {
                    $item.addClass('disabled').attr('title', titleStr + ' 无此属性搭配')
                }
            }
        }
    }

    /**
     * 正常属性点击
     */
    function handleNormalClick($this) {
        $this.siblings().removeClass('active')
        $this.addClass('active')
    }

    /**
     * 无效属性点击
     */
    function handleDisableClick($this) {
        var $currAttr = $this.parents('dl').eq(0)
        var idx = $currAttr.data('idx')
        var type = $currAttr.data('type')
        var value = $this.val()

        $this.removeClass('disabled')
        selectedCache[idx] = value

        console.log(selectedCache)
        // 清空高亮行的已选属性状态（因为更新的时候默认会跳过已选状态）
        $('dl').not($currAttr).find('button').removeClass('active')
        updateStatus(getSelectedItem())

        /**
         * 恢复原来已选属性
         * 遍历所有非当前属性行
         *   1. 与 selectedCache 对比
         *   2. 如果要恢复的属性存在（非 disable）且 和当前*未高亮行*已选择属性的*可组合*），高亮原来已选择的属性且更新
         *   3. 否则什么也不做
         */
        for (var i = 0; i < keys.length; i++) {
            var item = keys[i]
            var $curr = $('dl[data-type="'+ item +'"]')
            if (item == type) continue

            var $lastSelected = $curr.find('button[value="'+ selectedCache[i] +'"]')

            // 缓存的已选属性没有 disabled (可以被选择)
            if (!$lastSelected.hasClass('disabled')) {
                $lastSelected.addClass('active')
                updateStatus(getSelectedItem())
            }
        }

    }

    /**
     * 高亮当前属性区
     */
    function highLighAttr() {
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i]
            var $curr = $('dl[data-type="'+ key +'"]')
            if ($curr.find('.active').length < 1) {
                $curr.addClass('hl')
            } else {
                $curr.removeClass('hl')
            }
        }
    }

    function bindEvent() {
        $('#app').undelegate().delegate('button', 'click', function (e) {
            var $this = $(this)

            var isActive = $this.hasClass('.active')
            var isDisable = $this.hasClass('disabled')

            if (!isActive) {
                handleNormalClick($this)

                if (isDisable) {
                    handleDisableClick($this)
                } else {
                    selectedCache[$this.parents('dl').eq(0).data('idx')] = $this.val()
                }
                updateStatus(getSelectedItem())
                highLighAttr()
                showResult()
            }
        })

        $('button').each(function () {
            var value = $(this).val()

            if (!res[value] && !$(this).hasClass('active')) {
                $(this).addClass('disabled')
            }
        })
    }

    function showResult() {
        var result = getSelectedItem()
        var s = []

        for (var i = 0; i < result.length; i++) {
            var item = result[i];
            if (!!item) {
                s.push(item)
            }
        }

        if (s.length == keys.length) {
            var curr = res[s.join(spliter)]

            if (curr) {
                s = s.concat(curr.skus)
            }
            $('#msg').html('已选择：' + s.join('\u3000-\u3000'))
        }
    }

    function updateData() {
        data = JSON.parse($('#data_area').val())
        init(data)
    }

    function setTitle(el) {
        var title = $(el).data('title');
        if (title) $(el).attr('title', title);
    }
    function setAllTitle() {
        $('#app').find('button').each(setTitle)
    }

    function init(data) {
        res = {}
        r = {}
        keys = []
        selectedCache = []

        for (var attr_key in data[0]) {
            if (!data[0].hasOwnProperty(attr_key)) continue;
            if (attr_key != 'skuId') keys.push(attr_key)
        }
        setAllTitle();

        r = combineAttr(data, keys)

        render(r.result)

        buildResult(r.items)

        updateStatus(getSelectedItem())
        showResult()

        bindEvent()
    }

    init(data)


</script>

</body>
</html>