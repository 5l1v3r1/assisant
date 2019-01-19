// app.js
App({
  globalData: {
    userInfo: null,
    token: null
  },

  onLaunch: function () {
    // 检查小程序更新
    this.updateManager()
    this.login()
  },

  // 判断和更新小程序
  updateManager: function() {
    const updateManager = wx.getUpdateManager()

    updateManager.onCheckForUpdate(function (res) {
      // 请求完新版本信息的回调
      if (res.hasUpdate) {
        console.log('有新版本可更新.')
      }else {
        console.log('无新版本可更新')
      }
    })

    updateManager.onUpdateReady(function () {
      wx.showModal({
        title: '更新提示',
        content: '新版本已经准备好，是否重启应用？',
        success: function (res) {
          if (res.confirm) {
            // 新的版本已经下载好，调用 applyUpdate 应用新版本并重启
            updateManager.applyUpdate()
          }
        }
      })
    })

    updateManager.onUpdateFailed(function () {
      // 新的版本下载失败
      wx.showModal({
        title: '更新提示',
        content: '新版本下载失败',
        showCancel: false
      })
    })
  },

  // 登录获取 token 及用户信息
  login: function() {
    wx.login({
      success: res => {
        if (res.code) {
          this.getLoginToken(res.code)  // 发送 res.code 获得token
          this.getUserInfo()  // 登陆后获取用户信息
        } else {
          console.log("登录失败：" + res.errMsg);
        }
      }
    })
  },

  // 获取用户信息
  getUserInfo: function() {
    wx.getSetting({
      success: res => {
        if (res.authSetting['scope.userInfo']) {
          wx.getUserInfo({
            success: res => {
              this.globalData.userInfo = res.userInfo

              // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
              // 所以此处加入 callback 以防止这种情况
              if (this.userInfoReadyCallback) {
                this.userInfoReadyCallback(res)
              }
            }
          })
        }
      }
    })
  },

  // 获取用户 token
  getLoginToken: function(e) {
    wx.request({
      url: 'https://pww.wanqingbo.com/api/login/',
      method: 'post',
      data: {
        code: e
      },
      success: res => {
        this.globalData.token = res.data
        wx.setStorageSync('token', res.data)
      }
    })
  }
})