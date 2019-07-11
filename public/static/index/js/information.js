generateHeader();
generateFooter();

var informationVue = new Vue({
    el: '#information',
    data: {
        infoIndex: 0,
        navs: [
            {name: '媒体报道',isActive: true, isShow:true},
            {name: '企业日志',isActive: false, isShow:false}
        ],
        newsPageIndex: 1,  //当前新闻第几页
        newsPageCount: [],  //新闻总页数
        news: [], //新闻
        recordPageIndex: 1, //当前日志第几页
        recordPageCount: [], //日志总页数
        records: [], //日志
    },
    methods: {
        //切换导航
        infoShow: function (index) {
            //样式切换
            for(var i=0;i<this.navs.length;i++){
                this.navs[i].isActive=false;
                this.navs[i].isShow=false;
                if (index==i){
                    this.navs[index].isActive=true;
                    this.navs[index].isShow=true;
                }
            }
            //对应模块切换
            this.infoIndex = index;
        },
        //新闻跳页
        toNewsPage: function (index) {
            for(var i=0;i<this.newsPageCount.length;i++){
                this.newsPageCount[i].isActive=false;
                if (index==i){
                    this.newsPageCount[index].isActive=true;
                }
            }
            if (event) {
                //当前页数
                this.newsPageIndex = event.target.childNodes[0].nodeValue
            }
        },
        //新闻上一页
        preNewsPage: function (index) {
            this.newsPageIndex = index-1;
            var newsActiveIndex = this.newsPageIndex-1;
            if (newsActiveIndex>=0){
                //当前页样式
                for (var i=0;i<this.newsPageCount.length;i++){
                    this.newsPageCount[i].isActive = false;
                    if (newsActiveIndex==i){
                        this.newsPageCount[newsActiveIndex].isActive = true;
                    }
                }
            }else{
                this.newsPageIndex = 1;
                return;
            }
        },
        //新闻下一页
        nextNewsPage: function (index) {
            this.newsPageIndex = Number(index)+1;
            var newsActiveIndex = this.newsPageIndex-1;
            if (newsActiveIndex<=this.newsPageCount.length-1){
                //当前页样式
                for (var i=0;i<this.newsPageCount.length;i++){
                    this.newsPageCount[i].isActive = false;
                    if (newsActiveIndex==i){
                        this.newsPageCount[newsActiveIndex].isActive = true;
                    }
                }
            }else{
                this.newsPageIndex = this.newsPageCount.length;
                return;
            }
        },
        //日志跳页
        toRecordPage: function (index) {
            for(var i=0;i<this.recordPageCount.length;i++){
                this.recordPageCount[i].isActive=false;
                if (index==i){
                    this.recordPageCount[index].isActive=true;
                }
            }
            if (event) {
                //当前页数
                this.recordPageIndex = event.target.childNodes[0].nodeValue
            }
        },
        //日志上一页
        preRecordPage: function (index) {
            this.recordPageIndex = index-1;
            var recordActiveIndex = this.recordPageIndex-1;
            if (recordActiveIndex>=0){
                //当前页样式
                for (var i=0;i<this.recordPageCount.length;i++){
                    this.recordPageCount[i].isActive = false;
                    if (recordActiveIndex==i){
                        this.recordPageCount[recordActiveIndex].isActive = true;
                    }
                }
            }else{
                this.recordPageIndex = 1;
                return;
            }
        },
        //日志下一页
        nextRecordPage: function (index) {
            this.recordPageIndex = Number(index)+1;
            var recordActiveIndex = this.recordPageIndex-1;
            if (recordActiveIndex<=this.recordPageCount.length-1){
                //当前页样式
                for (var i=0;i<this.recordPageCount.length;i++){
                    this.recordPageCount[i].isActive = false;
                    if (recordActiveIndex==i){
                        this.recordPageCount[recordActiveIndex].isActive = true;
                    }
                }
            }else{
                this.recordPageIndex = this.recordPageCount.length;
                return;
            }
        }
    },
    watch: {
        newsPageIndex: function (newIndex) {
            console.log("newsPageIndex:",newIndex);
        },
        recordPageIndex: function (newIndex) {
            console.log("recordPageIndex:",newIndex);
        }
    },
    beforeMount(){
        //新闻总页数
        var newsTotalPage = 10;

        //当前页样式
        for (var i=0;i<newsTotalPage;i++){
            this.newsPageCount.push({'isActive': false})
            this.newsPageCount[0].isActive = true;
        }

        //新闻
        this.news.push({
                url: 'http://www.baidu.com',
                img: 'assets/information_news.png',
                title: '央行：2019年要全面推广移动支付应用',
                desc:'据央行官网显示，2019年3月29日，人民银行召开2019年支付结算工作电视电话会议。会议全面总结了2018年人民行支付结算工作，深入分析了当前面临的困难与挑战，并就下一阶段重点工作作出全面部署。人民银行党委委员、副行长范一飞出席会议并讲话。',
                from: '新京报'
            },
            {
                url: 'http://www.baidu.com',
                img: 'assets/information_news.png',
                title: '全球移动支付进行中，前十大市场亚洲占八，中国第一',
                desc:'随着移动互联网的发展，移动支付也变得深入我们的生活，可能我们现在对移动支付觉得没什么，因为我们现在大多都会使用移动支付，甚至很多年轻人出行在外完全不带现金，支付宝，微信等移动支付app早已深入人心，但实际上，在出了我们国家外，有些地方移动支付并没有这么便捷，甚至有些发达国家对于移动支付还处于大众未接受的状态。',
                from: '新京报'
            },
            {
                url: 'http://www.baidu.com',
                img: 'assets/information_news.png',
                title: '乡村流行“移动支付”',
                desc:'移动支付已经成为农村地区主流支付方式，去年非银行支付机构为农村地区提供移动支付业务近2800亿笔。',
                from: '新京报'
            },
            {
                url: 'http://www.baidu.com',
                img: 'assets/information_news.png',
                title: '央行：2019年要全面推广移动支付应用',
                desc:'据央行官网显示，2019年3月29日，人民银行召开2019年支付结算工作电视电话会议。会议全面总结了2018年人民行支付结算工作，深入分析了当前面临的困难与挑战，并就下一阶段重点工作作出全面部署。人民银行党委委员、副行长范一飞出席会议并讲话。',
                from: '新京报'
            },
            {
                url: 'http://www.baidu.com',
                img: 'assets/information_news.png',
                title: '央行：2019年要全面推广移动支付应用',
                desc:'据央行官网显示，2019年3月29日，人民银行召开2019年支付结算工作电视电话会议。会议全面总结了2018年人民行支付结算工作，深入分析了当前面临的困难与挑战，并就下一阶段重点工作作出全面部署。人民银行党委委员、副行长范一飞出席会议并讲话。',
                from: '新京报'
            },
            {
                url: 'http://www.baidu.com',
                img: 'assets/information_news.png',
                title: '全球移动支付进行中，前十大市场亚洲占八，中国第一',
                desc:'随着移动互联网的发展，移动支付也变得深入我们的生活，可能我们现在对移动支付觉得没什么，因为我们现在大多都会使用移动支付，甚至很多年轻人出行在外完全不带现金，支付宝，微信等移动支付app早已深入人心，但实际上，在出了我们国家外，有些地方移动支付并没有这么便捷，甚至有些发达国家对于移动支付还处于大众未接受的状态。',
                from: '新京报'
            },
            {
                url: 'http://www.baidu.com',
                img: 'assets/information_news.png',
                title: '乡村流行“移动支付”',
                desc:'移动支付已经成为农村地区主流支付方式，去年非银行支付机构为农村地区提供移动支付业务近2800亿笔。',
                from: '新京报'
            },
            {
                url: 'http://www.baidu.com',
                img: 'assets/information_news.png',
                title: '央行：2019年要全面推广移动支付应用',
                desc:'据央行官网显示，2019年3月29日，人民银行召开2019年支付结算工作电视电话会议。会议全面总结了2018年人民行支付结算工作，深入分析了当前面临的困难与挑战，并就下一阶段重点工作作出全面部署。人民银行党委委员、副行长范一飞出席会议并讲话。',
                from: '新京报'
            });

        //日志总页数
        var recordTotalPage = 10;

        //当前页样式
        for (var i=0;i<recordTotalPage;i++){
            this.recordPageCount.push({'isActive': false})
            this.recordPageCount[0].isActive = true;
        }

        //日志
        this.records.push(
            {
                title: '央行：2019年要全面推广移动支付应用',
                desc:'据央行官网显示，2019年3月29日，人民银行召开2019年支付结算工作电视电话会议。会议全面总结了2018年人民行支付结算工作，深入分析了当前面临的困难与挑战，并就下一阶段重点工作作出全面部署。人民银行党委委员、副行长范一飞出席会议并讲话。',
                from: '新京报'
            },
            {
                title: '全球移动支付进行中，前十大市场亚洲占八，中国第一',
                desc:'随着移动互联网的发展，移动支付也变得深入我们的生活，可能我们现在对移动支付觉得没什么，因为我们现在大多都会使用移动支付，甚至很多年轻人出行在外完全不带现金，支付宝，微信等移动支付app早已深入人心，但实际上，在出了我们国家外，有些地方移动支付并没有这么便捷，甚至有些发达国家对于移动支付还处于大众未接受的状态。',
                from: '新京报'
            },
            {
                title: '央行：2019年要全面推广移动支付应用',
                desc:'据央行官网显示，2019年3月29日，人民银行召开2019年支付结算工作电视电话会议。会议全面总结了2018年人民行支付结算工作，深入分析了当前面临的困难与挑战，并就下一阶段重点工作作出全面部署。人民银行党委委员、副行长范一飞出席会议并讲话。',
                from: '新京报'
            },
            {
                title: '全球移动支付进行中，前十大市场亚洲占八，中国第一',
                desc:'随着移动互联网的发展，移动支付也变得深入我们的生活，可能我们现在对移动支付觉得没什么，因为我们现在大多都会使用移动支付，甚至很多年轻人出行在外完全不带现金，支付宝，微信等移动支付app早已深入人心，但实际上，在出了我们国家外，有些地方移动支付并没有这么便捷，甚至有些发达国家对于移动支付还处于大众未接受的状态。',
                from: '新京报'
            },
            {
                title: '央行：2019年要全面推广移动支付应用',
                desc:'据央行官网显示，2019年3月29日，人民银行召开2019年支付结算工作电视电话会议。会议全面总结了2018年人民行支付结算工作，深入分析了当前面临的困难与挑战，并就下一阶段重点工作作出全面部署。人民银行党委委员、副行长范一飞出席会议并讲话。',
                from: '新京报'
            },
            {
                title: '全球移动支付进行中，前十大市场亚洲占八，中国第一',
                desc:'随着移动互联网的发展，移动支付也变得深入我们的生活，可能我们现在对移动支付觉得没什么，因为我们现在大多都会使用移动支付，甚至很多年轻人出行在外完全不带现金，支付宝，微信等移动支付app早已深入人心，但实际上，在出了我们国家外，有些地方移动支付并没有这么便捷，甚至有些发达国家对于移动支付还处于大众未接受的状态。',
                from: '新京报'
            }
        )
    }
})