// pages/publish/publish.js
Page({
  // 页面的初始数据
  data: {
    'context_word': 800,
    'question_word': 200
  },

  // 提交案例内容
  formSubmit: function (e) {
    wx.showModal({
      title: '案例提交',
      content: '提交后无法修改，确认提交吗？',
      success: res => {
        if (res.confirm) {
          this.submitCase(e.detail)
        }
      }
    })
    
  },

  submitCase: function(e) {
    wx.request({
      url: 'https://pww.wanqingbo.com/api/publish/',
      method: 'post',
      data: {
        token: wx.getStorageSync('token'),
        context: e.value.context,
        question: e.value.question
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
                  wx.navigateTo({
                    url: '../entry/entry',
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