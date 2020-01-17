//index.js
//获取应用实例
const app = getApp()
const core = app.requirejs('core');
Page({
  onShareAppMessage() {
    return {
      title: 'swiper',
      path: 'page/component/pages/swiper/swiper'
    }
  },
  data: {
    motto: 'Hello World',
    userInfo: {},
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
    background: ['demo-text-1', 'demo-text-2', 'demo-text-3'],
    user: [],
    indicatorDots: true,
    vertical: false,
    autoplay: true,
    interval: 2000,
    duration: 500,
    acolor:'',
    colorlist:[0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f']
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse){
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }
  },
  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  },
  changecolor:function(res){
    var that = this;
    that.setData({
      acolor: that.getnum()
    })
  },
  getnum:function(){
    var clist = this.data.colorlist;
    var num = clist.length;
    var n = 0;
    var colorstr = '#';
    for (var i = 1; i <= 6; i++) {
      n = Math.floor(Math.random() * num);
      colorstr += clist[n];
    }
    return colorstr;
  },
  getTruth: function(){
    var that = this;
    app.gets('http://location/',{data:1
    },function(res){
      if (res.data.error==0){
        var data = res.data.data;
        var user = that.data.user;
        if (user){
          // user.shift();
          user.splice(0,1)
        }
        user.push({ id: data.id,nickname: data.nickname, avatar: data.avatar});
        that.setData({
          user: user
        })
      }
    })
  }
})
