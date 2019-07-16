/**
 * 头部组件
 */
function generateHeader(){
    Vue.component('header-component', {
        data: function () {
            return {
                headerChangeClass: '', //头部背景颜色
                navs: [
                    {url: 'index.html',name: '首页'},
                    {url: 'intrduce.html',name: '产品介绍'},
                    {url: 'api.html',name: '开发文档'},
                    {url: 'download.html',name: '下载中心'},
                    {url: 'contact.html',name: '联系我们'}
                ],
                infos: [
                    {url: 'javascript:void(0)',text: '028-40082828',value:0},
                    {url: 'javascript:void(0)',text: '登录',value:1},
                    {url: 'javascript:void(0)',text: '注册',value:2}
                ]
            }
        },
        props: ['postClass'],  //头部动态背景颜色传值
        template: '<div class="header" :class="[headerChangeClass]">\n' +
            '        <div class="logo"></div>\n' +
            '        <ul class="header-container header-nav">\n' +
            '            <li v-for="item in navs"><a :href="item.url">{{ item.name }} </a></li>\n' +
            '        </ul>\n' +
            '        <ul class="header-container header-info">\n' +
            '            <li v-for="item in infos">' +
            '               <a :href="item.url" v-if="item.value==0">{{ item.text }}</a>'+
            '               <a :href="item.url" v-if="item.value==1" @click="this.logins()">{{ item.text }}</a>'+
            '               <a :href="item.url" v-if="item.value==2" @click="this.register()">{{ item.text }}</a>'+
            '            </li>\n' +
            '        </ul>\n' +
            '    </div>',
        beforeMount: function () {
            this.headerChangeClass = this.postClass;
        }
    })
    var header = new Vue({
        el: '#header',
    });
}
/**
 * 底部组件
 */
function generateFooter() {
    Vue.component('footer-component', {
        data: function () {
            return {
                navs: [
                    {
                        name: '帮助中心',
                        lists: [
                            {name: '常见问题',url: 'javascript:void(0)',isActive: false},
                            {name: '建议与意见',url: 'suggest.html',isActive: false},
                        ]
                    },
                    {
                        name: '联系我们',
                        lists: [
                            {name: '全国热线服务',url: 'javascript:void(0)',isActive: false},
                            {name: '400-8828-8868',url: 'javascript:void(0)',isActive: true},
                            {name: '8:00-21:00',url: 'javascript:void(0)',isActive: false}
                        ]
                    },
                    {
                        name: '企业邮箱',
                        lists: [
                            {name: 'derui@123.c',url: 'javascript:void(0)',isActive: true}
                        ]
                    },
                    {
                        name: '关于我们',
                        lists: [
                            {name: '资讯中心',url: 'information.html',isActive: false},
                            {name: '平台协议',url: 'agreement.html',isActive: false},
                            {name: '法律声明',url: 'agreement.html',isActive: false}
                        ]
                    }
                ]
            }
        },
        template: '<div class="footer">\n' +
            '        <ul class="footer-nav">\n' +
            '            <li>' +
            '               <a v-for="navItem in navs" class="footer-title">{{ navItem.name }}' +
            '                  <li class="first-list"><a v-for="item in navItem.lists" :href="item.url" class="footer-list" :class="{ active: item.isActive }">{{ item.name }}</a></li>\n' +
            '               </a>' +
            '            </li>\n' +
            '        </ul>\n' +
            '        <div class="footer-copyright">\n' +
            '            <div>\n' +
            '                <p>Copyright © 2019 得瑞支付 All rights reserved. 版权所有 蜀ICP11031416号-2</p>\n' +
            '                <p>公网安备110107125551202</p>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>'
    })

    var footer = new Vue({
        el: '#footer'
    })
}


