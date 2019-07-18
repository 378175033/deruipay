generateHeader();
generateFooter();

//动画
var animation = bodymovin.loadAnimation({
    container: document.getElementById('bm'),
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: '/static/index/static/data.json'
})

//banner轮播
var swiperBanner = new Swiper ('#swiper-banner',{
    autoplay: {
        delay: 3000,
        stopOnLastSlide: false,
        disableOnInteraction: true
    }
})

//支付产品轮播
var swiperPayType = new Swiper ('#swiper-pay-type', {
})

//切换轮播
function slidePrev() {
    swiperPayType.slidePrev();
}

function sildeNext() {
    swiperPayType.slideNext();
}
