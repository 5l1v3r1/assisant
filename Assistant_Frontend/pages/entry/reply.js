// pages/entry/reply.js
Page({
  // 页面的初始数据
  data: {
    reply_word: 1500
  },

  // 初始加载拆解内容
  onLoad: function (options) {
    var id = options.id;
    this.setData({
      case_id: id
    });

    var that = this;
    wx.request({
      url: 'https://pww.wanqingbo.com/api/get_reply/',
      method: 'post',
      data: {
        token: wx.getStorageSync('token'),
        case_id: id
      },
      success: res => {
        if (res.data.length != 0) {
          that.setData({
            reply_content: res.data[0].reply
          })
        }
      }
    })
  },

  formSubmit: function(e) {
    wx.request({
      url: 'https://pww.wanqingbo.com/api/reply/',
      method: 'post',
      data: {
        token: wx.getStorageSync('token'),
        case_id: e.detail.value.case_id,
        reply: e.detail.value.reply
      },
      success: res => {
        switch (res.data) {
          case 1:
            wx.showToast({
              title: '发布成功！',
              mask: true,
              duration: 2000
            }),
              setTimeout(
                function () {
                  wx.navigateBack({
                    delta: 1
                  })
                },
                1500
              )
            break;
          case 0:
            wx.showToast({
              title: '发布失败！',
              icon: 'none',
              mask: true,
              duration: 2000
            })
            break;
          case -1:
            wx.showToast({
              title: '填写不完整！',
              mask: true,
              duration: 2000
            })
            break;
        }
      }
    })
  }
})