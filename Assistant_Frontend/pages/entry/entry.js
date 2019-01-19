// pages/entry/entry.js
Page({
  // 页面的初始数据
  data: {
      // 留空
  },
  
  onLoad: function () {
    var that = this;
    wx.request({
      url: 'https://pww.wanqingbo.com/api/entry/',
      success: res => {
        that.setData({
          listData: res.data
        })
      }
    })
  },

  // 筛选案例日期
  bindDateChange: function(e) {
    this.setData({
      date: e.detail.value
    })
    wx.request({
      url: 'https://pww.wanqingbo.com/api/filter_case/',
      method: 'post',
      data: {
        date: e.detail.value
      },
      success: res => {
        this.setData({
          listData: res.data
        })
      }
    })
  }
})