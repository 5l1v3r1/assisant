// pages/about/about.js
Page({
  // 页面的初始数据
  data: {
    qr_code: '../../images/reward.jpg',
    img_mode: 'aspectFit'
  },

  // 显示打赏二维码图片
  load_qr: function (e) {
    wx.previewImage({
      urls: ['https://pww.wanqingbo.com/images/reward.jpg'],
    })
  },

  // 用户点击右上角分享
  onShareAppMessage: function () {
    return {
      title: '关于52私董会小助手',
      path: '/pages/about/about'
    }
  }
})