// index.js
// 获取应用实例
const app = getApp()

Page({
  data: {
    userInfo: {},
    hasUserInfo: false,
    isTime: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo')
  },

  // 事件处理函数
  entry: function() {
    wx.navigateTo({
      url: '../entry/entry'
    })
  },

  publish: function () {
    wx.navigateTo({
      url: '../publish/publish'
    })
  },

  onShareAppMessage: function (res) {
    return {
      title: '52私董会小助手',
      path: '/pages/index/index'
    }
  },

  // 页面加载处理
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
    wx.request({
      url: 'https://pww.wanqingbo.com/api/is_time/',
      success: res=> {
        if(res.data == 1) {
          this.setData({
            isTime: true
          })
        }
      }
    })
  },

  getUserInfo: function(e) {
    app.globalData.userInfo = e.detail.userInfo
    if (e.detail.userInfo) {
      this.setData({
        userInfo: e.detail.userInfo,
        hasUserInfo: true
      })
      wx.setStorageSync("user_info", app.globalData.userInfo)
      wx.request({
        url: 'https://pww.wanqingbo.com/api/user/',
        method: 'post',
        data: {
          token: app.globalData.token,
          user_info: app.globalData.userInfo
        },
        success: res => {
          if (res.data == 1) {
            console.log("新增登录用户。")
          } else {
            console.log("登录用户已存在。")
          }
        }
      })
    } else {
      this.setData({
        userInfo: {},
        hasUserInfo: false
      })
    }
  }
})
