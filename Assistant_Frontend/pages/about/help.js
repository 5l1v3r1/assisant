// pages/about/help.js
Page({
  // 页面的初始数据
  data: {
    showEgg: false,
    showRules: true,
    img_mode: 'aspectFit'
  },

  // 用户点击右上角分享
  onShareAppMessage: function () {
    return {
      title: '52私董会小助手帮助',
      path: '/pages/about/help'
    }
  },

  // 显示彩蛋
  onPullDownRefresh: function() {
    wx.stopPullDownRefresh({
      success: res => {
        this.setData({
          showEgg: !this.data.showEgg,
          showRules: !this.data.showRules,
          egg: '../../images/liurun.jpeg'
        })
      }
    })
  }
})